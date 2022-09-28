<?php $this->view('backend/emails/_head'); ?>
  	<h1><?php echo sprintf(lang('email_activate_heading'), $identity);?></h1>
	<p><?php echo sprintf(lang('email_activate_subheading'), anchor('auth/activate/'. $id .'/'. $activation, lang('email_activate_link')));?></p>
<?php $this->view('backend/emails/_footer'); ?>
