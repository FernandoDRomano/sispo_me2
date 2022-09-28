<!DOCTYPE html>
<html>
	<head>
	    <title><?php echo $title; ?></title>
	    <?php 
	    	$this->view('template/backend/header');
	    	$this->view('template/backend/css');
	    	$this->view('template/backend/js');
	    ?>
	</head>
	<body style="background: #53725c;">
	    <?php echo $contenido_main; ?>		    
	</body>
</html>