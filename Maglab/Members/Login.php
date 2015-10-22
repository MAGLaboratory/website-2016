<?php
namespace Maglab\Members;
class Login extends \Maglab\Controller {
  public function init(){
    $this->app->get('/members/?', [$this, 'require_user'], [$this, 'index']);
    $this->app->get('/members/login', [$this, 'show']);
    $this->app->post('/members/login', [$this, 'create']);
    $this->app->get('/members/logout', [$this, 'destroy']);
    $this->app->get('/members/forgot_password', [$this, 'forgot_password']);
    $this->app->post('/members/forgot_password', [$this, 'reset_password_request']);
    $this->app->get('/members/reset_password', [$this, 'reset_password_form']);
    $this->app->put('/members/reset_password', [$this, 'reset_password']);
    $this->app->post('/members/me', [$this, 'require_user'], [$this, 'update']);
    $this->app->get('/members/me/wiki', [$this, 'require_user'], [$this, 'wiki']);
  }

  public function index(){
    $user = (object)get_user_by_id($this->current_user['id']);
    $this->respond['user'] = $user;
    $this->render('members/login/show.php', 'Members');
  }
  
  public function show(){
    $this->render('members/login/login.php', 'Login');
  }
  
  public function create(){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare('SELECT id, email, pwhash FROM users WHERE email = ? LIMIT 1')){
      $post = $this->app->request->post();
      $stmt->bind_param('s', $post['email']);
      $stmt->execute();
      if($res = $stmt->get_result()){
        $user = $res->fetch_assoc();
        $res->free();
        if($user and password_verify($post['password'], $user['pwhash'])){
          $session = member_login($user['id']);
          $this->app->setCookie('auth', $session);
          $this->app->redirect('/members');
          die();
        }
      }
    }
    $this->render('members/login/login.php', 'Login');
  }
  
  public function destroy(){
    $auth = $this->app->getCookie('auth');
    if(is_string($auth)){ $this->member_logout($auth); }
    $this->app->deleteCookie('auth');
    $this->app->deleteCookie('mag_session');
    $this->app->redirect('/members');
    # This does a double redirect, but ensures we're really logged out
  }
  
  public function reset_password_request(){
    $email = $this->app->request->post('email');
    $this->reset_password_for($email);
    $this->respond['reset_email'] = $email;
    $this->render('members/login/forgot_password.php', 'Reset Password');
  }
  
  public function reset_password_form(){
    $now = $this->app->request->params('now');
    $reset_code = $this->app->request->params('reset_code');
    $this->respond['now'] = $now;
    $this->respond['reset_code'] = $reset_code;
    $this->respond['reset_user'] = null;
    if($now and $reset_code and (int)$now - time() > -3600){
      $user = get_user_by_auth($this->reset_session_pw($now, $reset_code));
      if($user and $user['role'] and strpos($user['role'], 'Reset') > -1){
        $this->respond['reset_user'] = $user;
      }
    }
    if(!$this->respond['reset_user']){
      $this->respond['reset_expired'] = true;
    }
    $this->render('members/login/forgot_password.php', 'Reset Password');
  }
  
  public function reset_password(){
    $put = $this->app->request->put();
    $this->respond['affected_rows'] = -1;
    if($put['confirm_email'] and $put['new_password'] and $put['now'] and $put['reset_code'] and (int)$put['now'] - time() > -3600){
      $mysqli = get_mysqli_or_die();
      $pw = $this->reset_session_pw($put['now'], $put['reset_code']);
      if($stmt = $mysqli->prepare("UPDATE users SET pwhash = ?, current_session = NULL, role = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', role, ','), CONCAT(',', 'Reset', ','), ',')) WHERE FIND_IN_SET('Reset', role) > 0 AND email = ? AND current_session = ? LIMIT 1")){
        $stmt->bind_param('sss', password_hash($put['new_password'], PASSWORD_BCRYPT), $put['confirm_email'], $pw);
        $stmt->execute();
        $this->respond['affected_rows'] = $stmt->affected_rows;
      }
    }
    $this->respond['completed_reset'] = true;
    $this->render('members/login/forgot_password.php', 'Password Reset Complete');
  }
  
  public function forgot_password(){
    $this->render('members/login/forgot_password.php', 'Forgot Password');
  }
  
  public function update(){
    
    if(isset($this->app->request->post()['current_password'])){
      $this->respond['pw_success'] = $this->change_member_password();
    } else {
      $this->respond['info_success'] = $this->save_member_info();
    }
    
    $this->respond['user'] = get_user_by_id($this->current_user['id']);
    $this->render('members/login/show.php', 'Profile');
  }
  
  function wiki(){
    $user = get_user_by_id($this->current_user['id']);
    $this->respond['createdWikiUser'] = $this->params('success');
    $this->respond['user'] = get_user_by_id($this->current_user['id']);
    $this->render('members/login/show.php', 'Profile');
  }
  
  protected function reset_password_for($email){
    # We mark the role as 'Reset' to denote that password is being reset.
    # Also switch the session so they log out since they probably aren't logged in anyway
    # Password is set to Time+random session string
    $reset_code = random_b64();
    $now = time();
    $pw = $this->reset_session_pw($now, $reset_code);
    $mysqli = get_mysqli_or_die();
    $this->respond['affected_rows'] = -1;
    if($stmt = $mysqli->prepare('UPDATE users SET current_session = ?, role = CONCAT_WS(",", role, "Reset") WHERE email = ? LIMIT 1')){
      $stmt->bind_param('ss', $pw, $email);
      $stmt->execute();
      $this->respond['affected_rows'] = $stmt->affected_rows;
      if($this->respond['affected_rows'] > 0){
        $this->send_reset_email($email, $reset_code, $now);
      }
    }
  }
  
  protected function send_reset_email($email, $reset_code, $now){
    $reset_path = "/members/reset_password?now=${now}&reset_code=${reset_code}";
    $this->respond['reset_path'] = $reset_path;
    
    $reset_url = "https://www.maglaboratory.org${reset_path}";
    $email_content = $this->render_to_string('email/login/reset_password.php', array(
      'email' => $email,
      'reset_code' => $reset_code,
      'now' => $now,
      'reset_url' => $reset_url));
    $this->email_html($email, "MagLaboratory - Password Reset", $email_content);
  }
  
  protected function member_logout($session){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare('UPDATE users SET current_session = NULL WHERE current_session = ? LIMIT 1')){
      $stmt->bind_param('s', $session);
      $stmt->execute();
    }
    
    return true;
  }
  
  protected function save_member_info(){
    $mysqli = get_mysqli_or_die();
    $post = $this->app->request->post();
    # TODO: Verify Email change
    # TODO: Don't allow email change on invalid email
    
    if($stmt = $mysqli->prepare('UPDATE users SET email = ?, first_name = ?, last_name = ?, main_phone = ?, emergency_phone = ? WHERE id = ?')){
      $stmt->bind_param('sssssi', $post['email'], $post['first_name'], $post['last_name'], $post['main_phone'], $post['emergency_phone'], $this->current_user['id']);
      $stmt->execute();
      return true;
    }
    return false;
  }

  protected function change_member_password(){
    $mysqli = get_mysqli_or_die();
    $post = $this->app->request->post();
    if(isset($post['new_password']) and $post['new_password'] == $post['confirm_password'] and strlen($post['new_password']) === 0){
      return false;
    }
    
    $stmtx = $mysqli->prepare('UPDATE users SET pwhash = ? WHERE id = ? LIMIT 1');
    
    if($stmt = $mysqli->prepare('SELECT pwhash FROM users WHERE id = ? LIMIT 1')){
      $stmt->bind_param('i', $this->current_user['id']);
      $stmt->execute();
      if($res = $stmt->get_result()){
        $userx = $res->fetch_assoc();
        $res->free();
        if($userx and password_verify($post['current_password'], $userx['pwhash'])){
          $stmtx->bind_param('si', password_hash($post['new_password'], PASSWORD_BCRYPT), $this->current_user['id']);
          $stmtx->execute();
          return true;
        }
      }
    }
    return false;
  }

  protected function reset_session_pw($now, $reset_code){
    return '' . $now . '*.*' . $reset_code;
  }



}


