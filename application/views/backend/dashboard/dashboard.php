<?php
	if ($this->ion_auth->logged_in()){
		$this->view('backend/dashboard/dashboard_estructura');
		$this->view('backend/dashboard/dashboard_js');
	}
	else redirect(base_url('backend/auth/login'), 'refresh');
?>