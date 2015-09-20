<?php

require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

require_once 'config.php';
require_once 'helpers.php';
require_once 'haldor_helpers.php';

$app = new \Slim\Slim(array(
  'mode' => $slim_mode,
  'templates.path' => HALDOR_ROOT . '/templates',
));

$app->get('/haldor/test', function() use ($app) {
  //var_dump(update_switch('Open_Switch', 'qFVtIGJhTep_aW3LSDMnnhRmrDhekP83wyVBLKQm', '0'));
});

$app->post('/haldor/test', function() use ($app) {
  'ok';
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

$app->post('/halley/bootup', function() use ($app) {
  authenticate($app);
  $session = generate_session();
  save_payload($app);
  echo $session;
});

$app->post('/halley/output', function() use ($app) {
  authenticate($app);
  save_payload($app);
  parse_halley_output($app);
  echo 'OK';
});

$app->get('/hal/?', function() use ($app) {
  require_once 'hal_helpers.php';
  mark_old_switches();
  
  $latest = latest_changes();
  $app->render('hal/index.php', array('title' => 'HAL',
    'isOpen' => is_maglabs_open($latest),
    'latestStatus' => $latest,
    'currentTime' => time()
    )
  );
});

$app->get('/hal/chart', function() use ($app) {
  require_once 'hal_helpers.php';
  
  $json = timeline_graph_json(time()-604800, time());
  $app->render('hal/chart.php', array('title' => 'HAL Charts',
    'graphJSON' => $json
    )
  );
});

$app->get('/hal/test', function() use ($app) {
  require_once 'hal_helpers.php';
  
  echo timeline_graph_json(time()-604800, time());
});

$app->run();
