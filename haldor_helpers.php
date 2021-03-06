<?php


function base64_urlencode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
}

function generate_session(){
  return base64_urlencode(openssl_random_pseudo_bytes(30));
}

function authenticate($app) {
  if(SKIP_CHECKSUM){
    return true;
  }
  
  global $secret;
  $input = file_get_contents('php://input');
  $session = $app->request->headers->get('X-Session');
  $hmac = hash_hmac('sha256', $input . $session, $secret, false);
  if($hmac != $app->request->headers->get('X-Checksum')){
    die("AUTH FAIL");
  } else {
    return true;
  }
}

function save_payload($app){
  $post = $app->request->post();
  $session = $app->request->headers->get('X-Session');
  
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  if($stmt = $mysqli->prepare("INSERT INTO haldor_payloads (payload, session) VALUES (?, ?)")){
    $stmt->bind_param('ss', json_encode($post), $session);
    $stmt->execute();
    $stmt->close();
  } else {
    return false;
  }
}

function save_switches($app) {
  $post = $app->request->post();
  $session = $app->request->headers->get('X-Session');
  $checks = ['Front_Door', 'Main_Door', 'Office_Motion', 'Shop_Motion', 'Open_Switch', 'Temperature'];

  mark_old_switches();

  foreach($checks as $i => $sensor){
    if(isset($post[$sensor])){
      update_switch($sensor, $session, $post[$sensor]);
    }
  }

  update_switch('Boot', $session, '1');
}

function mark_old_switches(){
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  # If the last response we got was over 15 minutes ago, it means we missed 3 update payloads
  # assume we lost connectivity and mark them as ended.
  
  $mysqli->real_query("UPDATE haldor SET end_at = NOW(), mark_at = NOW() WHERE end_at IS NULL AND progress_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)");

}

function update_switch($sensor, $session, $value){
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  $ival = (int)$value;
  $query = null;
  if($ival < 1){
    # TODO: Treat Motion type sensors differently because they're instantaneous
    # Item is no longer open, mark end_at
    $query = "UPDATE haldor SET progress_count = progress_count + 1, end_at = NOW(), last_value = ? WHERE sensor = ? AND end_at IS NULL AND session = ?";
  } else {
    $query = "UPDATE haldor SET last_value = ?, progress_count = progress_count + 1, progress_at = NOW() WHERE sensor = ? AND end_at IS NULL AND session = ?";
  }
  
  if($stmt = $mysqli->prepare($query)){
    $stmt->bind_param('sss', $value, $sensor, $session);
    $stmt->execute();
    if($ival >= 1 && $mysqli->affected_rows == 0){
      insert_switch($sensor, $session);
      # If we couldn't update when sensor is on, it means this is newly activated
      # so we'll have to do an insert.
      # If it's off, we don't care
    }
    
    $stmt->close();
  } else {
    return false;
  }
}

function set_boot_switch($app, $session){
  $post = $app->request->post();
  
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  if($stmt = $mysqli->prepare("INSERT INTO haldor_payloads (payload, session) VALUES (?, ?)")){
    $stmt->bind_param('ss', json_encode($post), $session);
    $stmt->execute();
    $stmt->close();
  } else {
    return false;
  }
  
  mark_old_switches();
  insert_switch('Boot', $session);
}

function insert_switch($sensor, $session){
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  if($stmt = $mysqli->prepare("INSERT INTO haldor (sensor, start_at, progress_at, session, created_at) VALUES (?, NOW(), NOW(), ?, NOW())")){
    $stmt->bind_param('ss', $sensor, $session);
    $stmt->execute();
    $stmt->close();
  } else {
    return false;
  }
}

function parse_halley_output($app){
  $post = $app->request->post();
  $session = $app->request->headers->get('X-Session');
  
  $mysqli = get_mysqli();
  if(!$mysqli){ return false; }
  
  $now = time();
  
  $rfid = null;
  $open_at = null;
  $denied_at = null;

  preg_match_all('/User (\d+) presented tag.+?(denied|granted) access at/s', $post['output'], $matches, PREG_SET_ORDER);
  
  if($stmt = $mysqli->prepare("INSERT INTO space_invaders (keycode, open_at, denied_at, created_at) VALUES (?, FROM_UNIXTIME(?), FROM_UNIXTIME(?), NOW())")){
    foreach($matches as $match){
      $rfid = $match[1];
      if($match[2] == 'denied'){
        $open_at = null;
        $denied_at = $now;
      } else {
        $open_at = $now;
        $denied_at = null;
      }
      $stmt->bind_param('sii', $rfid, $open_at, $denied_at);
      $stmt->execute();
    }
    $stmt->close();
  }
  
  return true;
}
