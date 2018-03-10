<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class History extends Member_Controller {
	

	function __construct()
	{
		parent::__construct();
		
		
		$this->load->model(array (
			'location_model',
			'continent_model',
			'port_model',
			'service_model',
			'server_config',
			'server_created')
		);

	}
	
	function index() {
		
		
		$name = $this->data['user']->username;
		
		$this->load->library('pagination');
		
		$config['base_url'] = site_url('member/history/index');
		$config['total_rows'] = $this->server_created->getUserCreated($name)->num_rows();
		$config['per_page']=4;
        $config['num_links'] = 2;
        
        $config['uri_segment']=4;
		
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#list'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
 
        $config['first_link']='Pertama ';
        $config['last_link']='Terakhir';
        $config['next_link']='<i class="fa fa-angle-right"></i>';
        $config['prev_link']='<i class="fa fa-angle-left"></i>';
       // $config['created_by'] = $name;
		$this->pagination->initialize($config);
		$this->data['link'] = $this->pagination->create_links();
		$this->data['config']=$this->uri->segment(4);
		
		
		$this->data['ini'] = $config;
		$this->data['total'] = $this->server_created->getHasilUang($name);
		$this->data['servers_created'] = $this->server_created->getUserCreated($name, $config)->result();
		foreach ($this->data['servers_created'] as $k=>$value) {
			
			$host = $this->web_ssh->server($value->server_id)->row();
			if(!isset($host->id)) {
				$this->data['servers_created'][$k]->server='<strike>Deleted</strike>';
			}
			else {
				$this->data['servers_created'][$k]->server=$host->host;
			}
		}
		$this->template->member_render('member/history/index', $this->data);
		
	}
	
}
