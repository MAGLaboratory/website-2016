<?php

require_once dirname(__FILE__) . '/../setup.php';

class LoginTest extends MaglabExam {
  function testClean(){
    // do nothing.
    // setup reinitializes the db, so this test puts the database back into the
    // initial fixture state. Good for when you need to change it a bit.
  }
}
