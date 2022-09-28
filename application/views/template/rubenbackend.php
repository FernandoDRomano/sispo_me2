<?php if ($this->ion_auth->logged_in()){ ?>
<!doctype html>
<html lang="en" class="no-js">
    
  <head>
    <title><?php echo $title; ?></title>
    <?php
        //$this->view('template/rubenbackend/Elementos');
        $this->view('template/rubenbackend/header');
        $this->view('template/rubenbackend/css');
        $this->view('template/rubenbackend/js');
        $user_row = $this->ion_auth->user()->row();
    ?>
  </head>
  <body class="skin-1">
      
	<div id="loading" name="loading" style="display:none">
		<b id="loadingText" style="color: white;text-shadow: 4px 4px 8px #000000;"></b>
	</div>
    <div id="wrapper">
        <?php include("rubenbackend/nav.php"); ?>
        <div id="page-wrapper" class="gray-bg dashbard-1">
          <?php include("rubenbackend/header_container.php"); ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                  <?php echo $contenido_main; ?>
                </div>
              </div>
            </div>
          </div>
          <?php include("rubenbackend/footer.php"); ?>
        </div>
    </div>
    <?php $this->view('template/rubenbackend/modal'); ?>
  </body>
</html>
<?php } ?>
