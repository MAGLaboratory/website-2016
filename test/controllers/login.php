<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/Login');

class LoginTest extends LocalWebTestCase {
  function setup(){
    parent::setup();
    $this->routes = new \MagLab\Members\Login($this->app);
  }

  function testInit(){
    $this->assertInstanceOf('MagLab\Members\Login', $this->routes);
  }
  
  function testLoginPage(){
    $this->client->get('/members/login');
    $this->assertEquals(200, $this->responseCode());
    $this->assertContains('login-form', $this->responseBody());
    $this->assertContains('Forgot Password?', $this->responseBody());
  }
}
