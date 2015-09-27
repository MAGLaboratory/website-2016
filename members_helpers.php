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

function find_keyholder($keycode, $access_at){
  $mysqli = get_mysqli_or_die();
  
  var_dump([$keycode, $access_at]);
  
  $keyholder_id = 0;
  if($stmt = $mysqli->prepare('SELECT id FROM keyholders WHERE keycode = ? AND ((start_at < FROM_UNIXTIME(?) AND end_at > FROM_UNIXTIME(?)) OR (start_at < FROM_UNIXTIME(?) AND end_at IS NULL)) LIMIT 1')){
    $stmt->bind_param('siii', $keycode, $access_at, $access_at, $access_at);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $ks = $res->fetch_array(MYSQLI_NUM);
      if($ks and $ks[0]){
        $keyholder_id = $ks[0];
      }
    }
  }
  return $keyholder_id;
}

function set_space_invader_keyholder(){
  $mysqli = get_mysqli_or_die();
  
  $stmtx = $mysqli->prepare('UPDATE space_invaders SET keyholder_id = ? WHERE id = ? LIMIT 1');
  
  if($stmt = $mysqli->prepare('SELECT id, keycode, UNIX_TIMESTAMP(open_at) AS open_at, UNIX_TIMESTAMP(denied_at) AS denied_at, UNIX_TIMESTAMP(created_at) AS created_at FROM space_invaders WHERE keyholder_id IS NULL')){
    $stmt->execute();
    if($res = $stmt->get_result()){
      while($row = $res->fetch_assoc()){
        ($access_at = $row['open_at']) or ($access_at = $row['denied_at']) or ($access_at = $row['created_at']);
        $keyholder = find_keyholder($row['keycode'], $access_at);
        $stmtx->bind_param('ii', $keyholder, $row['id']);
        $stmtx->execute();
      }
    }
  }
}

function get_keyholders(){
  $mysqli = get_mysqli_or_die();
  
  if($res = $mysqli->query('SELECT id, keycode, person, UNIX_TIMESTAMP(start_at) AS start_at, UNIX_TIMESTAMP(end_at) AS end_at FROM keyholders ORDER BY id DESC', MYSQLI_USE_RESULT)){
    return $res->fetch_all(MYSQLI_ASSOC);
  }
  return array();
}

function isAdmin($user){
  if(!$user or !$user['role']){ return false; }
  return (strpos($user['role'], 'Admin') > -1);
}

function change_member_password($app, $user){
  $mysqli = get_mysqli_or_die();
  $post = $app->request->post();
  if(isset($post['new_password']) and $post['new_password'] == $post['confirm_password'] and strlen($post['new_password']) === 0){
    return false;
  }
  
  $stmtx = $mysqli->prepare('UPDATE users SET pwhash = ? WHERE id = ? LIMIT 1');
  
  if($stmt = $mysqli->prepare('SELECT pwhash FROM users WHERE id = ? LIMIT 1')){
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    if($res = $stmt->get_result()){
      $userx = $res->fetch_assoc();
      $res->free();
      if($userx and password_verify($post['current_password'], $userx['pwhash'])){
        $stmtx->bind_param('si', password_hash($post['new_password'], PASSWORD_BCRYPT), $user['id']);
        $stmtx->execute();
        return true;
      }
    }
  }
  return false;
}

function save_member_info($app, $user){
  $mysqli = get_mysqli_or_die();
  $post = $app->request->post();
  
  if($stmt = $mysqli->prepare('UPDATE users SET email = ?, first_name = ?, last_name = ?, main_phone = ?, emergency_phone = ? WHERE id = ?')){
    $stmt->bind_param('sssssi', $post['email'], $post['first_name'], $post['last_name'], $post['main_phone'], $post['emergency_phone'], $user['id']);
    $stmt->execute();
    return true;
  }
  return false;
}

function get_space_invaders(){
  $mysqli = get_mysqli_or_die();
  
  if($res = $mysqli->query('SELECT space_invaders.id AS id, space_invaders.keyholder_id, space_invaders.keycode AS keycode, UNIX_TIMESTAMP(open_at) AS open_at, UNIX_TIMESTAMP(denied_at) AS denied_at, keyholders.person AS person FROM space_invaders LEFT JOIN keyholders ON keyholders.id = space_invaders.keyholder_id ORDER BY id DESC', MYSQLI_USE_RESULT)){
    return $res->fetch_all(MYSQLI_ASSOC);
  }
  return array();
}
