<?php $this->view('backend/emails/_head'); ?>
  	<h1><?php echo sprintf(lang('email_new_password_heading'), $identity);?></h1>
	<p><?php echo sprintf(lang('email_new_password_subheading'), $new_password);?></p>
<?php $this->view('backend/emails/_footer'); ?>
