<?php

require_once dirname(__FILE__) . '/test_config.php';

$mysql_user = escapeshellarg(mysql_user);
$mysql_pass = escapeshellarg(mysql_pass);
$mysql_db = escapeshellarg(mysql_db);

$sql_path = escapeshellarg(test_root . '/maglabs_haldor.sql');

exec("mysql -u${mysql_user} -p${mysql_pass} ${mysql_db} < '${sql_path}'");


$helpers = array('helpers', 'members_helpers', 'Slim/Slim', 'Maglab/base');

foreach($helpers as $helper){
  require_once HALDOR_ROOT . '/' . $helper . '.php';
}

\Slim\Slim::registerAutoloader();


function test_require($name){
  require_once HALDOR_ROOT . '/' . $name . '.php';
}

require test_root . '/slim-test-helpers/NoCacheRouter.php';
require test_root . '/slim-test-helpers/WebTestCase.php';
require test_root . '/slim-test-helpers/WebTestClient.php';

class LocalWebTestCase extends WebTestCase {
  public function getSlimInstance() {
    $app = new \Slim\Slim(array(
        'version'        => '0.0.0',
        'debug'          => false,
        'mode'           => 'test',
        'templates.path' => HALDOR_ROOT . '/templates'
    ));
    return $app;
  }
    
  public function responseCode(){
    return $this->client->response->status();
  }
  
  public function responseBody(){
    return $this->client->response->getBody();
  }
};
