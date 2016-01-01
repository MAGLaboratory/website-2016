<?php
namespace Maglab\Main;
class Home extends \Maglab\Controller {
  public function init(){
    $this->app->get('/', [$this, 'homepage']);
    $this->app->get('/donate', [$this, 'donate']);
    $this->app->get('/subscribe', [$this, 'subscribe']);
    $this->app->get('/membership', [$this, 'membership']);
  }
  
  function homepage(){
    $this->render('main/homepage.php', 'MAG Laboratory - Homepage');
  }

  function donate(){
    $this->render('main/donate.php', 'MAG Laboratory - Donate');
  }
  
  function subscribe(){
    $this->redirect('http://eepurl.com/bJC_aX');
  }
  
  function membership(){
    $this->render('main/membership.php', 'MAG Laboratory - Membership');
  }
  
}
