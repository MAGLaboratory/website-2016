<?php
define('SLIM_NO_RUN', true);
require '../members-load.php';
$env = Slim\Environment::Mock($_SERVER);
$env = Slim\Environment::getInstance();
$app->request = new Slim\Http\Request($env);
$app->response = new Slim\Http\Response();

$current_user = member_authenticate_by_auth($app->getCookie('auth'));
if(!$current_user){ die(); }
raise($current_user);

