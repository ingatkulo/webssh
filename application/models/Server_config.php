<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Server_config extends CI_Model {
	
	public function __construct() {
		
		parent::__construct();
		
	}
	
	public function read() {
		
		$this->db->where('id', '1');
		
		return $this->db->get('server_config');
		
	}
	public function update($data) {
		
		return $this->db->update('server_config', $data, array('id', 1));
		
	}
	
}
