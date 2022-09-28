<?php if ($this->ion_auth->logged_in()){ ?>
<!doctype html>
<html lang="en" class="no-js">
  <head>
    <title><?php echo $title; ?></title>
    <?php
        $this->view('template/backend/header');
        $this->view('template/backend/css');
        $this->view('template/backend/js');
        $user_row = $this->ion_auth->user()->row();
    ?>
  </head>
  <body class="skin-1">
    <div id="wrapper">
        <?php include("backend/nav.php"); ?>
        <div id="page-wrapper" class="gray-bg dashbard-1">
          <?php include("backend/header_container.php"); ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row">
                  <?php echo $contenido_main; ?>
                </div>
              </div>
            </div>
          </div>
          <?php include("backend/footer.php"); ?>
        </div>
    </div>
    <?php $this->view('template/backend/modal'); ?>
  </body>
</html>
<?php } ?>
