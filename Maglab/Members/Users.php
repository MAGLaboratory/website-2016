<?php
namespace Maglab\Members;
class Users extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/users', $admin_mw, [$this, 'index']);
    $this->app->post('/members/users', $admin_mw, [$this, 'invite']);
    $this->app->get('/members/users/:id', $admin_mw, [$this, 'show']);
    $this->app->put('/members/users/:id', $admin_mw, [$this, 'update']);
    $this->app->delete('/members/users/:id', $admin_mw, [$this, 'destroy']);
  }

  public function index(){
    $this->respond['members'] = $this->get_members();
    $this->render('members/users_index.php', 'Members List');
  }
  
  public function invite(){
    $mysqli = get_mysqli_or_die();
    $post = $this->app->request->post();
    if(isset($post['joined_at']) and strtotime($post['joined_at']) > 0){
      $joined_at = strtotime($post['joined_at']);
    } else {
      $joined_at = time();
    }
    
    if($post['roles'] and count($post['roles']) > 0){
      $roles = $this->role_filter( (array)$post['roles'] );
    }
    
    if(count($roles) == 0){ $roles = array('Guest'); }
    array_push($roles, 'Invite');
    $roles_str = implode(",", $roles);
    
    $now = time();
    $code = random_b64();
    $session = '' . $now . $code;
    
    if($stmt = $mysqli->prepare("INSERT INTO users (pwhash, email, role, first_name, last_name, current_session, joined_at) VALUES ('*', ?, ?, ?, ?, ?, FROM_UNIXTIME(?))")){
      $stmt->bind_param('sssssi', $post['email'], $roles_str, $post['first_name'], $post['last_name'], $session, $joined_at);
      $stmt->execute();
      $uid = $stmt->insert_id;
      if($uid > 0){
        $this->email_invite($post, $now, $code, $session);
        $this->respond['successful_invite'] = $post['email'];
      }
    }
    $this->index();
  }
  
  
  public function show(){}
  public function update(){}
  public function destroy(){}
  
  protected function get_members(){
    $mysqli = get_mysqli_or_die();
    
    if($res = $mysqli->query('SELECT id, role, first_name, last_name, main_phone, emergency_phone, email, UNIX_TIMESTAMP(joined_at) AS joined_at, UNIX_TIMESTAMP(left_at) AS left_at FROM users ORDER BY id DESC', MYSQLI_USE_RESULT)){
      return $res->fetch_all(MYSQLI_ASSOC);
    }
    return array();
  }
  
  protected function role_filter($roles){
    if(!$this->current_user or !$this->current_user['role']){ return false; }
    if(strpos($this->current_user['role'], 'Admin')){
      # Admins can create all roles
      return $roles;
    } else {
      # Other members can only add Guests
      return array_intersect(array('Guest'), $roles);
    }
    
    return !(strpos($user['role'], 'Reset') > -1 or strpos($user['role'], 'Verify') > -1 or strpos($user['role'], 'Disabled') > -1 or strpos($user['role'], 'Invite') > -1);
  }
  
  protected function email_invite(){
    
  }
}
