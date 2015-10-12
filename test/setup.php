<?php

require_once dirname(__FILE__) . '/test_config.php';

$mysql_user = escapeshellarg(mysql_user);
$mysql_pass = escapeshellarg(mysql_pass);
$mysql_db = escapeshellarg(mysql_db);

$schema_path = escapeshellarg(test_root . '/maglabs_haldor-schema.sql');
passthru("mysql -u${mysql_user} -p${mysql_pass} ${mysql_db} < '${schema_path}'");
$fixture_path = escapeshellarg(test_root . '/maglabs_haldor-fixtures.sql');
passthru("mysql -u${mysql_user} -p${mysql_pass} ${mysql_db} < '${fixture_path}'");



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
    $app->setName('default');
    $app->router = new NoCacheRouter($app->router);
    return $app;
  }
    
  public function responseCode(){
    return $this->client->response->status();
  }
  
  public function responseBody(){
    return $this->client->response->getBody();
  }
  
  public function assertOk(){
    $this->assertEquals(200, $this->responseCode());
  }
  
  public function assertCode($code = 301){
    $this->assertEquals($code, $this->responseCode());
  }
  
  public function assertInBody($text){
    $this->assertContains($text, $this->responseBody());
  }
  
  public function assertRedirect($path, $code = 302){
    $this->assertEquals($code, $this->responseCode());
    $this->assertEquals($path, $this->client->response->headers['Location']);
  }
  
  public function assertUnaffected($row_count = 0){
    $this->assertEquals($row_count, $this->routes->respond['affected_rows']);
  }
  
  public function assertAffected($row_count = 1){
    $this->assertEquals($row_count, $this->routes->respond['affected_rows']);
  }
  
  public function auth($session){
    return array('HTTP_COOKIE' => "auth=$session");
  }
};
