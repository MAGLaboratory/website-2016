<?php

# TODO: Wrap this in an object
function get_mysqli(){
  global $mysqli;
  if($mysqli){
    return $mysqli;
  } else {
    try {
      $mysqli = new mysqli(mysql_host, mysql_user, mysql_pass, mysql_db);
    } catch (Exception $e){
      $mysqli = false;
    }
    if(mysqli_connect_errno()){
      return false;
    }
    return $mysqli;
  }
}

function get_mysqli_or_die(){
  $mysqli = get_mysqli();
  if($mysqli){ return $mysqli; }
  else { die('unable to connect to database'); }
}


function filter_email($text, $html = true){
  $filtered = filter_var($text, FILTER_SANITIZE_EMAIL);
  return ($html ? htmlspecialchars($filtered, ENT_QUOTES | ENT_HTML5) : $filtered);
}

# htmlentities works in value attribute, so no need to use this?
#function filter_text_basic($text, $html = true){
#  return ($html ? htmlspecialchars($text, ENT_QUOTES | ENT_HTML5) : $text);
#}

function filter_text($text){
  return htmlentities($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function base64url_encode($data) { 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} 

function base64url_decode($data) { 
  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
}

function random_b64($length = 21){
  return base64url_encode(openssl_random_pseudo_bytes($length));
}
