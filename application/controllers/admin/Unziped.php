<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unziped extends CI_Controller {
	
	
	
	public function __construct() {
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('unzip');
		$this->load->model('templates_model');
		
		
		function rrmdir($src) {
			$dir = opendir($src);
			
			while(false !== ( $file = readdir($dir)) ) {
				if (( $file != '.' ) && ( $file != '..' )) {
					$full = $src . '/' . $file;
					if ( is_dir($full) ) {
						rrmdir($full);
					}
					else {
						unlink($full);
					}
				}
			}
			closedir($dir);
			rmdir($src);
		}
	}
	
	public function upload() {
		
		if (! is_writable('./assets')) {
				
				show_error('chmod 777 assets');
		}
		
		if (! is_writable(APPPATH.'/views/public') ) {
				
			show_error('chmod 777 application/views/public');
				
		}
		
		
		$config['upload_path'] = APPPATH.'/views/public/';
		$config['allowed_types'] = 'zip';
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);
		
		
		if (! $this->upload->do_upload('zip_file')) {
			echo $this->upload->display_errors();
			return;
		}
		else {
			
			$data = (object) $this->upload->data();
			$file_name = str_replace('.zip','',$data->file_name);
			
			
			$view_folder = APPPATH.'/views/public/'.$file_name;
			
			$css_folder = './assets/'.$file_name;
			
			
			if (is_dir($view_folder)) {
				
				rrmdir($view_folder);
				
				
				//echo "dir removed";	
			}
			if (is_dir($css_folder)) {
				
				rrmdir($css_folder);
				
				//echo "css removed";	
			}
			
			if ( mkdir($view_folder, 0777)) {
				
				$this->unzip->extract($data->full_path, $view_folder);
				
			}
			
			
			rename($view_folder.'/assets', $css_folder);
			unlink($data->full_path);
			
			$this->templates_model->create(array('name'=>$file_name));
			
			$this->session->set_flashdata('theme', '<div class="alert alert-success">Themplate upload successfull</div>');
			redirect('admin/webssh.html', 'refresh');
			
		}
	}
	public function delete($id=FALSE) {
		
		
		$name = $this->templates_model->read($id)->row();
		if (!isset($name->id)) { show_404(); }
		
		
		$view_folder = APPPATH.'/views/public/'.$name->name;
		$css_folder = './assets/'.$name->name;
		
		if (is_dir($view_folder)) {
				
				rrmdir($view_folder);
				
		
		}
		if (is_dir($css_folder)) {
				
				rrmdir($css_folder);
				
				
		}
		$this->templates_model->delete($id);
		$this->session->set_flashdata('theme', '<div class="alert alert-succes">Themplate deleted successfull</div>');
		
		redirect('admin/webssh.html', 'refresh');
	}
}
