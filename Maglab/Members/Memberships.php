<?php
namespace Maglab\Members;
class Memberships extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/memberships', $admin_mw, [$this, 'index']);
    $this->app->post('/members/memberships/payment', $admin_mw, [$this, 'add_payment']);
    $this->app->get('/members/memberships/remind_nonpaid', $admin_mw, [$this, 'remind_nonpaid']);
  }
  
  function index(){
    $members = $this->all_members();
    $payments = $this->payments_last_30_days();
    $members_map = array();
    $get_member_ids = function($member){ return $member['id']; };
    $get_payment_user_ids = function($payment){ return $payment['user_id']; };
    
    $member_ids = array_map($get_member_ids, $members);
    $paid_ids = array_map($get_payment_user_ids, $payments);
    $non_paid_ids = array_diff($member_ids, $paid_ids);
    foreach($members as $member){
      $members_map[$member['id']] = $member;
    }
    $this->respond['non_paid_ids'] = $non_paid_ids;
    $this->respond['payments'] = $payments;
    $this->respond['members_map'] = $members_map;
    $this->respond['all_users'] = $this->all_users();
    $this->render('members/memberships/index.php', 'Membership Payments');
  }
  
  function remind_nonpaid(){
    $user_id = (int)$this->params('user_id');
    $user = $this->get_user_info($user_id);
    $month = $this->params('month');
    $this->nonpayment_email($user_id, $month);
    $this->respond['remind_nonpaid'] = $user;
    $this->respond['remind_nonpaid_month'] = $month;
    $this->index();
  }
  
  function add_payment(){
    $stmt = $this->mysqli_prepare("INSERT INTO membership_payments (user_id, guest_name, amount, paid_on, transaction_id) VALUES (?, ?, ?, FROM_UNIXTIME(?), ?)");
    $user_id = (int)$this->params('user_id');
    $guest_name = $this->params('guest_name');
    if($user_id > 0 or empty($guest_name)){ $guest_name = null; }
    $amount = (int)$this->params('amount');
    $paid_on = strtotime($this->params('paid_on'));
    $transaction_id = "MANUAL_" . $paid_on . "_" . $amount;
    $stmt->bind_param('isiis', $user_id, $guest_name, $amount, $paid_on, $transaction_id);
    $stmt->execute();
    $this->redirect('/members/memberships');
  }
  
  protected function all_members(){
    $stmt = $this->mysqli_prepare("SELECT id, first_name, last_name, email FROM users WHERE FIND_IN_SET('General', `role`) OR FIND_IN_SET('Keyholder', `role`)");
    return $this->mysqli_results($stmt);
  }
  
  protected function all_users(){
    $stmt = $this->mysqli_prepare("SELECT id, first_name, last_name FROM users");
    return $this->mysqli_results($stmt);
  }
  
  protected function payments_last_30_days(){
    $start = time() - 2678400;
    $stmt = $this->mysqli_prepare("SELECT * FROM membership_payments WHERE paid_on > FROM_UNIXTIME(?)");
    $stmt->bind_param('i', $start);
    return $this->mysqli_results($stmt);
  }
  
  protected function nonpayment_email($user, $month){
    $body = $this->render_to_string('email/mass/feb27_2016.php', array('month' => $month, 'user' => $user));
    $this->email_html($user->email, "MAGLab - Nonpayment Reminder for $month", $body);
  }
  
}
