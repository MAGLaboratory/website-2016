<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='utf-8'>
<meta content='IE=edge' http-equiv='X-UA-Compatible'>
<meta content='width=device-width, initial-scale=1' name='viewport'>
<title>
MAG Laboratory <?php if(strlen($this->data->title) > 0){ echo '- ' . $this->data->title; } ?>
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
<a class='navbar-brand' href='/admin'>Admin</a>
</div>
<div class='collapse navbar-collapse' id='main-nav'>
<ul class='nav navbar-nav'>
<li>
<a href='/'>
back to MAGLab
</a>
</li>
<?php if($this->data->current_user){ ?>
<li>
<a href='/members'>Me</a>
</li>
<?php if(isAdmin($this->data->current_user)){ ?>
<li>
<a href='/members/keyholders'>Keyholders</a>
</li>
<li>
<a href='/members/spaceinvaders'>Space Invaders</a>
</li>
<?php } ?>
<?php } ?>
</ul>
</div>
</div>
</nav>
<div class='container' id='main-container'>
<h2>Change Password</h2>
<?php if($this->data->pw_success){ ?>
<div class='alert alert-info alert-dismissible fade in' role='alert'>
<button aria-label='Close' class='close' data-dismiss='alert' role='alert' type='button'>
<span aria-hidden='true'>x</span>
</button>
<strong>Success!</strong>
Successfully updated your profile info.
</div>
<?php } else if(isset($this->data->pw_success)){ ?>
<div class='alert alert-warning alert-dismissible fade in' role='alert'>
<button aria-label='Close' class='close' data-dismiss='alert' role='alert' type='button'>
<span aria-hidden='true'>x</span>
</button>
<strong>Failed.</strong>
Failed to update your password. Check your current password and make sure the confirmation matches the new password.
</div>
<?php } ?>
<form action='/members/me' class='form-horizontal' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='current_password'>Current Password</label>
<div class='col-sm-10'>
<input class='form-control' id='current_password' name='current_password' type='password'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='new_password'>New Password</label>
<div class='col-sm-10'>
<input class='form-control' id='current_password' name='new_password' type='password'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='confirm_password'>Confirm New Password</label>
<div class='col-sm-10'>
<input class='form-control' id='current_password' name='confirm_password' type='password'>
</div>
</div>
<div class='form-group'>
<div class='col-sm-offset-2 col-sm-10'>
<button class='btn btn-default' type='submit'>Change Password</button>
</div>
</div>
</form>
<h1>Profile</h1>
<?php if($this->data->info_success){ ?>
<div class='alert alert-info alert-dismissible fade in' role='alert'>
<button aria-label='Close' class='close' data-dismiss='alert' role='alert' type='button'>
<span aria-hidden='true'>x</span>
</button>
<strong>Success!</strong>
Successfully updated your profile info.
</div>
<?php } else if(isset($this->data->info_success)){ ?>
<div class='alert alert-warning alert-dismissible fade in' role='alert'>
<button aria-label='Close' class='close' data-dismiss='alert' role='alert' type='button'>
<span aria-hidden='true'>x</span>
</button>
<strong>Failed.</strong>
Failed to update your profile info? huh...that's weird.
</div>
<?php } ?>
<form action='/members/me' class='form-horizontal' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='email'>Email</label>
<div class='col-sm-10'>
<input class='form-control' id='user_email' name='email' type='email' value='<?php echo filter_email($this->data->user->email, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='first_name'>First Name</label>
<div class='col-sm-10'>
<input class='form-control' id='user_first_name' name='first_name' type='text' value='<?php echo filter_text($this->data->user->first_name, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='last_name'>Last Name</label>
<div class='col-sm-10'>
<input class='form-control' id='user_last_name' name='last_name' type='text' value='<?php echo filter_text($this->data->user->last_name, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='main_phone'>Main Phone</label>
<div class='col-sm-10'>
<input class='form-control' id='user_main_phone' name='main_phone' type='tel' value='<?php echo filter_text($this->data->user->main_phone, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='emergency_phone'>Emergency Phone</label>
<div class='col-sm-10'>
<input class='form-control' id='user_emergency_phone' name='emergency_phone' type='tel' value='<?php echo filter_text($this->data->user->emergency_phone, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='interests'>Interests / Skills</label>
<div class='col-sm-10'>
TODO
</div>
</div>
<div class='form-group'>
<div class='col-sm-offset-2 col-sm-10'>
<button class='btn btn-default' type='submit'>Save Changes</button>
</div>
</div>
</form>
</div>

</body>
</html>
