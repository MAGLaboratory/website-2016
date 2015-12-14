<?php
namespace Maglab\Members;
class Test extends \Maglab\Controller {
  public function init(){
    $this->app->get('/members/test', [$this, 'test']);
  }
  
  public function test(){
    $this->render('email/mass/desperate_price_increase.php', '');
  }
  
}
