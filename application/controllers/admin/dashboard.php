<?php

class Dashboard extends Admin_Controller {

	function __construct()
	{
		parent::__construct();

		if($this->auth->check_access('Restaurant manager'))
		{
			redirect($this->config->item('admin_folder').'/restaurant');
		}
		$this->auth->check_access('Admin', true);
		
		$this->load->model('Order_model');
		$this->load->model('Customer_model');
		$this->load->helper('date');
		
		$this->lang->load('dashboard');
	}
	
	function index1()
	{
		//check to see if shipping and payment modules are installed
		$data['payment_module_installed']	= (bool)count($this->Settings_model->get_settings('payment_modules'));
		$data['shipping_module_installed']	= (bool)count($this->Settings_model->get_settings('shipping_modules'));
		
		$data['page_title']	=  lang('dashboard');
		
		// get 5 latest orders
		$data['orders']	= $this->Order_model->get_neworders();

		// get 5 latest customers
		$data['customers'] = $this->Customer_model->get_customers(5);
				
		$restaurant = $this->Customer_model->get_restaurants();
		if($restaurant != 0 ){
			$data['restaurant'] = json_encode($restaurant);
		}else{
			$data['restaurant'] = "";
		}
		$pitstops = $this->Customer_model->get_pitstops();
		if($pitstops != 0 ){
			$data['pitstops'] = json_encode($pitstops);
		}else{
			$data['pitstops'] = "";
		}
		$this->view($this->config->item('admin_folder').'/dashboard', $data);
	}
	
	function recentinfo(){ 
		// get 5 latest orders
		$data['orders']	= $this->Order_model->get_neworders();

		// get 5 latest customers
		$data['customers'] = $this->Customer_model->get_customers(5);
		$this->view($this->config->item('admin_folder').'/recentinfo', $data);
	}

	function index(){
		$restaurant = $this->Customer_model->get_restaurants();
		if($restaurant != 0 ){
			$data['restaurant'] = json_encode($restaurant);
		}else{
			$data['restaurant'] = "";
		}
		$pitstops = $this->Customer_model->get_pitstops();
		if($pitstops != 0 ){
			$data['pitstops'] = json_encode($pitstops);
		}else{
			$data['pitstops'] = "";
		}
		$sql = $this->db->query("select COUNT(id) as count from customers");
		
		$data['countcustomers'] = $sql->result()[0]->count;
		$this->view($this->config->item('admin_folder').'/map', $data);
	}
}