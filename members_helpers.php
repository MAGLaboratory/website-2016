<?php

function member_authenticate($app){
  $auth = $app->getCookie('auth');

  if($user = member_authenticate_by_auth($auth)){
    return $user;
  } else {
    $app->redirect('/members/login');
    die();
  }
}

function member_authenticate_by_auth($auth){
  if($auth){
    if($user = get_user_by_auth($auth)){
      if(canLogin($user)){
        return $user;
      }
    }
  }
  return null;
}

function admin_authenticate($app){
  $user = member_authenticate($app);
  if(strpos($user['role'], 'Admin') > -1){
    return $user;
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
  
  if($stmt = $mysqli->prepare('SELECT id, role, email, first_name, last_name, main_phone, emergency_phone, interests, wikiusername, UNIX_TIMESTAMP(joined_at) AS joined_at, UNIX_TIMESTAMP(left_at) AS left_at FROM users WHERE id = ?')){
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

# User can only login if they don't have these marked in their roles
# Reset - a password reset was requested. The reset process must go through.
# Verify - email needs to be verified (email was recently changed)
# Disabled - User was disabled by an admin
# Invite - user was just invited, account not set up yet
function canLogin($user){
  if(!$user or !$user['role']){ return false; }
  return !(strpos($user['role'], 'Reset') > -1 or strpos($user['role'], 'Verify') > -1 or strpos($user['role'], 'Disabled') > -1 or strpos($user['role'], 'Invite') > -1);
}





