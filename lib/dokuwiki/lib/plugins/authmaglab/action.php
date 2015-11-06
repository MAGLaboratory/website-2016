<?php

if(!defined('DOKU_INC')) die();

class action_plugin_authmaglab extends DokuWiki_Action_Plugin {

  public function __construct() {
    global $conf;
    
    // Disable certain actions that don't make sense (login form, password generation)
    $disableactions = explode(',', $conf['disableactions']);
    $disableactions = array_map('trim', $disableactions);
    if (!in_array('login', $disableactions)) {
      $disableactions[] = 'login';
    }
    
    $conf['disableactions'] = implode(',', $disableactions);
    $conf['autopasswd'] = 0;
  }

  function register(&$controller){
    $controller->register_hook('AUTH_LOGIN_CHECK', 'BEFORE', $this, 'skip_login_action', NULL);
  }
  
  function skip_login_action(&$event, $param){
    global $auth;
    global $USERINFO;
    
    $current_user = $auth->authenticate();
    if(!$current_user){ header('Location: /members'); die(); }
    
    $USERINFO = $auth->getUserData($current_user);
    if($USERINFO){
      $event->result = true;
      //$event->preventDefault();
      $event->data['user'] = $USERINFO['id'];
      $event->data['password'] = ' ';
      $event->data['silent'] = false;
    } else {
      header('Location: /members'); die();
    }
  }

}
