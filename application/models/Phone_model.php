<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Phone_model extends CI_Model {

	
	public function __construct() {
		parent::__construct();
		
	}
	
	public function create($data) {
		return $this->db->insert('phone', $data);
	}
	public function read($id = '') {
		
	
		if(!empty($id))
		{
			$this->db->where('id', $id);
			
		}
		return $this->db->get('phone');
	}
	public function update($id, $data) {
		
		$this->db->where('id', $id);
		return $this->db->update('phone', $data);
	}
	public function delete($id) {
		
		$this->db->where('id', $id);
		return $this->db->delete('phone');
	}
}
