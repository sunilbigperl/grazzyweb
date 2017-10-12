<?php
class Admin extends Admin_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		
		//load the admin language file in
		$this->lang->load('admin');
		
		$this->current_admin	= $this->session->userdata('admin');
	}

	function index()
	{
		
		$this->auth->check_access('Admin', true);
		
		$data['page_title']	= lang('admins');
		$data['admins']		= $this->auth->get_admin_list();

		$this->view($this->config->item('admin_folder').'/admins', $data);
	}
	function delete($id)
	{
		$this->auth->check_access('Admin', true);
		
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		if ($this->current_admin['id'] == $id)
		{
			$this->session->set_flashdata('message', lang('error_self_delete'));
			redirect($this->config->item('admin_folder').'/admin');	
		}
		
		//delete the user
		$this->auth->delete($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect($this->config->item('admin_folder').'/admin');
	}
	function form($id = false)
	{	
	
		if($this->auth->check_access('Restaurant manager', false)){
			$userdata = $this->session->userdata('admin');
			if($userdata['id'] != $this->uri->segment(4)){
				redirect($this->config->item('admin_folder').'/orders/dashboard');
			}
		}
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= lang('admin_form');
		
		//default values are empty if the customer is new
		$data['id']		= '';
		$data['firstname']	= '';
		$data['lastname']	= '';
		$data['email']		= '';
		$data['username']	= '';
		$data['phone']	= '';
		$data['access']		= '';
		$data['enabled']	='';
		$data['FromDate']	='';
		$data['ToDate']	='';
		
		if ($id)
		{	
			$this->admin_id		= $id;
			$admin			= $this->auth->get_admin($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$admin)
			{
				$this->session->set_flashdata('message', lang('admin_not_found'));
				redirect($this->config->item('admin_folder').'/admin');
			}
			//set values to db values
			$data['id']			= $admin->id;
			$data['firstname']	= $admin->firstname;
			$data['lastname']	= $admin->lastname;
			$data['email']		= $admin->email;
			$data['username']	= $admin->username;
			$data['phone']	= $admin->phone;
			$data['access']		= $admin->access;
			$data['enabled']		= $admin->enabled;
			$data['FromDate']		= $admin->FromDate;
			$data['ToDate']		= $admin->ToDate;
		}
		
		$this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|max_length[32]');
		$this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|max_length[32]');
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]');
		$this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[10]|callback_check_phone|regex_match[/^[0-9]{10}$/]');
		$this->form_validation->set_rules('username', 'lang:username', 'trim|required|max_length[128]|callback_check_username');
		$this->form_validation->set_rules('access', 'lang:access', 'trim|required');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
		if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id)
		{
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
			$this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]|sha1');
		}
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->view($this->config->item('admin_folder').'/admin_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['firstname']	= $this->input->post('firstname');
			$save['lastname']	= $this->input->post('lastname');
			$save['email']		= $this->input->post('email');
			$save['username']	= $this->input->post('username');
			$save['phone']	= $this->input->post('phone');
			$save['access']		= $this->input->post('access');
			$save['enabled']		= $this->input->post('enabled');
			$FromDate		= date('Y-m-d',strtotime($this->input->post('FromDate')));
			$save['FromDate'] = isset($FromDate) ? $FromDate : '';
			$ToDate		= date('Y-m-d',strtotime($this->input->post('ToDate')));;
			$save['ToDate'] = isset($ToDate) ? $ToDate : '';
			
			if ($this->input->post('password') != '' || !$id)
			{
				$save['password']	= $this->input->post('password');
			}
			
			$this->auth->save($save);
			
			$this->session->set_flashdata('message', lang('message_user_saved'));
			
			//go back to the customer list
			redirect($this->config->item('admin_folder').'/admin');
		}
	}
	
	
	function check_username($str)
	{
		$email = $this->auth->check_username($str, $this->admin_id);
		if ($email)
		{
			$this->form_validation->set_message('check_username', lang('error_username_taken'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	function check_phone($str)
	{
		$phone = $this->auth->check_phone($str, $this->admin_id);
		if ($phone)
		{
			$this->form_validation->set_message('check_phone', "This phone no already in use");
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}


	function addcity($id = false)
    {
        
        $this->Id  = $id;
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $data['orders'] = $this->Customer_model->get_city();
        $data['page_title']     = lang('category_form');
        
        //default values are empty if the customer is new
		
        $data['id']             = '';
        $data['city']           = '';

        
       
        
        if ($id)
        {   
		
            $pitstopcity    = $this->Customer_model->addcity($id);

            //if the category does not exist, redirect them to the category list with an error
            if (!$pitstopcity)
            {
                //$this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder').'/addcity');
            }
            
            
			
            $data['id']             = $pitstopcity->Id;
            $data['city']           = $pitstopcity->city;
            
			
            
        }
        
        $this->form_validation->set_rules('city', 'city', 'required|is_unique[pitstopcity.city]');
        
        if ($this->form_validation->run() == FALSE)
        {
            $this->view($this->config->item('admin_folder').'/city_form', $data);
        }
        else
        {
            
            
            $save['id']             = $id;
			
            $save['city']           = $this->input->post('city');
           
		
			
			
            $pitstopcity_id    = $this->Customer_model->savecity($save);
       
            $this->session->set_flashdata('message', 'city saved');
            
            //go back to the category list
            redirect($this->config->item('admin_folder').'/admin/addcity');
        }
    }

    function deletecity($id)
    {
        
        $category   = $this->Customer_model->get_city($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($category)
        {
            
            $this->Customer_model->deletecity($id);
            
            $this->session->set_flashdata('message', "The Cityname has been deleted.");
            redirect($this->config->item('admin_folder').'/admin/addcity');
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }

    
}