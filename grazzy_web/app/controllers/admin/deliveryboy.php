<?php
class Deliveryboy extends Admin_Controller
{
	
	function __construct()
	{
		parent::__construct();

		$this->auth->check_access('Admin', true);
		$this->load->model('Deliveryboy_model');
		$this->lang->load('page');
	}
		
	function index()
	{
		$data['pages']		= $this->Deliveryboy_model->get_lists();
		$this->view($this->config->item('admin_folder').'/deliveryboy', $data);
	}
	
	
	
	/********************************************************************
	edit page
	********************************************************************/
	function form($id = false)
	{
		
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		
		//set the default values
		$data['id']			= '';
		$data['name']		= '';
		$data['address']	= '';
		$data['phone']		= '';
		$data['email']	= '';
		$data['enabled'] = '';
		
	
		$data['deliveryboys']		= $this->Deliveryboy_model->get_lists();
		
		if($id)
		{
			
			$page			= $this->Deliveryboy_model->get_deliveryboys($id);
			$page = $page[1];
			if(!$page)
			{
				//page does not exist
				$this->session->set_flashdata('error', lang('error_page_not_found'));
				redirect($this->config->item('admin_folder').'/deliveryboy');
			}
			
			
			//set values to db values
			$data['id']			= $page->id;
			$data['name']		= $page->name;
			$data['address']			= $page->address;
			$data['phone']		= $page->phone;
			$data['email']		= $page->email;
			$data['enabled']	=   $page->enabled;;
			
		}
		
		 $this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[64]');
	
		// Validate the form
		if($this->form_validation->run() == false)
		{
			
			$this->view($this->config->item('admin_folder').'/deliveryboy_form', $data);
		}
		else
		{
			
			
			$save = array();
			$save['id']			= $id;
			$save['name']	= $this->input->post('name');
			$save['address']		= $this->input->post('address');
			$save['phone']	= $this->input->post('phone'); 
			$save['email']	= $this->input->post('email');
			$save['enabled']        = $this->input->post('enabled');
			
			//save the page
			$page_id	= $this->Deliveryboy_model->save($save);
			
			
			redirect($this->config->item('admin_folder').'/deliveryboy');
		}
	}
	
	
	
	/********************************************************************
	delete page
	********************************************************************/
	function delete($id)
	{
		
		$page	= $this->Number_model->get_page($id);
		
		if($page)
		{
			$this->load->model('Routes_model');
			
			$this->Routes_model->delete($page->route_id);
			$this->Number_model->delete_page($id);
			$this->session->set_flashdata('message', lang('message_deleted_page'));
		}
		else
		{
			$this->session->set_flashdata('error', lang('error_page_not_found'));
		}
		
		redirect($this->config->item('admin_folder').'/numbers');
	}
}	