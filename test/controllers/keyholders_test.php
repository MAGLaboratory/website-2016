<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/Keyholders');

class KeyholdersTest extends MaglabExam {
  function setup(){
    parent::setup();
    $this->routes = new \MagLab\Members\Keyholders($this->app);
    $this->auth('marinus');
  }
  
  function testRequiresAdmin(){
    $this->adminOnly('/members/keyholders');
  }
  
  function testKeyholdersIndex(){
    $this->client->get('/members/keyholders');
    $this->assertOk();
    $this->assertInBody('Add Keyholder');
    $this->assertInBody('777');
    $this->assertInBody('131313');
    $this->assertInBody('LuckyCharms');
    $this->assertInBody('Friday');
    $this->assertInBody("data-keyholder_id='3'"); // End Now for NOP user
  }
  
  function testEndNow(){
    $this->client->put('/members/keyholders/3', array('end_now' => '1'));
    $this->assertRedirect('/members/keyholders');
    $this->setup();
    $this->client->get('/members/keyholders');
    $this->assertNoBody("data-keyholder_id='3'");
  }
  
  function testAddKeyholder(){
    $keycode = '74263822';
    $person = 'TESTING add Keyholder';
    $this->client->post('/members/keyholders', array('keycode' => $keycode, 'person' => $person, 'start_at' => '', 'end_at' => '', 'back' => '/members/keyholders'));
    $this->assertRedirect('/members/keyholders');
    
    $this->setup();
    $this->client->get('/members/keyholders');
    $this->assertOk();
    $this->assertInBody($keycode);
    $this->assertInBody($person);
  }
  
  function testSpaceInvaders(){
    $this->client->get('/members/space_invaders');
    $this->assertOk();
    $this->assertInBody('80085');
    $this->assertInBody('376616');
  }
}
