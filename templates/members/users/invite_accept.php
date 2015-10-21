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
<a href='/members/keyholders'>Keyholders</a>
</li>
<li>
<a href='/members/space_invaders'>Space Invaders</a>
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
</ul>
</li>
<?php   } ?>
<?php } ?>
</ul>
<ul class='nav navbar-nav navbar-right'>
<li>
<a href='/hal'>HAL</a>
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
<div class='row'>
<div class='col-md-12'>
<?php if(isset($user_setup_complete)){ ?>
<h1>Account Setup Complete</h1>
<p>
You can now
<a href='/members/login'>Login</a>
to your account with your email (
<?php echo filter_text($user_setup_complete['email'] , true); ?>
) and password.
</p>
<?php } else { ?>
<h1>Accept Invite</h1>
<form action='/members/invite' class='form-horizontal' id='invite-accept' method='post'>
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
<input name='now' type='hidden' value='<?php echo filter_text($now, true); ?>'>
<input name='code' type='hidden' value='<?php echo filter_text($code, true); ?>'>
<button class='btn btn-primary' type='submit'>Create Account</button>
</div>
</div>
</form>
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
<?php } ?>
</div>
</div>
</div>

</body>
</html>
