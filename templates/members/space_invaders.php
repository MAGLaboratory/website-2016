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
<?php if($this->data->current_user){ ?>
<li>
<a href='/members'>Me</a>
</li>
<?php if(isAdmin($this->data->current_user)){ ?>
<li>
<a href='/members/keyholders'>Keyholders</a>
</li>
<li>
<a href='/members/space_invaders'>Space Invaders</a>
</li>
<?php } ?>
<?php } ?>
</ul>
</div>
</div>
</nav>
<div class='container' id='main-container'>
<h1>Space Invaders</h1>
<?php date_default_timezone_set('America/Los_Angeles'); ?>
<table class='table table-hover'>
<thead>
<tr>
<th>#</th>
<th>Keycode</th>
<th>Person</th>
<th>Open/Denied At</th>
</tr>
</thead>
<tbody>
<?php foreach($this->data->invaders AS $invader){ ?>
<tr class='<?php echo filter_text($invader['open_at'] ? 'success' : 'warning', true); ?>'>
<td><?php echo filter_text($invader['id'], true); ?></td>
<td><?php echo filter_text($invader['keycode'], true); ?></td>
<td>
<?php if((int)$invader['keyholder_id'] == 0){ ?>
<button class='btn btn-info create-keyholder' data-keycode='<?php echo filter_text($invader['keycode'], true); ?>' data-target='#add-keyholder' data-toggle='modal' type='button'>Add</button>
<?php } ?>
<?php echo filter_text($invader['person'], true); ?>
</td>
<td>
<?php $open_msg = '?';
if($invader['open_at']){
  $open_msg = 'Opened ' . date('n/j/Y g:i:s a');
} else if($invader['denied_at']){
  $open_msg = 'Denied ' . date('n/j/Y g:i:s a');
}
 ?>
<?php echo filter_text($open_msg, true); ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php $keyholder_redirect = '/members/space_invaders'; ?>
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
<input class='form-control' id='person'>
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

</div>
<script>
  $(function(){
    $('#add-keyholder').on('show.bs.modal', function(e){
      var $button = $(e.relatedTarget);
      $('#keycode').val($button.data('keycode'));
    });
  });
</script>

</body>
</html>
