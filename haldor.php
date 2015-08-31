<?php

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once 'config.php';
require_once 'helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
));

$app->get('/haldor/test', function() use ($app) {
  authenticate($app);
});

$app->post('/haldor/test', function() use ($app) {
  authenticate($app);
  
});

$app->post('/haldor/bootup', function() use ($app) {
  authenticate($app);
  $session = generate_session();
  set_boot_switch($app, $session);
  echo $session;
});

$app->post('/haldor/checkup', function() use ($app) {
  authenticate($app);
  save_payload($app);
  save_switches($app);
  echo 'OK';
});

$app->get('/hal/?', function() use ($app) {
  
  $app->render('hal.php', array());
});

$app->run();
