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
    $this->assertOk();
    $this->assertInBody('login-form');
    $this->assertInBody('Forgot Password?');
    
    $this->client->get('/members');
    $this->assertCode(302);
  }
  
  function testForgotPasswordPage(){
    $this->client->get('/members/forgot_password');
    $this->assertOk();
    $this->assertInBody('Reset Password');
  }
  
  function testLoginFlow(){
    $this->client->post('/members/login', array('email' => 'maglabs-test-algae@kiafaldorius.net', 'password' => 'abcdef'));
    $this->assertRedirect('/members');
    # Login redirects to members page
    $session = $this->app->response()->cookies->get('auth')['value'];
    $this->client->get('/members', array(), array('HTTP_COOKIE' => "auth=$session"));
    $this->assertOk();
    $this->assertInBody('Gephyrocapsa');
    $this->assertInBody('oceanica');
    # Logout
    $this->assertInBody('/members/logout');
    $this->client->get('/members/logout', array(), array('HTTP_COOKIE' => "auth=$session"));
    $this->client->get('/members', array(), array('HTTP_COOKIE' => "auth=$session"));
    $this->assertRedirect('/members/login');
    
  }
  
  function testResetPasswordFailure(){
    # We show the success message even if we didn't actually do the reset
    $this->client->post('/members/forgot_password', array('email' => 'Pelagibacter-ubique@example.test'));
    $this->assertOk();
    $this->assertInBody('Pelagibacter');
    $this->assertUnaffected();
  }
  
  function testResetCodeFailure(){
    $this->client->get('/members/reset_password', array('now' => (string)time(), 'reset_code' => 'euaeauaoe'));
    $this->assertOk();
    $this->assertInBody('Reset Code Expired');
    $this->assertInBody('Forgot Password');
  }

  function testResetPasswordFlow(){
    
    $email = 'test-bennetiana@kiafaldorius.net';
    $this->client->post('/members/forgot_password', array('email' => $email));
    $this->assertOk();
    $this->assertInBody('bennetiana');
    $this->assertAffected();
    $reset_path = explode('?', $this->routes->respond['reset_path']);
    
    # check the reset password page
    $this->setup();
    parse_str($reset_path[1], $reset_code);
    $this->client->get($reset_path[0], $reset_code);
    $this->assertOk();
    $this->assertInBody('Confirm Email');
    $this->assertInBody('Confirm Password');
    
    # An incorrect email still continues the flow, but we don't say it failed.
    $this->client->put('/members/reset_password', array_merge(array('confirm_email' => 'MickyMouse@example.com', 'new_password' => 'abcdef'), $reset_code));
    $this->assertOk();
    $this->assertInBody('Password Reset Complete');
    
    # ... so since it wasn't completed, we can go back to the page and try again
    $this->setup();
    $this->client->put('/members/reset_password', array_merge(array('confirm_email' => $email, 'new_password' => 'aaaaaa'), $reset_code));
    $this->assertOk();
    $this->assertInBody('Password Reset Complete');
    
    $this->setup();
    $this->client->get($reset_path[0], $reset_code);
    $this->assertOk();
    $this->assertInBody('Reset Code Expired');
  }
  
  function testChangePassword(){
    
  }
  
  function testUpdateInfo(){
    
  }
  
  // TODO: Test that Invite and Reset can't login
}
