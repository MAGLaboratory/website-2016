<?php

require 'config.php';
require 'vendor/autoload.php';
require 'helpers.php';
require 'Maglab/base.php';


$search = new \PayPal\PayPalAPI\TransactionSearchRequestType();
$time = time() - 2678400; # 31 days ago
if($time < 1451606400){ $time = 1451606400; }
$start_date = date('Y-m-d', $time);
$search->StartDate = $start_date . "T00:00:00-0000";
$search->TransactionClass = "Received";
$req = new \PayPal\PayPalAPI\TransactionSearchReq();
$req->TransactionSearchRequest = $search;

$config = array(
  'mode' => PAYPAL_MODE,
  'acct1.UserName' => PAYPAL_CLIENT_ID,
  'acct1.Password' => PAYPAL_SECRET,
  'acct1.Signature' => PAYPAL_SIGNATURE
  );
$client = new \PayPal\Service\PayPalAPIInterfaceServiceService($config);
$response = $client->TransactionSearch($req);

$base = new \Maglab\Controller();

function hasTransaction($base, $transid){
  if($stmt = $base->mysqli_prepare("SELECT id FROM membership_payments WHERE transaction_id = ? LIMIT 1")){
    $stmt->bind_param('s', $transid);
    $resx = $base->mysqli_result($stmt);
    return $resx;
  }
  return false;
}

foreach($response->PaymentTransactions as $transaction){
  if($transaction->Status == "Completed"){
    $amount = str_replace('.', '', $transaction->GrossAmount->value);
    $transid = $transaction->TransactionID;
    
    if(hasTransaction($base, $transid)){
      continue;
    }
    $paid_on = strtotime($transaction->Timestamp);
    $email = $transaction->Payer;
    $user_id = null;
    if($user = $base->get_user_info_by_email($email)){
      $user_id = $user->id;
    }
    
    if($stmt = $this->mysqli_prepare("INSERT INTO membership_payments (user_id, amount, paid_on, transaction_id, email) VALUES (?, ?, FROM_UNIXTIME(?), ?, ?)")){
      $stmt->bind_param('iiiss', $user_id, $amount, $paid_on, $transid, $email);
      $stmt->execute();
      $stmt->close();
    }
  }
}
