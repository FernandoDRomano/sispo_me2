<?php
defined('BASEPATH') or exit('No direct script access allowed');

// 20200117 2355 : agrego |--

class MY_Session extends CI_Session{

	public function __construct() {
		parent::__construct();
	}

	function sess_destroy() {

		//$data = array(
		//	'ip_address' => (string) ''
		//);
		//$this->load->database();
		//$this->db->update($this->tables['users'], $data, array('id' => $this->session->userdata('user_id')));

		//call the parent
		parent::sess_destroy();
	}

}

// --|