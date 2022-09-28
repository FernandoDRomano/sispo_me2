<div class="row border-bottom">
  <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
      <div class="navbar-header">
          <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
      </div>
      <ul class="nav navbar-top-links navbar-right">
          <li><span class="m-r-sm text-muted welcome-message">Panel de control</span></li>
          <li><a href="<?php echo base_url( "backend/auth/logout" ) ?>">Logout</a></li>
      </ul>
  </nav>
</div>

<div class="row  border-bottom white-bg dashboard-header">
    <div class="col-xs-12">
      <h2><?php echo $title; ?></h2>
      <?php include("breadcrumb.php"); ?>
    </div>
    <div class="col-xs-12">

    </div>
    <div class="col-xs-12">

    </div>
</div>