<?php
namespace Maglab\Members;
class Login extends \Maglab\Controller {
  public function init(){
    $this->app->get('/members/?', [$this, 'require_user'], [$this, 'index']);
    $this->app->get('/members/login', [$this, 'show']);
    $this->app->post('/members/login', [$this, 'create']);
    $this->app->get('/members/logout', [$this, 'destroy']);
    $this->app->get('/members/forgot_password', [$this, 'forgot_password']);
    $this->app->post('/members/forgot_password', [$this, 'reset_password']);
    $this->app->get('/members/me', [$this, 'require_user'], [$this, 'profile']);
    $this->app->post('/members/me', [$this, 'require_user'], [$this, 'update']);
  }

  public function index(){
    $user = (object)get_user_by_id($this->current_user['id']);
    $this->render('members/index.php', 'Members', array('user' => $user));
  }
  
  public function show(){
    $this->render('members/login.php', 'Login');
  }
  
  public function create(){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare('SELECT id, email, pwhash FROM users WHERE email = LOWER(?) LIMIT 1')){
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
    $this->render('members/login.php', 'Login');
  }
  
  public function destroy(){
    $auth = $this->app->getCookie('auth');
    if(is_string($auth)){ $this->member_logout($auth); }
    $this->app->deleteCookie('auth');
    $this->app->deleteCookie('mag_session');
    $this->app->redirect('/members');
    # This does a double redirect, but ensures we're really logged out
  }
  
  public function reset_password(){
    $this->reset_password_for($this->app->request->post('email'));
  }
  
  public function forgot_password(){
    $this->render('members/forgot.php', 'Forgot Password');
  }
  
  public function profile(){
    $this->render('members/show.php', 'Profile', array());
  }
  
  public function update(){
    $response_data = array();
    
    if(isset($this->app->request->post()['current_password'])){
      $response_data['pw_success'] = $this->change_member_password();
    } else {
      $response_data['info_success'] = $this->save_member_info();
    }
    
    $response_data['user'] = get_user_by_id($this->current_user['id']);
    $this->render('members/show.php', 'Profile', $response_data);
  }
  
  protected function reset_password_for($email){
  
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




}


