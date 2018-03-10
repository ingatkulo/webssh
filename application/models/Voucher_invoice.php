<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Voucher_invoice extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function create($voucher_invoice) {
		
		$this->db->insert('voucher_invoice', $voucher_invoice);
		return $this->db->insert_id();
	}
	function pagination($config){
		$this->db->order_by("id","desc");
        return $this->db->get('voucher_invoice', $config['per_page'], $this->uri->segment($config['uri_segment']));
           
    }
	public function read($id=false) {
		
		if ($id) {
			
			$query = $this->db->get_where('voucher_invoice', array('id' => $id));
			
			return $query;
		}
		else
		
		{
			return $this->db->get('voucher_invoice');
		
		}
		
	}
	public function read_keranjang($id) {
		
		return $this->db->get_where('voucher_invoice', array('keranjang_id' => $id));
		
	}
	public function belum_dibaca() {
		return $this->db->get_where('voucher_invoice', array('dibaca'=>false));
	}
	public function update($id) {
		
		$this->db->where('id', $id);
		return $this->db->update('voucher_invoice', array('dibaca' => true));
	}
	public function check($id = '')
	{
		

		if (empty($id))
		{
			return FALSE;
		}

		return $this->db->where('keranjang_id', $id)
						->limit(1)
						->count_all_results('voucher_invoice') > 0;
	}
	public function delete($id) {}
	
	
}
