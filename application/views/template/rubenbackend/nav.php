<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                	<div class="">
                        <?php if ($user_row->foto != "") { ?>
		                    <img src="<?php echo base_url().'uploads/users/'.$user_row->foto?>" class="img-circle img-responsive" style="" />
	                  	<?php }else{ ?>
		                    <img src="<?php echo base_url().'uploads/users/default.jpg'?>" class="img-circle img-responsive" style="" />
	                  	<?php } ?>
                    </div>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    	<span class="clear">
                    		<span class="block m-t-xs">
                    			<strong class="font-bold"><?php echo $user_row->nombre?>, <?php echo $user_row->apellido?></strong>
                     		</span>
                     		<span class="text-muted text-xs block"><?php echo $user_row->username?> <b class="caret"></b></span>
                     	</span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo base_url().'backend/dashboard/micuenta' ?>">Mi cuenta</a></li>
                        <li><a href="<?php echo base_url( "backend/auth/change_password" ) ?>">Cambiar Password</a></li>
                        <li class="divider"></li>
                        <li><a href="<?php echo base_url( "backend/auth/logout" ) ?>">Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element"><i class="fa fa-arrows-alt"></i></div>

            </li>
            <?php $this->backend_lib->getMenu($user_row->id); ?>
        </ul>

    </div>
</nav>

