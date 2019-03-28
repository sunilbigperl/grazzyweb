<?php
class Deliveryboy extends Admin_Controller
{
	
	var $delivery_id	= false;
	function __construct()
	{
		parent::__construct();

		$this->auth->check_access('Deliver manager', false);
		$this->auth->check_access('Restaurant manager', true);
		$this->load->model('Deliveryboy_model');
		$this->load->model('Restaurant_model');
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
		
		$config['upload_path']		= 'uploads';
		$config['allowed_types']	= 'gif|jpg|png';
		$config['max_size']			= $this->config->item('size_limit');
		$config['encrypt_name']		= true;
		$config['width'] = 30;
		$config['height'] = 30;
		$this->load->library('upload', $config);
		
		//set the default values
		$data['id']			= '';
		$data['name']		= '';
		$data['address']	= '';
		$data['phone']		= '';
		$data['email']	= '';
		$data['enabled'] = '';
		$data['image'] = '';
		$data['adharno'] = '';
		
	
		$data['deliveryboys']		= $this->Deliveryboy_model->get_lists();
		
		if($id)
		{
			$this->delivery_id	= $id;
			
			$page			= $this->Deliveryboy_model->get_deliveryboys($id);
			
			$page = $page[0];
			if(!$page)
			{
				//page does not exist
				$this->session->set_flashdata('error', lang('error_page_not_found'));
				redirect($this->config->item('admin_folder').'/deliveryboy');
			}
			
			
			//set values to db values
			$data['id']			= $page->id;
			$data['name']		= $page->name;
			$data['address']	= $page->address;
			$data['phone']		= $page->phone;
			$data['email']		= $page->email;
			$data['image']		= $page->image;
			$data['enabled']	= $page->enabled;
            $data['adharno']	= $page->adharno;
			
		}
		
		$this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[64]');
		$this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[11]|callback_check_phone');
		// Validate the form
		if($this->form_validation->run() == false)
		{
			
			$this->view($this->config->item('admin_folder').'/deliveryboy_form', $data);
		}
		else
		{
			//$uploaded	= $this->upload->do_upload('image');
		
			$userdata = $this->session->userdata('admin');
			$save = array();
			// if ($id)
			// {
				
			// 	$save['id']	= $id;
				
			// 	//delete the original file if another is uploaded
			// 	if($uploaded)
			// 	{
			// 		if($data['image'] != '')
			// 		{
						
			// 			$file = 'uploads/'.$data['image'];
						
			// 			//delete the existing file if needed
			// 			if(file_exists($file))
			// 			{
			// 				unlink($file);
			// 			}
			// 		}
			// 	}
				
			// }
			// else
			// { 
			// 	if(!$uploaded)
			// 	{
					
			// 		$data['error']	= $this->upload->display_errors();
			// 		$this->view(config_item('admin_folder').'/deliveryboy_form', $data);
			// 		return; //end script here if there is an error
			// 	}
			// }
			
			// if($uploaded)
			// {
			// 	$image			= $this->upload->data();
			// 	$save['image']	= $image['file_name'];
			// }
			$save['id']			= $id;
			$save['name']	= $this->input->post('name');
			$save['address']		= $this->input->post('address');
			$save['phone']	= $this->input->post('phone'); 
			$save['email']	= $this->input->post('email');
			$save['enabled']        = $this->input->post('enabled');
			$save['adharno']        = $this->input->post('adharno');
			// $save['delivery_partner'] = $userdata['id'];
			$save['restaurant_manager'] = $userdata['id'];
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
		
		$page	= $this->Deliveryboy_model->DeleteDeliveryBoy($id);
		
		redirect($this->config->item('admin_folder').'/deliveryboy');
	}
	
	public function ShowReviewDetails($id){
		$RestReview = $this->Deliveryboy_model->GetReviewRest($id);
		$RestReviewavg= isset($RestReview['avg'][0]->avg) ? $RestReview['avg'][0]->avg : 0;
		$delpartnerreview = $this->Deliveryboy_model->GetReviewDelPartner($id);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;
		$delboyreview = $this->Deliveryboy_model->GetReviewboyPartner($id,8);
        $delboyreviewavg = isset($delboyreview['avg'][0]->avg) ? $delboyreview['avg'][0]->avg :0;
		$deliveryboy       = $this->Deliveryboy_model->get_deliveryboy($id); 
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$deliveryboy->name."</h4>
		</div>
		<div class='modal-body'>
		<div class=''><strong>Ratings By Restaurants:</strong> ".$RestReviewavg."</div>";
		echo  "<div class=''><strong>Ratings By delivery partner:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Order_Number</th><th>Feedbacktype</th><th>Feedback</th><th>Stars</th><th>from</th></tr></thead>
			<tbody>";
			if($delpartnerreview['data']){
				foreach($delpartnerreview['data'] as $customer){ 
					echo "<tr><td>".$customer->date."</td><td>".$customer->order_number."</td><td>".$customer->feedbacktype."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->firstname."</td></tr>";
				}
			}
			// if($RestReviewavg['data']){
			// 	foreach($customerreviewavg['data'] as $customer1){ 
			// 		echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->firstname."</td></tr>";
			// 	}
			// }

			if( $RestReview['data']){
				foreach( $RestReview['data'] as $customer1){ 
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->order_number."</td><td>".$customer1->feedbacktype."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->restaurant_name."</td></tr>";
				}
			}

			if( $delboyreview['data']){
				foreach( $delboyreview['data'] as $customer1){ 
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->order_number."</td><td>".$customer1->feedbacktype."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->name."</td></tr>";
				}
			}
		echo "</tbody>
		</table></div>";
	}
	
	function check_phone($str)
	{
		$email = $this->Deliveryboy_model->check_phone($str, $this->delivery_id);
		if ($email)
		{
			$this->form_validation->set_message('check_phone', 'This phone no already in use');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
}	