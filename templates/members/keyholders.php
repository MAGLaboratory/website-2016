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
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php foreach($this->data->keyholders AS $keyholder){ ?>
<tr>
<td><?php echo $keyholder->id; ?></td>
<td><?php echo $keyholder->keycode; ?></td>
<td><?php echo $keyholder->person; ?></td>
<td><?php echo $keyholder->start_at ? date('F d, Y', $keyholder->start_at) : ' '; ?></td>
<td><?php echo $keyholder->end_at ? date('F d, Y', $keyholder->end_at) : ' '; ?></td>
<td>
Edit
End
</td>
</tr>
<?php } ?>
</tbody>
</table>
<div aria-labelledby='addKeyholderLabel' class='modal fade' id='add-keyholder' role='dialog' tabindex='-1'>
<div class='modal-dialog modal-lg'>
<div class='modal-content'>
<form action='/members/keyholders' method='post'>
<div class='form-group'>
<label for='person'>Person (Name)</label>
<input class='form-control' id='person'>
</div>
</form>
</div>
</div>
</div>
</div>

</body>
</html>
