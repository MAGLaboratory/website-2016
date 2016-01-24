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
<?php if(isset($createdWikiUser) and $createdWikiUser == 1){ ?>
<h1>Create Wiki User</h1>
<div class='alert alert-success'>
<strong>Created Wiki Account!</strong>
<p>
You can now login to your account at the
<strong>
<a href='/wiki'>wiki</a>
</strong>
</p>
</div>
<?php } elseif(empty($user->wikiusername)){ ?>
<h1>Create Wiki User</h1>
<p>
Due to spam bots, we have to restrict wiki registration to members and guests only.
Create your wiki account using the form below. It will be permanently tied to this maglab account.
If you have guests that want wiki access, give them an invite so they can create their own.
</p>
<p>
Remember: Stuff you put on the wiki will be public. And will be marked under the wiki username. This is separate from the profile information below.
</p>
<?php if(isset($createdWikiUser) and $createdWikiUser == 0){ ?>
<div class='alert alert-warning'>
<strong>Failed to create account.</strong>
Sorry, something went wrong and we couldn't create the account. Try again later?
</div>
<?php } ?>
<form action='/w/createWikiUser.php' autocomplete='off' class='form-horizontal' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='wiki_email'>Email</label>
<div class='col-sm-10'>
<input class='form-control' id='wiki-email' name='wiki_email' type='email' value='<?php echo filter_email($user->email, true); ?>'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='wiki_username'>Username</label>
<div class='col-sm-10'>
<input autocomplete='off' class='form-control' id='wiki-username' name='wiki_username' placeholder='SirWeldAlot' type='text' value=''>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='wiki_password'>Password</label>
<div class='col-sm-10'>
<input autocomplete='off' class='form-control' id='wiki-password' name='wiki_password' placeholder='p00pscooper' type='password' value=''>
</div>
</div>
<div class='form-group'>
<div class='col-sm-10 col-sm-offset-2'>
<button class='btn btn-primary' type='submit'>Create Wiki Account</button>
</div>
</div>
<script>
  $(function(){
    setTimeout(function(){
      $('#wiki-password,#wiki-username').val('');
    }, 700);
  });
</script>
</form>
<?php } ?>
<h1>Profile</h1>
<p>The profile information below is for recordkeeping and maglab usage only. And will be kept mostly private. (This is separate from public information on the wiki.) The emergency contact is kinda important...</p>
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
<label class='col-sm-2 control-label'>Wiki Username</label>
<div class='col-sm-10'>
<?php if(empty($user->wikiusername)){ ?>
<i>Not yet created.</i>
<?php } else { ?>
<strong><?php echo filter_text($user->wikiusername, true); ?></strong>
<?php } ?>
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
</div>

</body>
</html>
