<?php
namespace Maglab;

require_once HALDOR_ROOT . '/lib/htmlpurifier-4.7.0-standalone/HTMLPurifier.standalone.php';
use HTMLPurifier_Config;
use HTMLPurifier;

class Controller {
  protected $app;
  public function __construct($app = null){
    $this->app = ($app instanceof \Slim\Slim) ? $app : \Slim\Slim::getInstance();
    $this->init();
    $this->current_user = null;
    $this->respond = array();
    $this->purifier = null;
  }
  
  public function init(){}
  
  public function params($name){
    return $this->app->request->params($name);
  }
  
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
          'current_user' => $this->current_user,
          'controller' => $this),
        $this->respond,
        $data
      )
    );
  }
  
  public function redirect($path){
    $this->app->redirect($path);
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
  
  public function purifier(){
    if($this->purifier){ return $this->purifier; }
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', '/tmp');
    $config->set('AutoFormat.Linkify', true);
    $this->purifier = new HTMLPurifier($config);
    return $this->purifier;
  }
  
  public function purify($dirty_html){
    return $this->purifier()->purify($dirty_html);
  }
}
