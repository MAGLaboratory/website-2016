<?php
  namespace Maglab\Members;
  class Users extends \Maglab\Controller {
    public function init(){
      $admin_mw = [$this, 'require_admin'];
      $this->app->get('/members/users', $admin_mw, [$this, 'index']);
      $this->app->post('/members/users', $admin_mw, [$this, 'create']);
      $this->app->get('/members/users/:id', $admin_mw, [$this, 'show']);
      $this->app->put('/members/users/:id', $admin_mw, [$this, 'update']);
      $this->app->delete('/members/users/:id', $admin_mw, [$this, 'destroy']);
    }

    public function index(){
      $members = $this->get_members();
      $this->render('members/users_index.php', 'Members List', array('members' => $members));
    }
    
    public function create(){}
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
  }
