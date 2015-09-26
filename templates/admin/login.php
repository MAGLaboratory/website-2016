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
<?php if($this->data->user){ ?>
<li>
<a href='/admin/keyholders'>Keyholders</a>
</li>
<li>
<a href='/admin/spaceinvaders'>Space Invaders</a>
</li>
<?php } ?>
</ul>
</div>
</div>
</nav>
<div class='container' style='margin-top: 75px;'>
<form action='/admin/login' class='form-horizontal' method='post'>
<div class='form-group'>
<label class='col-sm-2 control-label' for='email'>Email</label>
<div class='col-sm-10'>
<input class='form-control' id='email' name='email' placeholder='you@maglaboratory.org' type='email'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2 control-label' for='password'>Password</label>
<div class='col-sm-10'>
<input class='form-control' id='password' name='password' placeholder='***' type='password'>
</div>
</div>
<div class='form-group'>
<div class='col-sm-offset-2 col-sm-10'>
<button class='btn btn-default' type='submit'>Login</button>
</div>
</div>
</form>
</div>

</body>
</html>
