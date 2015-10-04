<?php
  namespace Maglab;
  class Controller {
    protected $app;
    public function __construct($app = null){
      $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
      $this->init();
      $this->current_user = null;
    }
    
    public function init(){}
    
    public function require_user(){
      $this->current_user = member_authenticate($this->app);
    }
    
    public function require_admin(){
      $this->current_user = admin_authenticate($this->app);
    }
    
    public function render($template, $title, $data = []){
      $this->app->render($template,
        array_merge(
          array('title' => $title,
            'current_user' => $this->current_user),
          $data
        )
      );
    }
  }
