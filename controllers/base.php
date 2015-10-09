<?php
namespace Maglab;
class Controller {
  protected $app;
  public function __construct($app = null){
    $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
    $this->init();
    $this->current_user = null;
    $this->respond = array();
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
        $this->respond,
        $data
      )
    );
  }
  
  public function render_to_string($template, $data){
    $view = $this->app->view();
    //$view->appendData($data);
    return $view->fetch($template, $data);
  }
  
  public function email_html($to, $subject, $html){
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: MagLaboratory.com <website@maglaboratory.org>' . "\r\n";
    $headers .= 'Reply-To: website@maglaboratory.org' . "\r\n";
    mail($to, $subject, $html, $headers);
  }
}
