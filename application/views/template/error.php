<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <title><?php echo $title; ?></title>
    <?php 
      include("backend/header.php");
      include("backend/css.php");
      include("backend/js.php");
    ?>
  </head>
  <body class="skin-3">
    <div class="middle-box text-center animated fadeInDown" style="color: white">
        <?php echo $contenido_main; ?>
    </div>  
  </body>
</html>