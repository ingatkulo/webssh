<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Member_Controller {
	

	function __construct()
	{
		parent::__construct();
		$this->load->model('server_created');
	}
	
	function index() {
		
		$name = $this->data['user']->username;
		
		$this->data['total_server'] = $this->web_ssh->servers()->num_rows();
		$this->data['total_akun'] = $this->server_created->getUserCreated($name)->num_rows();
		$this->template->member_render('member/dashboard', $this->data);
	}
}
