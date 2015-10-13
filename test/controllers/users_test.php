<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/Users');

class UsersTest extends MaglabExam {
  function setup(){
    parent::setup();
    $this->routes = new \MagLab\Members\Users($this->app);
  }
  
  function testRequiresAdmin(){
    $this->adminOnly('/members/users');
  }
}
