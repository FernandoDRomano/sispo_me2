<div class="row">
  	<div class="col-xs-6">
    	<div class="panel panel-info">
      		<div class="panel-heading">
        		<div class="row">
          			<div class="col-xs-6">
            			<i class="fa fa-file-o fa-5x"></i>
          			</div>
          			<div class="col-xs-6 text-right">
            			<p class="announcement-heading"><?php echo $notas->cantidad ?></p>
            			<p class="announcement-text">Notas</p>
          			</div>
        		</div>
      		</div>
      		<a href="<?php echo base_url().'notas'?>">
        		<div class="panel-footer announcement-bottom">
          			<div class="row">
            			<div class="col-xs-6">Ingresar</div>
            			<div class="col-xs-6 text-right">
              				<i class="fa fa-arrow-circle-right"></i>
            			</div>
          			</div>
        		</div>
      		</a>
    	</div>
  	</div>
    <div class="col-xs-6">
      <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                  <i class="fa fa-check-square-o fa-5x"></i>
                </div>
                <div class="col-xs-6 text-right">
                  <p class="announcement-heading"><?php echo $tareas->cantidad ?></p>
                  <p class="announcement-text">Tareas</p>
                </div>
            </div>
          </div>
          <a href="<?php echo base_url().'tareas'?>">
            <div class="panel-footer announcement-bottom">
                <div class="row">
                  <div class="col-xs-6">Ingresar</div>
                  <div class="col-xs-6 text-right">
                      <i class="fa fa-arrow-circle-right"></i>
                  </div>
                </div>
            </div>
          </a>
      </div>
    </div>
</div>