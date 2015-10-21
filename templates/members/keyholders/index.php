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
<h1>Key Holders</h1>
<button class='btn btn-primary' data-target='#add-keyholder' data-toggle='modal' type='button'>Add Keyholder</button>
<table class='table table-striped'>
<thead>
<tr>
<th>#</th>
<th>Keycode</th>
<th>Person</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody>
<?php foreach($this->data->keyholders AS $keyholder){ ?>
<tr>
<td><?php echo filter_text($keyholder['id'], true); ?></td>
<td><?php echo filter_text($keyholder['keycode'], true); ?></td>
<td><?php echo filter_text($keyholder['person'], true); ?></td>
<td><?php echo filter_text($keyholder['start_at'] ? date('F d, Y', $keyholder['start_at']) : ' ', true); ?></td>
<td>
<?php if(!$keyholder['end_at']){ ?>
<button class='btn btn-default keyholder-end-now' data-keyholder_id='<?php echo filter_text($keyholder['id'], true); ?>' type='button'>End Now</button>
<?php } ?>
<?php echo filter_text($keyholder['end_at'] ? date('F d, Y', $keyholder['end_at']) : ' ', true); ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php $keyholder_redirect = '/members/keyholders'; ?>
<div aria-labelledby='addKeyholderLabel' class='modal fade' id='add-keyholder' role='dialog' tabindex='-1'>
<div class='modal-dialog modal-lg'>
<div class='modal-content'>
<div class='modal-header'>
<button aria-label='Close' class='close' data-dismiss='modal' type='button'>
<span aria-hidden='true'>&times;</span>
</button>
<h4 class='modal-title' id='addKeyholderLabel'>Add a keyholder</h4>
</div>
<div class='modal-body'>
<form action='/members/keyholders' method='post'>
<div class='form-group'>
<label for='keycode'>Keycode</label>
<input class='form-control' id='keycode' name='keycode' type='number'>
</div>
<div class='form-group'>
<label for='person'>Person (Name)</label>
<input class='form-control' id='person' name='person' type='text'>
</div>
<div class='form-group'>
<label for='start_at'>Start at</label>
<input class='form-control' id='start_at' name='start_at' type='datetime-local'>
<p class='help-block'>
The day they got the key. Leave blank to default to today.
<br>
For best results format like Year/Month/Day Hour:Minute eg: 2015/09/08 17:09 or 2015/01/27 5:39 pm
</p>
</div>
<div class='form-group'>
<label for='end_at'>End at</label>
<input class='form-control' id='end_at' name='end_at' type='datetime-local'>
<p class='help-block'>
The day they stopped being a keyholder (gave key back). Leave blank to default to not yet.
<br>
For best results format like Year/Month/Day Hour:Minute eg: 2015/09/08 17:09 or 2015/01/27 5:39 pm
</p>
</div>
<div class='form-group'>
<input name='back' type='hidden' value='<?php echo filter_text($keyholder_redirect, true); ?>'>
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
<input name='end_now' type='hidden' value='1'>
</form>
</div>
</div>
<script>
  $(function(){
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
