<?php

namespace Maglab\Members;
class Payments extends \Maglab\Controller {
  public static $client = null;

  public static function client(){
    if(self::$client){
      return self::$client;
    } else {
      $config = array(
        'mode' => PAYPAL_MODE,
        'acct1.UserName' => PAYPAL_CLIENT_ID,
        'acct1.Password' => PAYPAL_SECRET,
        'acct1.Signature' => PAYPAL_SIGNATURE
        );
      self::$client = new \PayPal\Service\PayPalAPIInterfaceServiceService($config);
      return self::$client;
    }
  }
  
  public static function searchByEmail($email){
    $search = new \PayPal\PayPalAPI\TransactionSearchRequestType();
    $search->StartDate = "2015-01-01T00:00:00-0000";
    $search->Email = $email;
    $req = new \PayPal\PayPalAPI\TransactionSearchReq();
    $req->TransactionSearchRequest = $search;
    $response = self::client()->TransactionSearch($req);
    return $response;
  }

  public function init(){
    $user_mw = [$this, 'require_user'];
    $this->app->get('/members/payments', $user_mw, [$this, 'index']);
  }
  
  function index(){
    $response = self::searchByEmail($this->current_user['email']);
    if($response and $response->Ack and strpos($response->Ack, 'Success') > -1){
      $payments = $response->PaymentTransactions;
    } else {
      $payments = [];
    }
    
    $this->response['payments'] = $payments;
    $this->render('members/payments/index.php', 'Payments');
  }
  
}
