<?php

class Login extends Base_Controller {

	function __construct()
	{
		parent::__construct();
		$this->lang->load('login');
		$this->load->model('Customer_model');
	}

	function index()
	{

		//we check if they are logged in, generally this would be done in the constructor, but we want to allow customers to log out still
		//or still be able to either retrieve their password or anything else this controller may be extended to do
		$redirect	= $this->auth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			//redirect($this->config->item('admin_folder').'/customers');
		}
		
		
		$this->load->helper('form');
		$data['redirect']	= $this->session->flashdata('redirect');
		$submitted 			= $this->input->post('submitted');
		if ($submitted)
		{
			$username	= $this->input->post('username');
			$password	= $this->input->post('password');
			$remember   = $this->input->post('remember');
			$redirect	= $this->input->post('redirect');
			$login		= $this->auth->login_admin($username, $password, $remember);
			
			if ($login)
			{
				if ($redirect == '')
				{
					$userdata = $this->session->userdata('admin');
					if($this->auth->check_access('Restaurant manager')){
						
						$date = date('Y-m-d');
						$sql = $this->db->query("select * from admin where NextRenewalDate > '".$date."' and username='".$username."'");
						//print_r("select * from admin where NextRenewalDate > '".$date."' and username='".$username."'"); exit;
						if($sql->num_rows() > 0){
							$redirect = $this->config->item('admin_folder').'/orders/dashboard';
						}else{
							$this->auth->logout();
							$this->session->set_flashdata('error', 'Your renewal date expired');
							redirect($this->config->item('admin_folder').'/login');
						}
						
					}elseif($this->auth->check_access('Admin')){
						$redirect = $this->config->item('admin_folder').'/dashboard';
					}else{
						
						$date = date('Y-m-d');
						
						$sql = $this->db->query("select * from admin where NextRenewalDate > '".$date."' and username='".$username."'");
						
						if($sql->num_rows() > 0){
							$redirect = $this->config->item('admin_folder').'/orders/delpartnerorders';
						}else{
							$this->auth->logout();
							$this->session->set_flashdata('error', 'Your renewal date expired');
							redirect($this->config->item('admin_folder').'/login');
						}
						
					}
				}
				redirect($redirect);
			}
			else
			{
				//this adds the redirect back to flash data if they provide an incorrect credentials
				$this->session->set_flashdata('redirect', $redirect);
				$this->session->set_flashdata('error', lang('error_authentication_failed'));
				redirect($this->config->item('admin_folder').'/login');
			}
		}
		$this->load->view($this->config->item('admin_folder').'/login', $data);
	}
	
	function logout()
	{
		$this->auth->logout();
		
		//when someone logs out, automatically redirect them to the login page.
		$this->session->set_flashdata('message', lang('message_logged_out'));
		redirect($this->config->item('admin_folder').'/login');
	}

	function forgot_password()
	{
		$submitted = $this->input->post('submitted');
		if ($submitted)
		{
			$this->load->helper('string');
			$email = $this->input->post('email');
			
			$reset = $this->Customer_model->reset_password($email);
			
			if ($reset)
			{						
				$this->session->set_flashdata('message', "Password reset success. New password sent to your mail");
			}
			else
			{
				$this->session->set_flashdata('error',"Mail id is not registered");
			}
			redirect('login/forgot_password');
		}
		
		// load other page content 
		//$this->load->model('banner_model');
		$this->load->helper('directory');
	
		//if they want to limit to the top 5 banners and use the enable/disable on dates, add true to the get_banners function
		//$data['banners']	= $this->banner_model->get_banners();
		//$data['ads']		= $this->banner_model->get_banners(true);
		$data['categories']	= $this->Category_model->get_categories_tiered();
		
		
		$this->load->view($this->config->item('admin_folder').'/forgot_password', $data);
	}
}
