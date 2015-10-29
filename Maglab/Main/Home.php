<?php
namespace Maglab\Main;
class Home extends \Maglab\Controller {
  public function init(){
    $this->app->get('/', [$this, 'homepage']);
    
  }
  
  function homepage(){
    $this->render('main/homepage.php', 'MAGLaboratory - Homepage');
  }

  
}
