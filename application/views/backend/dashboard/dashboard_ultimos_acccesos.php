<?php foreach ($acessos as $f) {?>
	<div class="feed-element"><div class="media-body "><small class="pull-right"><?php echo $f->time?></small><strong><?php echo $f->ip_address?></strong></div></div>
<?php } ?>
