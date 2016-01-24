<?php

require_once 'config.php';

if(ADMIN_ENFORCE_SSL){
  if(!isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) || $_SERVER["HTTP_X_FORWARDED_PROTO"] != 'https'){
    header("Status: 301 Moved Permanently");
    header(sprintf('Location: https://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']));
    die();
  }
}


require_once 'vendor/autoload.php';

require_once 'helpers.php';
require_once 'members_helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
  'cookies.secure' => ADMIN_ENFORCE_SSL,
  'cookies.encrypt' => true,
  'cookies.lifetime' => '4 hours',
  'cookies.httponly' => true,
  'cookies.secret_key' => COOKIE_SECRET,
  'cookies.path' => '/',
  #'cookies.cipher' => MCRYPT_RIJNDAEL_256,
  #'cookies.cipher_mode' => MCRYPT_MODE_CBC,
));

/*
$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '8 hours',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'mag_session',
    'secret' => COOKIE_SECRET,
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));
*/


if(!defined('SLIM_NO_RUN')){
  require_once('Maglab/base.php');
  require_once('Maglab/Members/Login.php');
  require_once('Maglab/Members/Keyholders.php');
  require_once('Maglab/Members/Users.php');
  require_once('Maglab/Members/Procurement.php');
  require_once('Maglab/Members/Emailer.php');
  require_once('Maglab/Members/Payments.php');
  require_once('Maglab/Members/Memberships.php');

  $login_routes = new \MagLab\Members\Login($app);
  $keyholder_routes = new \Maglab\Members\Keyholders($app);
  $member_routes = new \Maglab\Members\Users($app);
  $procurement_routes = new \Maglab\Members\Procurement($app);
  $emailer_routes = new \Maglab\Members\Emailer($app);
  $paypal_routes = new \Maglab\Members\Payments($app);
  $membership_routes = new \Maglab\Members\Memberships($app);

  if(defined('TESTABLE')){
    require_once('Maglab/Members/Test.php');
    $test_routes = new \Maglab\Members\Test($app);
  }



  $app->run();
} else {
  # Skip the controllers and run() since we're not using the slim router
  # Currently used as part of a hack for wiki user creation
}
