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
<a class='navbar-brand' href='/hal'>HAL</a>
</div>
<div class='collapse navbar-collapse' id='main-nav'>
<ul class='nav navbar-nav'>
<li>
<a href='/'>
back to MAGLabs
</a>
</li>
<li>
<a href='/hal/chart'>Chart</a>
</li>
</ul>
</div>
</div>
</nav>
<div class='jumbotron' style='margin-top: 100px;'>
<div class='container' id='hal-graph' style='height:500px;'></div>
</div>
<script src='https://www.google.com/jsapi?autoload={%27modules%27:[{%27name%27:%27visualization%27,%27version%27:%271%27,%27packages%27:[%27timeline%27]}]}'></script>
<script>
  google.setOnLoadCallback(drawChart);
  
  function drawChart(){
    var chart = new google.visualization.Timeline($('#hal-graph')[0]);
    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn({type: 'string', id: 'Sensor'});
    dataTable.addColumn({type: 'string', id: 'Time'});
    dataTable.addColumn({type: 'date', id: 'Start'});
    dataTable.addColumn({type: 'date', id: 'End'});
    dataTable.addRows(<?php echo $this->data->graphJSON; ?>);
    
    var options = {
      timeline: {colorByRowLabel: true}
    };
    
    chart.draw(dataTable, options);
  };
</script>

</body>
</html>
