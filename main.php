<?php

require_once 'config.php';

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once 'helpers.php';
require_once 'main_helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
  'cookies.secure' => ADMIN_ENFORCE_SSL,
  'cookies.encrypt' => true,
  'cookies.lifetime' => '24 hours',
  'cookies.httponly' => true,
  'cookies.secret_key' => COOKIE_SECRET . '_main',
  'cookies.path' => '/',
  #'cookies.cipher' => MCRYPT_RIJNDAEL_256,
  #'cookies.cipher_mode' => MCRYPT_MODE_CBC,
));


require_once('Maglab/base.php');
require_once('Maglab/Main/Home.php');
$home_routes = new \Maglab\Main\Home($app);

$app->run();
