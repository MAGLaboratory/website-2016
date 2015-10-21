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

