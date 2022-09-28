<div class="middle-box text-center loginscreen  animated fadeInDown">
    <div>
        <br><br>
        <h1 class="logo-name"><img src="<?php echo base_url().'assets/public/logo.svg' ?>" class="img-responsive center-block"></h1>
        <br>
        <?php if ($this->session->flashdata('message')) print_r($this->session->flashdata('message')) ?>
        <?php echo form_open("backend/auth/login", 'class="m-t"');?>
            <div class="form-group">
                <input name="identity" id="identity" type="text" placeholder="Usuario" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Contrase&#241;a" class="form-control password" required>
            </div>
            <button class="btn btn-primary block full-width m-b" type="submit">Acceder</button>
        <?php echo form_close();?>
        <p class="m-t"> <small>&copy; 2022</small> </p>
    </div>
</div>