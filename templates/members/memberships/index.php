<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=edge' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1' name='viewport'>
<title>
MAG Laboratory <?php if(isset($this->data->title) and strlen($this->data->title) > 0){ echo '- ' . $this->data->title; } ?>
</title>
<link href='/css/bootstrap.min.css' rel='stylesheet'>
<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
<script src='/js/bootstrap.min.js'></script>
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src='https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js'></script>
<script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
<![endif]-->
<style>
  #main-container { margin-top: 75px; }
</style>
</head>
<body>
<nav class='navbar navbar-default navbar-fixed-top'>
<div class='container-fluid'>
<div class='navbar-header'>
<button class='navbar-toggle collapsed' data-target='#main-nav' data-toggle='collapse' type='button'>
<span class='sr-only'>Toggle navigation</span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
</button>
<a class='navbar-brand' href='/members'>Members Only</a>
</div>
<div class='collapse navbar-collapse' id='main-nav'>
<ul class='nav navbar-nav'>
<?php if($this->data->current_user){  ?>
<?php   if(isAdmin($this->data->current_user)){  ?>
<li>
<a href='/members/users'>Members</a>
</li>
<li>
<a href='/members/payments'>Payments</a>
</li>
<li class='dropdown'>
<a aria-expanded='false' aria-haspopup='true' class='dropdown-toggle' data-toggle='dropdown' href='#' role='button'>
Administration
<span class='caret'></span>
</a>
<ul class='dropdown-menu'>
<li>
<a href='/members/keyholders'>Keyholders</a>
</li>
<li>
<a href='/members/space_invaders'>Space Invaders</a>
</li>
<li>
<a href='/members/memberships'>Membership Payments</a>
</li>
</ul>
</li>
<li class='dropdown'>
<a aria-expanded='false' aria-haspopup='true' class='dropdown-toggle' data-toggle='dropdown' href='#' role='button'>
External
<span class='caret'></span>
</a>
<ul class='dropdown-menu'>
<li>
<a href='https://mail.zoho.com/cpanel/index.do#groups' target='_blank'>Zoho Admin</a>
</li>
<li>
<a href='https://mail.zoho.com/biz/index.do' target='_blank'>Zoho Mail</a>
</li>
<li>
<a href='https://www.mailchimp.com' target='_blank'>Mailchimp</a>
</li>
</ul>
</li>
<?php   } ?>
<?php } ?>
</ul>
<ul class='nav navbar-nav navbar-right'>
<li>
<a href='/hal'>HAL</a>
</li>
<li>
<a href='/members/procurement'>Shopping</a>
</li>
<?php if($this->data->current_user){  ?>
<li>
<a href='/members'>Me</a>
</li>
<li>
<a href='/members/logout'>logout</a>
</li>
<?php } else { ?>
<li>
<a href='/members/login'>Login</a>
</li>
<?php } ?>
</ul>
</div>
</div>
</nav>
<div class='container' id='main-container'>
<h1>Payments</h1>
<button class='btn btn-info' data-target='#pay-modal' data-toggle='modal' id='add-payment'>Add (Manual) Payment</button>
<table class='table table-hover'>
<thead>
<th>Name</th>
<th>Email</th>
<th>Paid On</th>
<th>Amount</th>
</thead>
<tbody>
<?php foreach($payments as $payment){
  if($payment['user_id'] == 0 or $payment['user_id'] == null){
    $member = array('first_name' => 'Guest', 'last_name' => $payment['guest_name'], 'email' => '');
  } else {
    $member = $members_map[$payment['user_id']];
    if(!$member){ continue; }
  }
 ?>
<tr>
<td>
<?php echo filter_text($member['first_name'] . ' ' . $member['last_name'], true); ?>
</td>
<td>
<?php echo filter_text($payment['email'], true); ?>
</td>
<td>
<?php echo filter_text($payment['paid_on'], true); ?>
</td>
<td>
<?php echo filter_text('$' . ($payment['amount']/100), true); ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<h1>Members that have not paid in the last 30 days</h1>
<table class='table table-hover'>
<thead>
<tr>
<th>Name</th>
<th>Email</th>
</tr>
</thead>
<tbody>
<?php foreach($non_paid_ids as $non_paid_id){ 
  $member = $members_map[$non_paid_id];
  if(!$member){ continue; }
 ?>
<tr>
<td>
<?php echo filter_text($member['first_name'] . ' ' . $member['last_name'], true); ?>
</td>
<td>
<?php echo filter_text($member['email'], true); ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<div class='modal fade' id='pay-modal' role='dialog'>
<div class='modal-dialog'>
<div class='modal-content'>
<form action='/members/memberships/payment' id='manual-payment' method='POST'>
<div class='modal-header'>
<h1>Manually Add Payment</h1>
</div>
<div class='modal-body'>
<div class='form-group'>
<label for='user_id'>Select Member</label>
<select class='form-control' name='user_id'>
<option value=''>Guest</option>
<?php foreach($all_users as $user){
 ?>
<option value='<?php echo filter_text($user["id"], true); ?>'>
<?php echo filter_text($user["first_name"] . " " . $user["last_name"], true); ?>
</option>
<?php } ?>
</select>
</div>
<div class='form-group'>
<label for='guest_name'>Guest Name (if Guest)</label>
<input class='form-control' name='guest_name'>
</div>
<div class='form-group'>
<label for='amount'>
Amount
<strong class='text-warning'>(in cents)</strong>
</label>
<input class='form-control' id='payment-amount' name='amount' type='number'>
<span class='hint'>This is in cents, so $25.00 should be entered as 2500</span>
</div>
<div class='form-group'>
<label for='paid_on'>Paid On (Date)</label>
<input class='form-control' id='payment-date' name='paid_on' type='date'>
<span class='hint'>Format MM/DD/YYYY or YYYY-MM-DD for best results</span>
</div>
</div>
<div class='modal-footer'>
<button class='btn btn-primary' type='submit'>Add Payment</button>
<button class='btn btn-default' data-dismiss='modal' type='button'>Cancel</button>
</div>
</form>
</div>
</div>
</div>
<script>
  $(function(){
    $('#manual-payment').on('submit', function(e){
      if($('#payment-amount').val().length == 0){
        alert("Please enter a payment amount");
        e.preventDefault();
        return;
      }
      if($('#payment-date').val().length == 0 || $('#payment-date').val().split(/[-/]/).length != 3){
        alert("Please enter a payment date");
        e.preventDefault();
        return;
      }
      
    });
  });
</script>


</body>
</html>
