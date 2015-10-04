<?php
namespace Maglab\Members;

class Keyholders extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->post('/members/keyholders', $admin_mw, [$this, 'add_keyholder']);
    $this->app->get('/members/keyholders', $admin_mw, [$this, 'list_keyholders']);
    $this->app->put('/members/keyholders/:id', $admin_mw, [$this, 'update_keyholder']);
    $this->app->get('/members/space_invaders', $admin_mw, [$this, 'space_invaders']);
  }
  
  public function add_keyholder(){
    $this->insert_keyholder();
    $this->app->redirect($this->app->request->params('back'));
  }
  
  public function list_keyholders(){
    $this->set_space_invader_keyholder();
    $this->render('members/keyholders.php', 'Key Holders', array('keyholders' => $this->get_keyholders()));
  }
  
  public function update_keyholder($keyholder_id){
    if($app->request->params('end_now') == '1'){
      $this->end_keyholder($keyholder_id);
    } else {
      # TODO: update_keyholder
    }
    $this->app->redirect('/members/keyholders');
  }
  
  public function space_invaders(){
    $this->set_space_invader_keyholder();
    $invaders = $this->get_space_invaders();
    $this->render('members/space_invaders.php', 'Space Invaders', array('invaders' => $invaders));
  }
  
  protected function set_space_invader_keyholder(){
    $mysqli = get_mysqli_or_die();
    
    $stmtx = $mysqli->prepare('UPDATE space_invaders SET keyholder_id = ? WHERE id = ? LIMIT 1');
    
    if($stmt = $mysqli->prepare('SELECT id, keycode, UNIX_TIMESTAMP(open_at) AS open_at, UNIX_TIMESTAMP(denied_at) AS denied_at, UNIX_TIMESTAMP(created_at) AS created_at FROM space_invaders WHERE keyholder_id IS NULL')){
      $stmt->execute();
      if($res = $stmt->get_result()){
        while($row = $res->fetch_assoc()){
          ($access_at = $row['open_at']) or ($access_at = $row['denied_at']) or ($access_at = $row['created_at']);
          $keyholder = $this->find_keyholder($row['keycode'], $access_at);
          $stmtx->bind_param('ii', $keyholder, $row['id']);
          $stmtx->execute();
        }
      }
    }
  }
  
  protected function insert_keyholder(){
    $mysqli = get_mysqli_or_die();
    $post = $this->app->request->post();
    if(isset($post['start_at']) and strtotime($post['start_at']) > 0){
      $start_at = strtotime($post['start_at']);
    } else {
      $start_at = time();
    }
    
    if(isset($post['end_at']) and strtotime($post['end_at']) > 0){
      $end_at = strtotime($post['end_at']);
    } else {
      $end_at = null;
    }
    
    if($stmt = $mysqli->prepare('INSERT INTO keyholders (keycode, person, start_at, end_at) VALUES (?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))')){
      $stmt->bind_param('ssii', $post['keycode'], $post['person'], $start_at, $end_at);
      $stmt->execute();
    }
  }

  protected function get_keyholders(){
    $mysqli = get_mysqli_or_die();
    
    if($res = $mysqli->query('SELECT id, keycode, person, UNIX_TIMESTAMP(start_at) AS start_at, UNIX_TIMESTAMP(end_at) AS end_at FROM keyholders ORDER BY id DESC', MYSQLI_USE_RESULT)){
      return $res->fetch_all(MYSQLI_ASSOC);
    }
    return array();
  }

  protected function end_keyholder($keyholder_id){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare('UPDATE keyholders SET end_at = NOW() WHERE id = ? AND end_at IS NULL LIMIT 1')){
      $stmt->bind_param('i', $keyholder_id);
      $stmt->execute();
      return true;
    }
    return false;
  }

  protected function get_space_invaders(){
    $mysqli = get_mysqli_or_die();
    
    if($res = $mysqli->query('SELECT space_invaders.id AS id, space_invaders.keyholder_id, space_invaders.keycode AS keycode, UNIX_TIMESTAMP(open_at) AS open_at, UNIX_TIMESTAMP(denied_at) AS denied_at, UNIX_TIMESTAMP(space_invaders.created_at) AS created_at, keyholders.person AS person, keyholderx.person AS current_person FROM space_invaders LEFT JOIN keyholders ON keyholders.id = space_invaders.keyholder_id LEFT JOIN keyholders AS keyholderx ON keyholderx.keycode = space_invaders.keycode AND keyholderx.end_at IS NULL ORDER BY id DESC', MYSQLI_USE_RESULT)){
      return $res->fetch_all(MYSQLI_ASSOC);
    }
    return array();
  }



  protected function find_keyholder($keycode, $access_at){
    $mysqli = get_mysqli_or_die();
    
    $keyholder_id = 0;
    if($stmt = $mysqli->prepare('SELECT id FROM keyholders WHERE keycode = ? AND ((start_at < FROM_UNIXTIME(?) AND end_at > FROM_UNIXTIME(?)) OR (start_at < FROM_UNIXTIME(?) AND end_at IS NULL)) LIMIT 1')){
      $stmt->bind_param('siii', $keycode, $access_at, $access_at, $access_at);
      $stmt->execute();
      if($res = $stmt->get_result()){
        $ks = $res->fetch_array(MYSQLI_NUM);
        if($ks and $ks[0]){ $keyholder_id = $ks[0]; }
      }
    }
    return $keyholder_id;
  }
  
  
  
}
