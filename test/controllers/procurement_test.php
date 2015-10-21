<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/Procurement');

class ProcurementTest extends MaglabExam {
  function setup(){
    parent::setup();
    $this->routes = new \MagLab\Members\Procurement($this->app);
  }
  
  function testIndex(){
    $this->client->get('/members/procurement');
    $this->assertOk();
    $this->assertInBody('procurement-form');
  }
  
  function testCreate(){
    $this->client->post('/members/procurement', array('category' => 'Groceries', 'name' => 'Beer', 'need_amount' => '10', 'have_amount' => '5', 'cost' => '$30', 'description' => 'To Loosen Up and Get Down wid it'));
    $this->assertOk();
    $this->assertInBody('procurement-form');
    $this->assertInBody('Groceries');
    $this->assertInBody('Beer');
    $this->assertInBody('Down wid it');
  }
  
  function testGot(){
    $this->client->patch('/members/procurement/1', array('got' => '1'));
    $this->assertOk();
    $this->assertInBody('393');
  }
  
  function testLost(){
    $this->client->patch('/members/procurement/2', array('lost' => '1'));
    $this->assertOk();
    $this->assertInBody('738');
  }
  
  function testNeedMore(){
    $this->client->patch('/members/procurement/1', array('need' => '1'));
    $this->assertOk();
    $this->assertInBody('4835');
  }
  
  function testNeedLess(){
    $this->client->patch('/members/procurement/2', array('skip' => '1'));
    $this->assertOk();
    $this->assertInBody('3429');
  }
  
  function testRemove(){
    $this->client->delete('/members/procurement/3');
    $this->assertOk();
  }
}
