<?php

require_once 'config.php';

if(ADMIN_ENFORCE_SSL){
  if(!isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) || $_SERVER["HTTP_X_FORWARDED_PROTO"] != 'https'){
    header("Status: 301 Moved Permanently");
    header(sprintf('Location: https://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']));
    die();
  }
}

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once 'helpers.php';
require_once 'members_helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
  'cookies.secure' => ADMIN_ENFORCE_SSL,
  'cookies.encrypt' => true,
  'cookies.lifetime' => '8 hours',
  'cookies.httponly' => true,
  'cookies.secret_key' => COOKIE_SECRET,
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

  $login_routes = new \MagLab\Members\Login($app);
  $keyholder_routes = new \Maglab\Members\Keyholders($app);
  $member_routes = new \Maglab\Members\Users($app);
  $procurement_routes = new \Maglab\Members\Procurement($app);

  $app->run();
} else {
  
}
