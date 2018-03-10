<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha_model extends CI_Model {
	
	public function __construct() {
		
		parent::__construct();
		
	}
	public function create($data) {
		
		$query = $this->db->insert_string('captcha', $data);
		$this->db->query($query);
	}
	public function check($captcha)
	{
		$expiration = time() - 30; // 5 second
		
		//$expiration = time();
		$this->db->where('captcha_time < ', $expiration)
			->delete('captcha');
			
			
		$sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
		$binds = array($captcha, $this->input->ip_address(), $expiration);
		$query = $this->db->query($sql, $binds);
		$row = $query->row();
		if ($row->count == 0)
		{
			return FALSE;
		}
		return TRUE;
	}
	
}
