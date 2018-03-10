<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Freessh_model extends CI_Model {
	
	public function __construct() {
		
		parent::__construct();
	
	}
	public function countServerCreated($id) {
		
		$date = new DateTime(date('d-m-Y'));
		$date->format('U');
		
		$now = $date->getTimeStamp();
		
		$this->db->where('server_id', $id);
		$this->db->where('now', $now);
		
		
		$server = $this->db->get('free_ssh')->result();
		return count($server);
		
	}
	public function create($id) {
		
		$date = new DateTime(date('d-m-Y'));
		$date->format('U');
		$now = $date->getTimeStamp();
		
		$data = array('server_id'=> $id, 'now'=> $now);
		
		$this->db->insert('free_ssh', $data);
		
	}
	
}
