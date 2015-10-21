<?php
namespace Maglab\Members;
class Procurement extends \Maglab\Controller {
  public function init(){
    $this->app->get('/members/procurement', [$this, 'index']);
    $this->app->post('/members/procurement', [$this, 'create']);
    $this->app->patch('/members/procurement/:id', [$this, 'procuring']);
    #$this->app->put('/members/procurement', [$this, 'update']);
    $this->app->delete('/members/procurement/:id', [$this, 'archive']);
  }
  
  function index(){
    $this->respond['items'] = $this->getItems();
    $this->render('members/procurement/index.php', 'Procurement');
  }
  
  function create(){
    $post = $this->app->request->post();
    $this->respond['createdItem'] = $this->createItem($post);
    $this->respond['items'] = $this->getItems();
    $this->render('members/procurement/index.php', 'Procurement');
  }
  
  function procuring($id){
    if($this->params('got')){
      $query = 'UPDATE procurement SET have_amount = have_amount + 1 WHERE id = ? LIMIT 1';
    } elseif($this->params('lost')){
      $query = 'UPDATE procurement SET have_amount = have_amount - 1 WHERE id = ? LIMIT 1';
    } elseif($this->params('need')){
      $query = 'UPDATE procurement SET need_amount = need_amount + 1 WHERE id = ? LIMIT 1';
    } elseif($this->params('skip')){
      $query = 'UPDATE procurement SET need_amount = need_amount - 1 WHERE id = ? LIMIT 1';
    }
    
    $mysqli = get_mysqli_or_die();
    
    if(isset($query) and $stmt = $mysqli->prepare($query)){
      $stmt->bind_param('i', $id);
      $stmt->execute();
    }
    
    if($stmt1 = $mysqli->prepare('SELECT id, category, name, description, need_amount, have_amount, cost FROM procurement WHERE id = ? LIMIT 1')){
      $stmt1->bind_param('i', $id);
      $stmt1->execute();
      $result = $stmt1->get_result();
      $this->respond['item'] = $result->fetch_assoc();
    }

    $this->render('members/procurement/item.php', '');    
  }
  
  #function update(){}
  
  function archive($id){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare("UPDATE procurement SET archived_at = NOW() WHERE archived_at IS NULL AND id = ?")){
      $stmt->bind_param('i', $id);
      $stmt->execute();
    }
    $this->render('/members/procurement/item.php', '');
  }
  
  protected function getItems(){
    $mysqli = get_mysqli_or_die();
    
    if($res = $mysqli->query('SELECT id, category, name, description, need_amount, have_amount, cost FROM procurement WHERE archived_at IS NULL ORDER BY id DESC', MYSQLI_USE_RESULT)){
      return $res->fetch_all(MYSQLI_ASSOC);
    }
    return array();
  }
  
  protected function createItem($post){
    $mysqli = get_mysqli_or_die();
    
    if($stmt = $mysqli->prepare("INSERT INTO procurement (category, name, need_amount, have_amount, cost, description, history, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())")){
      $history = (json_encode(array_merge(array('action' => 'create'), $post)) . "\n\t\n");
      $stmt->bind_param('ssiisss', $post['category'], $post['name'], $post['need_amount'], $post['have_amount'], $post['cost'], $post['description'], $history);
      $stmt->execute();
      return $stmt->affected_rows == 1;
    }
    
    return false;
  }
}
