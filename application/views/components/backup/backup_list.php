<div class="col-xs-12">
    <div class="ibox-content">
     <img src="/assets/public/underconstruction.jpg" alt=""> 
        <?php if($this->session->flashdata('registro')): ?>
            <div class="row">
              <div class="col-md-12">
                <div class="alert alert-success" style="margin: 10px 20px 30px 20px;">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  La liquidaci¨®n nro: <b><span class="barcodear"><?php echo $this->session->flashdata('registro') ?></span></b> fue guardada con exito.
                </div>
              </div>
            </div>
          <?php endif; ?>
         
        
    </div>
</div>
