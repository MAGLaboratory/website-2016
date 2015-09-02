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
  $json = "[[ 'Front Door', '', new Date(0,0,0,12,0,0), new Date(0,0,0,13,30,0) ]]";
  $latest = array('Front Door' => ['Open', time()-1000], 'Back Door' => ['Closed', time()-3049]);
  $app->render('hal/index.php', array('title' => 'HAL',
    'graphJSON' => $json,
    'isOpen' => true,
    'latestStatus' => $latest,
    'currentTime' => time()
    )
  );
});

$app->run();
