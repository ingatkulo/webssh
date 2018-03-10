<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {
	

	function __construct()
	{
		parent::__construct();

	}
	
	function index() {
		
		$this->data['jumlah_user'] = count($this->ion_auth->users()->result()) - 1;
		$this->data['jumlah_server'] = $this->web_ssh->servers()->num_rows();
		
		$this->template->admin_render('admin/dashboard', $this->data);
	}
}
