<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Public_Controller {

	public function index()

	{
	
		//$por = $this->web_ssh->get_service_ports(1,4)->result();
		$por=$this->web_ssh->get_server_services(5)->result();
		foreach ($por as $por) {
			echo $por->name;
		}
		
	}
}
