<?php

class Dashboard extends Admin_Controller {

	function __construct()
	{
		parent::__construct();

		if($this->auth->check_access('Restaurant manager'))
		{
			redirect($this->config->item('admin_folder').'/restaurant');
		}
		if($this->auth->check_access('Deliver manager')){
			
			redirect($this->config->item('admin_folder').'/orders/delpartnerorders');
			
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
		$data['orders']	= $this->Order_model->get_newordersForAdmin();

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
		
		$sql1 =  $this->db->query("select COUNT(pitstop_id) as count from pitstops");
		$data['pitstops'] = $sql1->result()[0]->count;
		
		$date = date('Y-m-d H:i:s',strtotime('last day of last month'));
		$sql2  = $this->db->query("SELECT COUNT(id) as count FROM `orders` WHERE `ordered_on` <= '".$date."'");
		//echo "SELECT COUNT(id) as count FROM `orders` WHERE `ordered_on` <= '".$date."'";exit;
		$data['previousorders'] = $sql2->result()[0]->count;
		
		$sql3  = $this->db->query("SELECT COUNT(id) as count FROM `orders`");
		$data['totalorders'] = $sql3->result()[0]->count;

		$sql4  = $this->db->query("SELECT COUNT(restaurant_id) as count FROM `restaurant`");
		$data['foodoutlets'] = $sql4->result()[0]->count;
		$this->view($this->config->item('admin_folder').'/map', $data);
	}
}