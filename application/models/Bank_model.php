<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_model extends CI_Model {

	
	public function __construct() {
		parent::__construct();
		
	}
	
	public function create($data) {
		return $this->db->insert('bank', $data);
	}
	public function read($id = '') {
		
	
		if(!empty($id))
		{
			$this->db->where('id', $id);
			
		}
		return $this->db->get('bank');
	}
	public function update($id, $data) {
		
		$this->db->where('id', $id);
		return $this->db->update('bank', $data);
	}
	public function delete($id) {
		
		$this->db->where('id', $id);
		return $this->db->delete('bank');
	}
}
