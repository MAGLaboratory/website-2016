<?php

function member_authenticate($app){
  $auth = $app->getCookie('auth');
  if($auth){
    if($user = get_user_by_auth($auth)){
      return $user;
    }
  }
  $app->redirect('/members/login');
  die();
}

function admin_authenticate($app){
  $auth = member_authenticate($app);
  if(strpos($auth['role'], 'Admin') > -1){
    return $auth;
  }
  $app->redirect('/members');
  die();
}

function get_user_by_auth($auth){
  $mysqli = get_mysqli_or_die();
  
  if($stmt = $mysqli->prepare('SELECT id, email, role FROM users WHERE current_session = ?')){
    $stmt->bind_param('s', $auth);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $user = $res->fetch_assoc();
      return $user;
    }
  }
  return null;
}

function get_user_by_id($id){
  $mysqli = get_mysqli_or_die();
  
  if($stmt = $mysqli->prepare('SELECT id, role, email, first_name, last_name, main_phone, emergency_phone, interests, UNIX_TIMESTAMP(joined_at) AS joined_at, UNIX_TIMESTAMP(left_at) AS left_at FROM users WHERE id = ?')){
    $stmt->bind_param('i', $id);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $user = $res->fetch_object();
      return $user;
    }
  }
  return null;
}

function member_login($id){
  $session = bin2hex(openssl_random_pseudo_bytes(12));
  $mysqli = get_mysqli_or_die();
  
  if($stmt = $mysqli->prepare('UPDATE users SET current_session = ? WHERE id = ? LIMIT 1')){
    $stmt->bind_param('si', $session, $id);
    $stmt->execute();
  }
  
  return $session;
}

function isAdmin($user){
  if(!$user or !$user['role']){ return false; }
  return (strpos($user['role'], 'Admin') > -1);
}







