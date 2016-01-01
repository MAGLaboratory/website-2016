<?php
namespace Maglab\Members;
class Emailer extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/emailer', $admin_mw, [$this, 'index']);
    $this->app->post('/members/emailer/send', $admin_mw, [$this, 'send']);
  }
  
  function index(){
    $this->render('members/emailer/index.php', 'Emailer');
  }
  
  function send(){
    $method = 'send_' . $this->params('email_code');
    
    if(is_callable(array($this, $method), false, $method)){
      call_user_func([$this, $method], $this, $this->app);
    } else {
      $jabberwocky = ['Twas bryllyg', 'and the slythy toves',
        'Did gyre', 'and gymble in the wabe',
        'All mimsy were the borogoves',
        'And the mome raths outgrabe.'];
      echo $jabberwocky[rand(0,count($jabberwocky))];
    }
  }
  
  function send_desperate_price_increase($controller, $app){
    $body = $this->render_to_string('email/mass/desperate_price_increase.php', array());
    $lines = explode("\n", $controller->params('payload'));

    $sent = [];
    foreach($lines as $i => $line){
      $email = str_getcsv($line);
      if(!$email or count($email) < 2 or !$email[0] or !$email[1]){
        continue;
      }
      
      $to = "{$email[0]} <${email[1]}>";
      $this->email_html($to, 'Greetings to All Members of MAG Laboratory', $body);
      
      array_push($sent, $to);
    }
    
    $this->header('Content-Type', 'text/plain');
    var_dump($sent);
  }
  
  function send_general_resubscribe($controller, $app){
    $body = $this->render_to_string('email/mass/general_resubscribe.php', array());
    $lines = explode("\n", $controller->params('payload'));
    
    $sent = [];
    foreach($lines as $i => $line){
      $email = str_getcsv($line);
      if(!$email or count($email) < 2 or !$email[0] or !$email[1]){
        continue;
      }
      $to = "{$email[0]} <{$email[1]}>";
      $this->email_html($to, 'Happy New Year! Please Re-subscribe to MAG Laboratory', $body);
      
      array_push($sent, $to);
    }
    
    $this->header('Content-Type', 'text/plain');
    var_dump($sent);
    
  }
  
}
