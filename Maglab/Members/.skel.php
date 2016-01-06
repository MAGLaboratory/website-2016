<?php
namespace Maglab\Members;
class CONTROLLER extends \Maglab\Controller {
  public function init(){
    $this->app->get('/', [$this, 'index']);
  }
  
  function index(){
    
  }
  
}
