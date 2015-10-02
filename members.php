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

$app->get('/members/login', function() use ($app){
  $app->render('members/login.php', array('title' => 'Login'));
});

$app->post('/members/login', function() use ($app){
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
        $session = member_login($user['id']);
        $app->setCookie('auth', $session);
        $app->redirect('/members');
        die();
      }
    }
  }
  $app->render('members/login.php', array('title' => 'Login'));
});

$app->get('/members/logout', function() use ($app){
  $app->deleteCookie('auth');
  $app->deleteCookie('mag_session');
  $app->redirect('/members');
  # This does a double redirect, but ensures we're really logged out
});

$app->get('/members/?', function() use ($app){
  $current_user = member_authenticate($app);
  $user = (object)get_user_by_id($current_user['id']);
  $app->render('members/index.php', array('title' => 'Members', 'current_user' => $current_user, 'user' => $user));
});

$app->post('/members/me', function() use ($app){
  $current_user = member_authenticate($app);
  $response_data = array('title' => 'Members', 'current_user' => $current_user);
  
  if(isset($app->request->post()['current_password'])){
    $response_data['pw_success'] = change_member_password($app, $current_user);
  } else {
    $response_data['info_success'] = save_member_info($app, $current_user);
  }
  
  $response_data['user'] = get_user_by_id($current_user['id']);
  $app->render('members/index.php', $response_data);
});

$app->post('/members/keyholders', function() use ($app){
  $current_user = admin_authenticate($app);
  add_keyholder($app);
  $app->redirect($app->request->params('back'));
});

$app->get('/members/keyholders', function() use($app){
  $current_user = admin_authenticate($app);
  set_space_invader_keyholder();
  $app->render('members/keyholders.php', array('title' => 'Key Holders', 'current_user' => $current_user, 'keyholders' => get_keyholders()));
});

$app->put('/members/keyholders/:id', function($keyholder_id) use($app){
  $current_user = admin_authenticate($app);
  if($app->request->params('end_now') == '1'){
    end_keyholder($keyholder_id);
  } else {
    # update_keyholder
  }
  $app->redirect('/members/keyholders');
});

$app->get('/members/space_invaders', function() use($app){
  $current_user = admin_authenticate($app);
  set_space_invader_keyholder();
  $invaders = get_space_invaders();
  $app->render('members/space_invaders.php', array('title' => 'Space Invaders', 'current_user' => $current_user, 'invaders' => $invaders));
});









$app->run();
