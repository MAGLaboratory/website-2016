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
