<?php 
    $this->view('template/backend/header');
    $this->view('template/backend/css');
    $this->view('template/backend/js');
    echo $contenido_main;
?>

<style type="text/css">
body {
  font-family: "open sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
  background-color: #FFFFFF;
  font-size: 13px;
  color: #676a6c;
  overflow-x: hidden;
}

body.boxed-layout {
    background: transparent;
}

table.dataTable, .table{
	font-size: 12px;
}
</style>