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
<?php if($this->data->reset_email){ ?>
<div class='row'>
<div class='col-md-12'>
<h1>Reset Email sent</h1>
<p>
Please check your inbox
<?php echo filter_text("(" . $this->data->reset_email . ") ", true); ?>
for a reset email.
</p>
<p>If you did not receive an email, contact a MagLaboratory administrator.</p>
</div>
</div>
<?php } elseif($this->data->reset_user) { ?>
<div class='row'>
<div class='col-md-12'>
<h1>Reset Password</h1>
<form action='/members/reset_password' class='form-horizontal' id='reset-password' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='confirm_email'>Confirm Email</label>
<div class='col-sm-10'>
<input class='form-control' id='confirm_email' name='confirm_email' type='email'>
<p class='help-block'>
This is the same email you used to request the password reset. It will not complete the password reset process if it is wrong.
<br>
Make sure it's correct because, for security purposes, we will not show an error if it's wrong.
</p>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='new_password'>New Password</label>
<div class='col-sm-10'>
<input class='form-control' id='new_password' name='new_password' placeholder='******' type='password'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='confirm_password'>Confirm Password</label>
<div class='col-sm-10'>
<input class='form-control' id='confirm_password' placeholder='******' type='password'>
<div class='hide alert alert-danger' id='error-pw-match' role='alert'>
<span aria-hidden='true' class='glyphicon glyphicon-exclamation-sign'></span>
<span class='sr-only'>Error:</span>
Password Confirmation does not match password.
</div>
</div>
</div>
<div class='form-group'>
<div class='col-sm-offset-2 col-sm-10'>
<input name='_METHOD' type='hidden' value='PUT'>
<input name='now' type='hidden' value='<?php echo filter_text($now, true); ?>'>
<input name='reset_code' type='hidden' value='<?php echo filter_text($reset_code, true); ?>'>
<button class='btn btn-primary' type='submit'>Reset Password</button>
</div>
</div>
</form>
</div>
<script>
  $(function(){
    $('#reset-password').on('submit', function(e){
      if($('#new_password').val() != $('#confirm_password').val()){
        $('#error-pw-match').removeClass('hide');
        e.preventDefault();
      } else {
        $('#error-pw-match').addClass('hide');
      }
    });
  });
</script>
</div>
<?php } elseif($this->data->completed_reset){ ?>
<div class='row'>
<div class='col-md-12'>
<h1>Password Reset Complete</h1>
<p>
Your password has been reset.
You can now
<a href='/members/login'>login with your new password.</a>
</p>
</div>
</div>
<?php } else { ?>
<div class='row'>
<div class='col-md-12'>
<h1>Forgot Password</h1>
<p>If you don't remember the email address you used, contact a MagLaboratory administor.</p>
<?php if($this->data->reset_expired){ ?>
<div class='alert alert-danger' role='alert'>
<strong>Reset Code Expired!</strong>
The reset code has expired. Please try again.
</div>
<?php } ?>
</div>
</div>
<div class='row'>
<div class='col-md-12'>
<form action='/members/forgot_password' class='form-horizontal' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='email'>Email</label>
<div class='col-sm-10'>
<input class='form-control' id='email' name='email' placeholder='you@maglaboratory.org' type='email'>
</div>
</div>
<div class='form-group'>
<div class='col-sm-offset-2 col-sm-10'>
<button class='btn btn-default' type='submit'>Reset Password</button>
</div>
</div>
</form>
</div>
</div>
<?php } ?>
</div>

</body>
</html>
