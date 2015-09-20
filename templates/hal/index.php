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
  #activity_panel td { font-size: 18px; }
  #activity_panel th { font-size: 20px; }
  #activity_panel .panel-title { line-height: normal; font-size: 50px; }
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
<a class='navbar-brand' href='/hal'>HAL</a>
</div>
<div class='collapse navbar-collapse' id='main-nav'>
<ul class='nav navbar-nav'>
<li>
<a href='/'>
back to MAGLab
</a>
</li>
<li>
<a href='/hal/chart'>Chart</a>
</li>
</ul>
</div>
</div>
</nav>
<div class='container' style='margin-top: 100px;'>
<div class='row'>
<div class='col-md-12'>
<?php if($this->data->isOpen){ ?>
<div class='panel panel-success' id='activity_panel'>
<div class='panel-heading'>
<h1 class='panel-title'>
We are
<strong>OPEN</strong>
</h1>
</div>
<div class='panel-body'>
<table class='table-striped table-hover' style='width: 100%;'>
<thead>
<tr>
<th>Sensor</th>
<th>Status</th>
<th></th>
<th>Last Update</th>
</tr>
</thead>
<tbody>
<?php date_default_timezone_set('America/Los_Angeles'); ?>
<?php foreach($this->data->latestStatus as $sensor => $v){ ?>
<tr>
<td><?php echo $sensor; ?></td>
<td><?php echo $v[0]; ?></td>
<td>
<?php if($v[1]){ ?>
<time class='timeago' datetime='<?php echo date('c', $v[1]); ?>'></time>
<?php } ?>
</td>
<td><?php echo($v[1] ? date('M j, Y g:i A T', $v[1]) : 'NEVER'); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
<?php } else { ?>
<div class='panel panel-danger'>
<div class='panel-heading'>
<h1 class='panel-title'>
We are
<strong>CLOSED</strong>
</h1>
</div>
<div class='panel-body'>
<table class='table-striped table-hover' style='width: 100%;'>
<thead>
<tr>
<th>Sensor</th>
<th>Status</th>
<th></th>
<th>Last Update</th>
</tr>
</thead>
<tbody>
<?php date_default_timezone_set('America/Los_Angeles'); ?>
<?php foreach($this->data->latestStatus as $sensor => $v){ ?>
<tr>
<td><?php echo $sensor; ?></td>
<td><?php echo $v[0]; ?></td>
<td>
<?php if($v[1]){ ?>
<time class='timeago' datetime='<?php echo date('c', $v[1]); ?>'></time>
<?php } ?>
</td>
<td><?php echo($v[1] ? date('M j, Y g:i A T', $v[1]) : 'NEVER'); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

</div>
<? } ?>
</div>
</div>
</div>
<script src='/js/timeago.js'></script>
<script>
  $(function(){
    $('time.timeago').timeago();
  });
</script>

</body>
</html>
