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
<div class='container-fluid' id='main-container'>
<form action='/members/procurement' class='form-horizontal' id='procurement-form' method='post'>
<div class='row'>
<div class='col-sm-5'>
<div class='form-group'>
<label class='col-sm-2' for='name'>Name</label>
<div class='col-sm-10'>
<input class='form-control' id='name' name='name' placeholder='Beer!' type='text'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2' for='category'>
Category
</label>
<div class='col-sm-10'>
<input class='form-control' id='category' name='category' placeholder='Groceries' type='text'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2' for='need_amount'>Need</label>
<div class='col-sm-4'>
<input class='form-control' id='need_amount' name='need_amount' placeholder='9009' type='number'>
</div>
<label class='col-sm-2' for='have_amount'>Have</label>
<div class='col-sm-4'>
<input class='form-control' id='have_amount' name='have_amount' placeholder='0' type='number'>
</div>
</div>
<div class='form-group'>
<label class='col-sm-2' for='cost'>Cost</label>
<div class='col-sm-10'>
<input class='form-control' id='estimated_cost' name='cost' placeholder='estimated: $9.99 - $1374 per barrel' type='text'>
</div>
</div>
</div>
<div class='col-sm-7'>
<div class='form-group'>
<div class='col-sm-12'>
<textarea class='form-control' id='description' name='description' placeholder='Optional Description: eg: Beer Pong! *Please do not drink and operate heavy machinery.' rows='4'></textarea>
</div>
</div>
<div class='form-group'>
<div class='col-sm-12'>
<button class='btn btn-primary' type='submit'>Add Item</button>
</div>
</div>
</div>
</div>
<?php if(isset($createdItem)){ ?>
<?php   if($createdItem){ ?>
<div class='alert alert-success'>
<strong>Success!</strong>
</div>
<?php   } else {  ?>
<div class='alert alert-danger'>
<strong>Failed!</strong>
I don't know what happened, but try again maybe?
</div>
<?php   } ?>
<?php } ?>
</form>
<table class='table table-striped'>
<thead>
<tr>
<th>Category</th>
<th>Name</th>
<th>Need</th>
<th>Have</th>
<th>Cost</th>
<th>Actions</th>
</tr>
</thead>
<tbody id='procurement-list'>
<?php foreach($items as $item){ ?>
<?php if(isset($item)){ ?>
<tr data-id='<?php echo filter_text($item['id'], true); ?>'>
<td><?php echo filter_text($item['category'], true); ?></td>
<td>
<?php echo filter_text($item['name'], true); ?>
<br>
<?php echo $controller->purify($item['description']); ?>
</td>
<td><?php echo filter_text($item['need_amount'], true); ?></td>
<td><?php echo filter_text($item['have_amount'], true); ?></td>
<td><?php echo filter_text($item['cost'], true); ?></td>
<td>
<button class='got-1 btn btn-primary' type='button'>Got +1</button>
<button class='lost-1 btn btn-warning' type='button'>Lost -1</button>
<button class='need-1 btn btn-default' type='button'>Need +1</button>
<button class='skip-1 btn btn-default' type='button'>Need -1</button>
<br>
<button class='remove-item btn btn-danger' type='button'>Remove</button>
</td>
</tr>
<?php } ?>

<?php } ?>
</tbody>
</table>
</div>
<script>
  $(function(){
    function getTr(x){
      var tr = $(x).parents('tr');
      tr.find('button').prop('disabled', true);
      return tr;
    }
   
    $('#procurement-list').on('click', '.got-1', function(){
      var $this = getTr(this);
      $.post('/members/procurement/' + $this.data('id'), {'got': 1, '_METHOD': 'PATCH'}, function(itm_html){
        $this.replaceWith(itm_html);
      }, 'html');
    });
    
    $('#procurement-list').on('click', '.lost-1', function(){
      var $this = getTr(this);
      $.post('/members/procurement/' + $this.data('id'), {'lost': 1, '_METHOD': 'PATCH'}, function(itm_html){
        $this.replaceWith(itm_html);
      }, 'html');
    });
    
    $('#procurement-list').on('click', '.need-1', function(){
      var $this = getTr(this);
      $.post('/members/procurement/' + $this.data('id'), {'need': 1, '_METHOD': 'PATCH'}, function(itm_html){
        $this.replaceWith(itm_html);
      }, 'html');
    });
    
    $('#procurement-list').on('click', '.skip-1', function(){
      var $this = getTr(this);
      $.post('/members/procurement/' + $this.data('id'), {'skip': 1, '_METHOD': 'PATCH'}, function(itm_html){
        $this.replaceWith(itm_html);
      }, 'html');
    });
    
    $('#procurement-list').on('click', '.remove-item', function(){
      var $this = getTr(this);
      $.post('/members/procurement/' + $this.data('id'), {'got': 1, '_METHOD': 'DELETE'}, function(itm_html){
        $this.replaceWith(itm_html);
      }, 'html');
    });
  });
</script>

</body>
</html>
