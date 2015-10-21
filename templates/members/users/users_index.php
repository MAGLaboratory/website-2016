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
<h1>Members / Users</h1>
<button class='btn btn-primary' data-target='#add-member' data-toggle='modal' type='button'>Invite Member</button>
<table class='table table-striped'>
<thead>
<tr>
<th>#</th>
<th>Role</th>
<th>Email</th>
<th>Name</th>
<th>Phone</th>
<th>Emergency</th>
<th>Joined</th>
<th>Left</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($this->data->members AS $member){ ?>
<tr>
<td><?php echo filter_text($member['id'], true); ?></td>
<td><?php echo filter_text($member['role'], true); ?></td>
<td><?php echo filter_text($member['email'], true); ?></td>
<td><?php echo filter_text($member['first_name'] . ' ' . $member['last_name'], true); ?></td>
<td><?php echo filter_text($member['main_phone'], true); ?></td>
<td><?php echo filter_text($member['emergency_phone'], true); ?></td>
<td><?php echo filter_text($member['joined_at'] ? date('F d, Y', $member['joined_at']) : ' ', true); ?></td>
<td>
<?php if(!$member['left_at']){ ?>
<button class='btn btn-default keyholder-end-now' data-member_id='<?php echo filter_text($member['id'], true); ?>' type='button'>Leave Now</button>
<?php } ?>
<?php echo filter_text($member['left_at'] ? date('F d, Y', $member['left_at']) : ' ', true); ?>
</td>
<td>
<button class='btn btn-default action-show'>Actions</button>
<p class='action-list hide'>
<a class='btn btn-default' href='#'>Edit</a>
<a class='btn btn-danger' href='#'>Disable</a>
<a class='btn btn-danger' href='#'>Verify</a>
</p>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<div aria-labelledby='addMemberLabel' class='modal fade' id='add-member' role='dialog' tabindex='-1'>
<div class='modal-dialog modal-lg'>
<div class='modal-content'>
<div class='modal-header'>
<button aria-label='Close' class='close' data-dismiss='modal' type='button'>
<span aria-hidden='true'>&times;</span>
</button>
<h4 class='modal-title' id='addKeyholderLabel'>Invite a member</h4>
</div>
<div class='modal-body'>
<form action='/members/users' method='post'>
<div class='form-group'>
<label for='email'>Email</label>
<input class='form-control' id='keycode' name='email' required type='email'>
</div>
<div class='form-group'>
<label for='first_name'>First Name</label>
<input class='form-control' id='first_name' name='first_name' type='text'>
</div>
<div class='form-group'>
<label for='last_name'>Last Name</label>
<input class='form-control' id='last_name' name='last_name' type='text'>
</div>
<div class='form-group'>
<div class='checkbox'>
<label>
<input name='roles[]' type='checkbox' value='Guest'>
Guest
</label>
</div>
<?php if(isAdmin($this->data->current_user)){ ?>
<div class='checkbox'>
<label>
<input name='roles[]' type='checkbox' value='Admin'>
Admin
</label>
</div>
<div class='checkbox'>
<label>
<input name='roles[]' type='checkbox' value='Keyholder'>
Keyholder
</label>
</div>
<div class='checkbox'>
<label>
<input name='roles[]' type='checkbox' value='General'>
General Member
</label>
</div>
<div class='checkbox'>
<label>
<input name='roles[]' type='checkbox' value='Backer'>
Backer/Donor
</label>
</div>
<?php } ?>
</div>
<div class='form-group'>
<label for='joined_at'>Joined at</label>
<input class='form-control' id='start_at' name='joined_at' type='datetime-local'>
<p class='help-block'>
Leave blank to set as right now.
<br>
For best results format like Year/Month/Day Hour:Minute eg: 2015/09/08 17:09 or 2015/01/27 5:39 pm
</p>
</div>
<div class='form-group'>
<button class='btn btn-primary' type='submit'>Submit</button>
</div>
</form>
</div>
</div>
</div>
</div>

<div class='hide'>
<form action='about:blank' id='end-now' method='post'>
<input name='_METHOD' type='hidden' value='PUT'>
<input name='left_now' type='hidden' value='1'>
</form>
</div>
</div>
<script>
  $(function(){
    $('.action-show').click(function(){
      var $list = $(this).next('.action-list');
      if($list.hasClass('hide')){
        $list.removeClass('hide');
      } else {
        $list.addClass('hide');
      }
    });
  
    $('.keyholder-end-now').click(function(){
      if(confirm("Are you sure the key has been returned?")){
        var $this = $(this);
        $('#end-now').attr('action', '/members/keyholders/'+$this.data('keyholder_id'));
        $('#end-now').submit();
      }
    });
  });
</script>

</body>
</html>
