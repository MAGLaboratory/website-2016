<?php
namespace Maglab\Members;
class Payments extends \Maglab\Controller {
  public static $client = null;

  public static function client(){
    if(self::$api){
      return self::$api;
    } else {
      self::$api = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(PAYPAL_CLIENT_ID, PAYPAL_SECRET)
      );
      return self::$api;
    }
  }

  public function init(){
    $this->app->get('/members/payments', [$this, 'index']);
  }
  
  function index(){
    
  }
  
}
