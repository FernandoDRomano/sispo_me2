<div class="row">
	<div class="col-xs-3">
		<ul class="nav nav-tabs" role="tablist" id="myTab">
			<?php $i = 0; foreach ($padres as $f) {?>
				<li class="<?php if($i == 0){ echo 'active'; $i = 1;}?>"><a href="#tabAccesoDirecto_<?php echo $f->menu_id?>" role="tab" data-toggle="tab"><?php echo $f->descripcion?></a></li>
			<?php } ?>
		</ul>
	</div>
	<div class="col-xs-9">
		<div class="tab-content miTab">				  	
		  	<?php $i = 0; foreach ($padres as $f) {?>
				<div class="tab-pane <?php if($i == 0){ echo 'active'; $i = 1;}?>" id="tabAccesoDirecto_<?php echo $f->menu_id?>">
			  		<div class="row" style="padding: 30px;">
			  			<?php foreach ($hijos as $h) {?>
				  			<?php if($h->parent == $f->menu_id){?>
					  			<div class='col-xs-4'>
									<a href='<?php echo base_url().$h->link?>' class='btn btn-icon btn-block'>
										<i class='<?php echo $h->iconpath?> fa-3x'></i><p><?php echo $h->descripcion?></p>
									</a>
								</div>
							<?php } ?>
						<?php } ?>
			  		</div>
			  	</div>
			<?php } ?>  	
		</div>
	</div>
</div>

<style type="text/css">
.nav.nav-tabs li {
    width: 100%;
}

.nav-tabs {
    border-bottom: 0;
    border-right: 1px solid #ddd;
    padding-right: 5px;
}
</style>