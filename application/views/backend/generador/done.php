<h5><?php echo 'URL de prueba: '. anchor(base_url().'index.php/'.$this->input->post('controller').'/'); ?></h5>

<h5>application/controllers/<?php echo $_POST['view'] ?>.php</h5>
<div style="border: #555 solid 2px; height: 200px; overflow:auto; padding:10px;">
<?php echo '<pre>'.htmlentities($controller).'</pre>'; ?>
</div>


<h5>application/views/<?php echo $_POST['view'] ?>_add.php</h5>
<div style="border: #555 solid 2px; height: 200px; overflow:auto; padding:10px;">
<?php echo '<pre>'.htmlentities($add_content).'</pre>'; ?>
</div>


<h5>application/views/<?php echo $_POST['view'] ?>_list.php</h5>
<div style="border: #555 solid 2px; height: 200px; overflow:auto; padding:10px;">
<?php echo '<pre>'.htmlentities($list_content).'</pre>'; ?>
</div>



<h5>application/views/<?php echo $_POST['view'] ?>_edit.php</h5>
<div style="border: #555 solid 2px; height: 200px; overflow:auto; padding:10px;">
<?php echo '<pre>'.htmlentities($edit_content).'</pre>'; ?>
</div>


<h5>application/views/<?php echo $_POST['view'] ?>_view.php</h5>
<div style="border: #555 solid 2px; height: 200px; overflow:auto; padding:10px;">
<?php echo '<pre>'.htmlentities($view_content).'</pre>'; ?>
</div>