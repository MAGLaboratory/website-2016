<?php
namespace Maglab\Members;
class Emailer extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/emailer', $admin_mw, [$this, 'index']);
    $this->app->post('/members/emailer/send', $admin_mw, [$this, 'send']);
    $this->app->get('/members/emailer/active_members', $admin_mw, [$this, 'active_members']);
  }
  
  function index(){
    $this->render('members/emailer/index.php', 'Emailer');
  }
  
  function active_members(){
    $query = $this->mysqli_prepare("SELECT id, role, email, first_name, last_name FROM users WHERE FIND_IN_SET('Keyholder', role) OR FIND_IN_SET('General', role) OR FIND_IN_SET('Guest', role) OR FIND_IN_SET('Backer', role)");
    
    $this->respond['members'] = $this->mysqli_results($query);
    $this->render('members/emailer/active_members.php', 'Active Members');
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
  
  /*
  function send_template($controller, $app){
    $body = $this->render_to_string('email/template.php', array());
    $lines = explode("\n", $controller->params('payload'));
    
    $sent = [];
    foreach($lines as $i => $line){
      $csv = str_getcsv($line);
      
      $email = $csv[0];
      $first_name = $csv[1];
      $last_name = $csv[2];
      
      $name = $first_name . ' ' . $last_name;
      
      if(empty($csv) or empty($email)){
        continue;
      }
      $to = "{$name} <{$email}>";
      $this->email_html($to, 'Greetings from MAG Laboratory', $body);
      
      array_push($sent, $to);
    }
    
    $this->header('Content-Type', 'text/plan');
    var_dump($sent);
  }
  */
  
  
  function send_feb27_2016($controller, $app){
    $body = $this->render_to_string('email/mass/feb27_2016.php', array());
    $lines = explode("\n", $controller->params('payload'));
    
    $sent = [];
    foreach($lines as $i => $line){
      $csv = str_getcsv($line);
      
      $email = $csv[0];
      $first_name = $csv[1];
      $last_name = $csv[2];
      
      $name = $first_name . ' ' . $last_name;
      
      if(empty($csv) or empty($email)){
        continue;
      }
      $to = "{$name} <{$email}>";
      $this->email_html($to, 'MAG Laboratory - Important Meeting on Saturday Feb 27, 2016', $body);
      
      array_push($sent, $to);
    }
    
    $this->header('Content-Type', 'text/plan');
    var_dump($sent);
  }
  
}
