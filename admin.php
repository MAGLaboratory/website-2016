<?php

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once 'config.php';
require_once 'helpers.php';
require_once 'admin_helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
));

$app->get('/admin/login', function() use ($app){

});

$app->get('/admin', function() use ($app){

});

$app->post('/admin/keyholders', function() use ($app){

});






$app->run();
