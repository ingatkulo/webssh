<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Auth
 * @property Ion_auth|Ion_auth_model $ion_auth        The ION Auth spark
 * @property CI_Form_validation      $form_validation The form validation library
 */
class MY_Controller extends CI_Controller
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model(
			array(
				'voucher_model',
				'voucher_invoice',
				'keranjang_model',
				'bank_model',
				'phone_model',
				'web_ssh',
				'server_config'
			));
		$this->load->library(array('ion_auth', 'template', 'form_validation'));
		$this->load->helper(array('url', 'language'));

		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

		$this->lang->load('auth');
		$this->config->load('ar_panel', TRUE);
		$this->data['version'] = $this->config->item('version', 'ar_panel');
		
		
	}
	public function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	/**
	 * @return bool Whether the posted CSRF token matches
	 */
	public function _valid_csrf_nonce()
	{
		$csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
		if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

}
class Admin_Controller extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('login.html', 'refresh');
        }
        $id = $this->ion_auth->get_user_id();
		$this->data['user'] = $this->ion_auth->user($id)->row();
		$this->data['jumlah_invoice'] = count($this->voucher_invoice->belum_dibaca()->result());
		
        
    }
}
class Member_Controller extends MY_Controller
{
	
	
	public function __construct()
	{
		parent::__construct();
		
		
        if ( ! $this->ion_auth->logged_in() )
        {
            redirect('login.html', 'refresh');
        }
        if ($this->ion_auth->is_admin()) {
			redirect('admin', 'refresh');
		}
		
        $this->data['message'] = $this->session->flashdata('message');
        $id = $this->ion_auth->get_user_id();
        
        $user = $this->ion_auth->user($id)->row();
        
        $this->data['user'] = $user;
        
        //$keranajang = $this->keranjang_model()->read()->result();
        $blm_dibayar = false;
        $keranjang = $this->keranjang_model->read_user_keranjang($id, $blm_dibayar)->result();
        $jumlah = count($keranjang);
        
        foreach ($keranjang as $ker) {
			
			if ($this->voucher_invoice->check($ker->id)) {
					$jumlah -= 1;
			}
		}
        
        
        $this->data['jumlah_keranjang'] = $jumlah;

		/*Error Type*/
		
		
		if ($user->phone == null && $user->first_name == null && $user->last_name == null) {
			
			$this->set_message('warning','phone', 'Profile Anda belum lengkap');
			return;
			
		}
		if (strpos($user->username, '-') !== FALSE) {
			$this->set_message('warning','user_id', 'User ID Anda belum dilengkapi<br>Pastikan sudah diganti');
			return;
			
		}
		if ($saldo <= 5000) {
			$this->set_message('warning','saldo', 'Warning !!!. Saldo Anda '. $saldo);
			return;
		}
	


        
    }
    private function set_message($type,$data, $message)
	{
		
		$this->data['alert'] = array (
			'type' => $type,
			'data' => $data,
			'message' => $message);
		
	}
    
    
}

class Public_Controller extends MY_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		
		
		$this->data['ipAddr'] = $this->input->ip_address();
		
		$this->data['time'] = date('H:i:s d/m/Y', time() );
		
        if ($this->ion_auth->logged_in())
        {
			
			$this->data['login'] = TRUE;		
			$id = $this->ion_auth->get_user_id();
			
			$this->data['user'] = $this->ion_auth->user($id)->row();
        }
        else
        {
			$this->data['login'] = FALSE;
        }
	}
	public function public_render($content, $data) {
		
		$config = $this->server_config->read()->row();
		
		$template = $config->template;
		
		
		$this->template->theme = $template;
		$data['template'] = $template;
		
		
		
		$this->template->public_render('public/'.$template.'/'.$content, $data);
	}
	public function success_render($content, $data) {
		
		$config = $this->server_config->read()->row();
		
		$template = $config->template;
		
		
		$this->template->theme = $template;
		$data['template'] = $template;
		
		
		
		return $this->template->success_render('public/'.$template.'/'.$content, $data);
	}
	
}
