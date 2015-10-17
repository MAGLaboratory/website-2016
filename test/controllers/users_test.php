<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/Users');

class UsersTest extends MaglabExam {
  function setup($session = 'marinus'){
    parent::setup();
    $this->routes = new \MagLab\Members\Users($this->app);
    $this->auth($session);
  }
  
  function testRequiresAdmin(){
    $this->adminOnly('/members/users');
  }
  
  function testUsersIndex(){
    $this->client->get('/members/users');
    $this->assertOk();
    $this->assertInBody('test-cerevisiae&commat;kiafaldorius&period;net');
    $this->assertInBody('test-pastorianus&commat;kiafaldorius&period;net');
    $this->assertInBody('test-naardenensis&commat;kiafaldorius&period;net');
    $this->assertInBody('Gephyrocapsa');
    $this->assertInBody('Vanvoorstia');
  }
  
  function testInviteUser(){
    $this->client->post('/members/users', array('email' => 'pineapple@example.sj', 'first_name' => 'Pine', 'last_name' => 'Pommel', 'roles' => array('Keyholder')));
    $this->assertOk();
    $this->assertInBody('pineapple&commat;example&period;sj');
    $this->setup();
    $this->client->get('/members/users');
    $this->assertInBody('Pine Pommel');
  }
  
}
