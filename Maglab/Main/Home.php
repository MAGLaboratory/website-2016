<?php
namespace Maglab\Main;
class Home extends \Maglab\Controller {
  public function init(){
    $this->app->get('/', [$this, 'homepage']);
    $this->app->get('/donate', [$this, 'donate']);
    $this->app->get('/subscribe', [$this, 'subscribe']);
  }
  
  function homepage(){
    $this->render('main/homepage.php', 'MAGLaboratory - Homepage');
  }

  function donate(){
    $this->render('main/donate.php', 'MAGLaboratory - Donate');
  }
  
  function subscribe(){
    $this->redirect('http://eepurl.com/bJC_aX');
  }
  
}
