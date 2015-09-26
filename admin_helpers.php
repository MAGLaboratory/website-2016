<?php

function admin_authenticate($app){
  $auth = $app->getCookie('auth');
  if($auth){
    if($user = get_user_by_auth($auth)){
      return $user;
    }
  }
  $app->redirect('/admin/login');
  die();
}

function get_user_by_auth($auth){
  $mysqli = get_mysqli();
  if(!$mysqli){ die('unable to connect to database'); }
  
  if($stmt = $mysqli->prepare('SELECT id, email FROM users WHERE current_session = ?')){
    $stmt->bind_param('s', $auth);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $user = $res->fetch_assoc();
      return $user;
    }
  }
  return null;
}

function admin_login($id){
  $session = bin2hex(openssl_random_pseudo_bytes(12));
  $mysqli = get_mysqli();
  if(!$mysqli){ die('unable to connect to database'); }
  
  if($stmt = $mysqli->prepare('UPDATE users SET current_session = ? WHERE id = ? LIMIT 1')){
    $stmt->bind_param('si', $session, $id);
    $stmt->execute();
  }
  
  return $session;
}
