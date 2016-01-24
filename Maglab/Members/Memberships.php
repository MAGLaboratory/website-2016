<?php
namespace Maglab\Members;
class Memberships extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/memberships', $admin_mw, [$this, 'index']);
  }
  
  function index(){
    
  }
  
}
