<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Port_model extends Crud_Model {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->table='port';
	}
	
	
	public function get_id($id) {
		
		$query = $this->db->get_where('port', array('id'=>$id) )->row();
		
		
	    if (isset($query->id) ) {
			
			return $query->id;
		}
		return false;
	}
	public function check($name) {
		//cek port
		
		if (!is_numeric($name)) {
			
			return TRUE;
		}
		return $this->db->where('name', $name)
						->limit(1)
						->count_all_results('port') > 0;
	}
	
}
