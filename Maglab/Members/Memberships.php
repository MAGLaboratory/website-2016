<?php
namespace Maglab\Members;
class Memberships extends \Maglab\Controller {
  public function init(){
    $admin_mw = [$this, 'require_admin'];
    $this->app->get('/members/memberships', $admin_mw, [$this, 'index']);
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
    $this->render('members/memberships/index.php', 'Membership Payments');
  }
  
  protected function all_members(){
    $stmt = $this->mysqli_prepare("SELECT id, first_name, last_name, email from users WHERE FIND_IN_SET('General', `role`) OR FIND_IN_SET('Keyholder', `role`)");
    return $this->mysqli_results($stmt);
  }
  
  protected function payments_last_30_days(){
    $start = time() - 2678400;
    $stmt = $this->mysqli_prepare("SELECT * FROM membership_payments WHERE paid_on > FROM_UNIXTIME(?)");
    $stmt->bind_param('i', $start);
    return $this->mysqli_results($stmt);
  }
}
