<?php

function back(){
  header('Location: /members');
  die();
}

define('SLIM_NO_RUN', true);
require '../members-load.php';
$env = Slim\Environment::Mock($_SERVER);
$env = Slim\Environment::getInstance();
$app->request = new Slim\Http\Request($env);
$app->response = new Slim\Http\Response();

$current_user = member_authenticate_by_auth($app->getCookie('auth'));
if(!$current_user){ back(); }

$user = get_user_by_id($current_user['id']);

if(!$user or !empty($user->wikiusername)){ back(); }

if(!defined('WIKI_INSTALL_PATH')){ back(); }

# BEGIN WIKI SETUP
# --->
define('SKIP_WIKI_EXTS', true);
$IP = WIKI_INSTALL_PATH;
require_once "$IP/includes/AutoLoader.php";
define('MEDIAWIKI', true);

require_once "$IP/includes/Defines.php";
require_once "$IP/includes/DefaultSettings.php";
require_once "$IP/includes/GlobalFunctions.php";
# Load composer's autoloader if present
if ( is_readable( "$IP/vendor/autoload.php" ) ) {
      require_once "$IP/vendor/autoload.php";
}

if ( defined( 'MW_CONFIG_CALLBACK' ) ) {
      # Use a callback function to configure MediaWiki
      call_user_func( MW_CONFIG_CALLBACK );
} else {
      // Require the configuration (probably LocalSettings.php)
      require "$IP/LocalSettings.php";
}
require "$IP/includes/Setup.php";
require_once "$IP/includes/User.php";

function createWikiUser($username, $email, $password){
  $mwuser = User::newFromName($username);
  if(!is_object($mwuser)){ return false; }
  $added = $mwuser->addToDatabase();
  if($added->isOk()){
    $mwuser->setPassword($password);
    $mwuser->setEmail($email);
    $mwuser->confirmEmail();

    #$mwuser->setRealName($name);
    #$mwuser->addGroup($group);

    $mwuser->saveSettings(); // no return value?
    $ssUpdate = new SiteStatsUpdate( 0, 0, 0, 0, 1 );
    $ssUpdate->doUpdate();
    return $mwuser->getId();
  }
  return false;
}
# <---
# END WIKI SETUP

$username = $_POST['wiki_username'];
$password = $_POST['wiki_password'];
$email = $_POST['wiki_email'];

$success = createWikiUser($username, $email, $password);

if($success){
  header('Location: /members/me/wiki?success=1');
  if(is_int($success)){
    # If we created an account but didn't successfully save this, it would be bad
    $mysqli = get_mysqli_or_die();
    if($stmt = $mysqli->prepare("UPDATE users SET wiki_uid = ?, wikiusername = ? WHERE id = ? LIMIT 1")){
      $stmt->bind_param('isi', $success, $username, $user->id);
      $stmt->execute();
    }
  }
} else {
  header('Location: /members/me/wiki?success=0');
}
