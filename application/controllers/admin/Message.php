<?php

class Message extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        //$this->auth->check_access('Admin', true);
        $this->load->model('Message_model');
    }
	
    function index($id){
		$this->load->model('Restaurant_model');
		$restaurant = $this->Restaurant_model->get_restaurant($id);
		$data['page_title'] = 'Message to '.$restaurant->restaurant_name;
        $data['messages'] = $this->Message_model->get_restmessage($id);
        $this->view($this->config->item('admin_folder').'/restmessage', $data);
	}
	
    function restmessage()
    {       
		$data['page_title'] = 'Message to restaurants';
        $data['messages'] = $this->Message_model->get_restmessages();
		$data['restaurants'] = $this->Message_model->get_restaurants();
        $this->view($this->config->item('admin_folder').'/restmessage', $data);
    }
   
	function custmessage()
    {       
		$data['page_title'] = 'Message to customers';
        $data['messages'] = $this->Message_model->get_custmessage();
        $this->view($this->config->item('admin_folder').'/custmessage', $data);
    }
   function delmessage(){
	    $data['page_title'] = 'Message to delivery partner';
        $data['messages'] = $this->Message_model->get_delmessages();
		$data['delpartners'] = $this->Message_model->get_delpartners();
        $this->view($this->config->item('admin_folder').'/delmessage', $data);
   }
   
   function notifications()
    {       
		$data['page_title'] = 'Notification Messages';
        $data['messages'] = $this->Message_model->get_notifications();
        $this->view($this->config->item('admin_folder').'/notifications', $data);
    }
	
   function messagerest(){
	   $data = $this->input->post();
	    $id = $this->Message_model->get_messagerest($data);
		if($id > 0){
			redirect('admin/message/restmessage');
		}
   }
   
   function messagedel(){
	   $data = $this->input->post();
	    $id = $this->Message_model->messagedel($data);
		if($id > 0){
			redirect('admin/message/delmessage');
		}
   }
   
   function messagecust(){ 
	    $data = $this->input->post();
	    $id = $this->Message_model->messagecust($data);
		if($id){
			redirect('admin/message/custmessage');
		}
   }
   
   function addnotification(){
	   $data = $this->input->post();
	    $id = $this->Message_model->insert_notifications($data);
		if($id > 0){
			redirect('admin/message/notifications');
		}
   }
}