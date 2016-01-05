<?php
namespace Maglab\Members;
class Users extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $user_mw = [$this, 'require_user'];
    $this->app->get('/members/users', $admin_mw, [$this, 'index']);
    $this->app->post('/members/users', $user_mw, [$this, 'invite']);
    $this->app->get('/members/users/:id', $admin_mw, [$this, 'show']);
    $this->app->put('/members/users/:id', $admin_mw, [$this, 'update']);
    $this->app->delete('/members/users/:id', $admin_mw, [$this, 'destroy']);
    $this->app->get('/members/invite/resend', $user_mw, [$this, 'resend_invite']);
    $this->app->get('/members/invite', [$this, 'invite_accept']);
    $this->app->post('/members/invite', [$this, 'setup_account']);
  }

  public function index(){
    $this->respond['members'] = $this->get_members();
    $this->render('members/users/users_index.php', 'Members List');
  }
  
  public function invite(){
    $mysqli = get_mysqli_or_die();
    $post = $this->app->request->post();
    
    $this->respond['insert_id'] = null;
    if(isset($post['joined_at']) and strtotime($post['joined_at']) > 0){
      $joined_at = strtotime($post['joined_at']);
    } else {
      $joined_at = time();
    }
    
    
    if(isset($post['roles']) and count($post['roles']) > 0){
      $roles = $this->role_filter( (array)$post['roles'] );
    } else {
      $roles = array('Guest');
    }
    
    if(count($roles) == 0){ $roles = array('Guest'); }
    array_push($roles, 'Invite');
    $roles_str = implode(",", $roles);
    
    $now = time();
    $code = random_b64();
    $session = $this->invite_password($now, $code);
    
    if($stmt = $mysqli->prepare("INSERT INTO users (pwhash, email, role, first_name, last_name, current_session, joined_at, created_at) VALUES ('*', ?, ?, ?, ?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))")){
      $stmt->bind_param('sssssii', $post['email'], $roles_str, $post['first_name'], $post['last_name'], $session, $joined_at, $now);
      $stmt->execute();
      $uid = $stmt->insert_id;
      $this->respond['insert_id'] = $uid;
      if($uid > 0){
        $inviter = (object)get_user_by_id($this->current_user['id']);
        $invite_url = "https://www.maglaboratory.org/members/invite?now=${now}&code=${code}";
        $data = array('inviter' => $inviter, 'invite_url' => $invite_url);
        $this->email_invite($data, $post['email']);
        $this->respond['successful_invite'] = $post['email'];
      }
    }
    $this->index();
  }
  
  function resend_invite(){
    $inviter = (object)get_user_by_id($this->current_user['id']);
    $invited = $this->get_user_info($this->params('id'));

    if(!$invited or !isset($invited->role) or strpos($invited->role, 'Invite') === false or strpos($invited->current_session, '%%%') === false){
      $this->redirect('/members/invite');
    } else {
      self::email_invite($inviter, $invited, $this);
      
      $this->respond['successful_invite'] = $invited->email;
      $this->index();
    }
  }
  
  
  public function show(){}
  public function update(){}
  public function destroy(){}
  
  public function invite_accept(){
    $now = $this->app->request->params('now');
    $code = $this->app->request->params('code');
    
    $this->respond['invite_user'] = null;

    if($now and $code){
      $user = get_user_by_auth($this->invite_password($now, $code));
      if($user and $user['role'] and strpos($user['role'], 'Invite') > -1){
        $this->respond['invite_user'] = $user;
      }
    }
    
    $this->respond['now'] = $now;
    $this->respond['code'] = $code;
    $this->render('members/users/invite_accept.php', 'Reset Password');
  }
  
  public function setup_account(){
    $mysqli = get_mysqli_or_die();
    $now = $this->app->request->params('now');
    $code = $this->app->request->params('code');
    $invite_password = $this->invite_password($now, $code);
    
    if($now and $code){
      $user = get_user_by_auth($invite_password);
      
      $password = $this->hash_password($this->app->request->post('new_password'));

      if($user and $user['role'] and strpos($user['role'], 'Invite') > -1){
        if($stmt = $mysqli->prepare("UPDATE users SET pwhash = ?, current_session = NULL, role = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', role, ','), CONCAT(',', 'Invite', ','), ',')) WHERE FIND_IN_SET('Invite', role) > 0 AND current_session = ? AND id = ? LIMIT 1")){
          $stmt->bind_param('ssi', $password, $invite_password, $user['id']);
          $stmt->execute();
          $this->respond['affected_rows'] = $stmt->affected_rows;
          if($this->respond['affected_rows'] > 0){
            $this->render('members/users/invite_accept.php', 'Account Setup Complete', array('user_setup_complete' => $user));
            return;
          }
        }
        
      }
    }
    
    $this->redirect('/members');
  }
  
  protected function get_members(){
    $mysqli = get_mysqli_or_die();
    
    if($res = $mysqli->query('SELECT id, role, first_name, last_name, main_phone, emergency_phone, email, UNIX_TIMESTAMP(joined_at) AS joined_at, UNIX_TIMESTAMP(left_at) AS left_at FROM users ORDER BY id DESC', MYSQLI_USE_RESULT)){
      return $res->fetch_all(MYSQLI_ASSOC);
    }
    return array();
  }
  
  public function role_filter($roles){
    if(!$this->current_user or !$this->current_user['role']){ return false; }
    if(strpos($this->current_user['role'], 'Admin') > -1){
      # Admins can create all roles
      return $roles;
    } else {
      # Other members can only add Guests
      return array_intersect(array('Guest'), $roles);
    }
    
    return !(strpos($user['role'], 'Reset') > -1 or strpos($user['role'], 'Verify') > -1 or strpos($user['role'], 'Disabled') > -1 or strpos($user['role'], 'Invite') > -1);
  }
  
  static function email_invite($inviter, $invited, $controller){
    $sessionx = explode("%%%", $invited->current_session);
    $now = $sessionx[0];
    $code = $sessionx[1];
    $invite_url = "https://www.maglaboratory.org/members/invite?now=${now}&code=${code}";
    $data = array('inviter' => $inviter, 'invite_url' => $invite_url);
    $body = $controller->render_to_string('email/users/invite.php', $data);
    $controller->email_html($invited->email, 'Invitation to join MAGLaboratory', $body);
  }
  
  protected function invite_password($now, $code){
    return '' . $now . '%%%' . $code;
  }
}
