<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Server_model extends Crud_Model {
	
	public function __construct() {
		
		parent::__construct();
		
		$this->table='server';
	}
	
}
