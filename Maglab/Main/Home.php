<?php
namespace Maglab\Main;
class Home extends \Maglab\Controller {
  public function init(){
    $this->app->get('/', [$this, 'homepage']);
    $this->app->get('/donate', [$this, 'donate']);
    $this->app->get('/subscribe', [$this, 'subscribe']);
    $this->app->get('/membership', [$this, 'membership']);
    $this->app->get('/membership/pay4keyed', [$this, 'pay4keyed']);
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
  
  function pay4keyed(){
    $this->render('main/pay4keyed.php', 'MAG Laboratory - Pay for Keyed Membership');
  }
  
}
