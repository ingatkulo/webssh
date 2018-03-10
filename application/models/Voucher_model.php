<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_model extends CI_Model {

	
	public function __construct() {
		parent::__construct();
		
	}
	
	public function create($data) {
		return $this->db->insert('voucher', $data);
	}
	public function read($id = '') {
		
	
		if(!empty($id))
		{
			$this->db->where('id', $id);
			
		}
		return $this->db->get('voucher');
	}
	public function read_unlock($id=null) {
		
	
		if(!empty($id))
		{
			$this->db->where('id', $id);
			
			
		}
		$this->db->where('active', true);
		return $this->db->get('voucher');
	}
	public function update($id, $data) {
		
		$this->db->where('id', $id);
		return $this->db->update('voucher', $data);
	}
	public function delete($id) {
		
		$this->db->where('id', $id);
		return $this->db->delete('voucher');
	}
}
