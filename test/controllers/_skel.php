<?php

require_once dirname(__FILE__) . '/../setup.php';

test_require('Maglab/Members/CONTROLLER');

class CONTROLLERTest extends MaglabExam {
  function setup(){
    parent::setup();
    $this->routes = new \MagLab\Members\CONTROLLER($this->app);
  }
  
  
}
