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
require_once 'admin_helpers.php';

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

$app->get('/admin/login', function() use ($app){
  $app->render('admin/login.php', array('title' => 'Login'));
});

$app->post('/admin/login', function() use ($app){
  $mysqli = get_mysqli();
  if(!$mysqli){ die('unable to connect to database'); }
  
  if($stmt = $mysqli->prepare('SELECT id, email, pwhash FROM users WHERE email = LOWER(?) LIMIT 1')){
    $post = $app->request->post();
    $stmt->bind_param('s', $post['email']);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $user = $res->fetch_assoc();
      $res->free();
      if($user and password_verify($post['password'], $user['pwhash'])){
        $session = admin_login($user['id']);
        $app->setCookie('auth', $session);
        $app->redirect('/admin/keyholders');
        die();
      }
    }
  }
  $app->render('admin/login.php', array('title' => 'Login'));
});

$app->get('/admin/?', function() use ($app){
  admin_authenticate($app);
  $app->redirect('/admin/keyholders');
});

$app->post('/admin/keyholders', function() use ($app){
  $user = admin_authenticate($app);
    
});

$app->get('/admin/keyholders', function() use($app){
  $user = admin_authenticate($app);
  
});




$app->run();
