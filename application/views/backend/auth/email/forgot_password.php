<?php $this->view('backend/emails/_head'); ?>
  	<h1><?php echo sprintf(lang('email_forgot_password_heading'),$campo['identity']);?></h1>
	<p><?php echo sprintf(lang('email_forgot_password_subheading'), anchor('backend/auth/reset_password/'. $campo['forgotten_password_code'], lang('email_forgot_password_link')));?></p>
<?php $this->view('backend/emails/_footer'); ?>
