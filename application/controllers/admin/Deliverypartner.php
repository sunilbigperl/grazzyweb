<?php
class Deliverypartner extends Admin_Controller
{
	//these are used when editing, adding or deleting an admin
	var $admin_id		= false;
	var $current_admin	= false;
	function __construct()
	{
		parent::__construct();
		
		//load the admin language file in
		$this->lang->load('admin');
		$this->load->model('Deliveryboy_model');
		$this->load->model('Restaurant_model');
		$this->current_admin	= $this->session->userdata('admin');
	}

	function index()
	{
		if($this->auth->check_access('Deliver manager')){
			$userdata = $this->session->userdata('admin');
			if($userdata['id'] != $this->uri->segment(4)){
				redirect($this->config->item('admin_folder').'/orders/delpartnerorders');
			}
		} 
		$this->auth->check_access('Admin', true);
		$data['page_title']	= "Delivery partners";
		$data['admins']		= $this->Deliveryboy_model->get_deliverypartner_list();
		
		$this->view($this->config->item('admin_folder').'/deliverypartners', $data);
	}
	function delete($id)
	{
		$this->auth->check_access('Admin', true);
		
		//even though the link isn't displayed for an admin to delete themselves, if they try, this should stop them.
		if ($this->current_admin['id'] == $id)
		{
			$this->session->set_flashdata('message', lang('error_self_delete'));
			redirect($this->config->item('admin_folder').'/deliverypartner');	
		}
		
		//delete the user
		$this->auth->delete($id);
		$this->session->set_flashdata('message', lang('message_user_deleted'));
		redirect($this->config->item('admin_folder').'/deliverypartner');
	}
	
	function form($id = false)
	{	
		
		
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
		$data['servicetax'] = '';
		$data['gst'] = '';
		$data['commission'] = '';
		$data['penalty'] ='';
        $data['fromtime'] ='';
		$data['totime'] ='';
		$data['days'] ='';
		$data['ListValues'] ='';
		$data['NextRenewalDate'] = '';
		if ($id)
		{	
			$this->admin_id		= $id;
			$admin			= $this->auth->get_admin($id);
			$ListValues	=$this->Deliveryboy_model->get_ListValues($id);
			
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
			$data['gst'] = $admin->GST;
			$data['servicetax'] = $admin->servicetax;
			$data['commission'] = $admin->commission;
			$data['penalty']	= $admin->penalty;
			$data['fromtime']	= $admin->fromtime;
			$data['totime']	= $admin->totime;
			$data['days']	= $admin->days;
			$data['ListValues'] = $ListValues;
			$data['NextRenewalDate'] = $admin->NextRenewalDate;
		}
		
		
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
			$this->view($this->config->item('admin_folder').'/deliverypartner_form', $data);
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
			$save['gst']			   = $this->input->post('gst');
			$save['servicetax']			   = $this->input->post('servicetax');
			$save['commission'] 		   = $this->input->post('commission');
			$save['penalty'] 			   = $this->input->post('penalty');
			$save['fromtime'] 			   = $this->input->post('fromtime');
			$save['totime'] 			   = $this->input->post('totime');
			$save['days'] 				   = serialize($this->input->post('days'));
			$save['NextRenewalDate'] = $this->input->post('NextRenewalDate');
			
			$ListValues = $this->input->post('values');
			
			//print_r($save); exit;
			if ($this->input->post('password') != '' || !$id)
			{
				$save['password']	= $this->input->post('password');
			}
			
			$this->auth->save($save);
	
			$this->Deliveryboy_model->SaveCharges($ListValues,$id);
			
			$this->session->set_flashdata('message', "Delivery partner saved");
			
			//go back to the customer list
		
				redirect($this->config->item('admin_folder').'/deliverypartner');
			
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
	
	public function ShowReviewDetails($id){
		$delpartnerreview = $this->Deliveryboy_model->GetReviewDelPartner1($id);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;
		$delpartner       = $this->Deliveryboy_model->get_deliveryPartner($id); 
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews for ".$delpartner->firstname."</h4>
		</div>
		<div class='modal-body'>";
		echo  "<div class=''><strong>Ratings By Restaurants:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>order_number</th><th>Feedbacktype</th><th>Feedback</th><th>Stars</th><th>from</th></tr></thead>
			<tbody>";
			if($delpartnerreview['data']){
				foreach($delpartnerreview['data'] as $customer){ 
					echo "<tr><td>".$customer->date."</td><td>".$customer->order_number."</td><td>".$customer->feedbacktype."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->restaurant_name."</td></tr>";
				}
			}
			
		echo "</tbody>
		</table></div>";
	}
	
	function partnerform($id = false)
	{	
		
		if($this->auth->check_access('Deliver manager', true)){
			$userdata = $this->session->userdata('admin');
			if($userdata['id'] != $this->uri->segment(4)){
				redirect($this->config->item('admin_folder').'/orders/delpartnerorders');
			}
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		$data['page_title']		= "Delivery Partner form";
		
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
		$data['servicetax'] = '';
		$data['commission'] = '';
		$data['penalty'] ='';
		 $data['fromtime'] ='';
		$data['totime'] ='';
		$data['dayss'] = '';
		
		if ($id)
		{	
			$this->admin_id		= $id;
			$admin			= $this->auth->get_admin($id);
			//if the administrator does not exist, redirect them to the admin list with an error
			if (!$admin)
			{
				$this->session->set_flashdata('message', lang('admin_not_found'));
				redirect($this->config->item('admin_folder').'/delpartners');
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
			$data['servicetax'] = $admin->servicetax;
			$data['commission'] = $admin->commission;
			$data['penalty']	= $admin->penalty;
			$data['fromtime']	= $admin->fromtime;
			$data['totime']	= $admin->totime;
			$data['days'] = $admin->days;
		}
		
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]');
		$this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[11]|callback_validate_phone_number');
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
			$this->view($this->config->item('admin_folder').'/deliverypartner_form', $data);
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
			$save['servicetax']			   = $this->input->post('servicetax');
			$save['commission'] 		   = $this->input->post('commission');
			$save['penalty'] 			   = $this->input->post('penalty');
			$save['fromtime'] 			   = $this->input->post('fromtime');
			$save['totime'] 			   = $this->input->post('totime');
			$save['days'] 				   = serialize($this->input->post('days'));
			
			if ($this->input->post('password') != '' || !$id)
			{
				$save['password']	= $this->input->post('password');
			}
			
			$this->mo->save($save);
			
			$this->session->set_flashdata('message', lang('message_user_saved'));
			
			//go back to the customer list
			redirect($this->config->item('admin_folder').'/delpartners');
		}
	}
	
	public function ChangeStatus($id=false,$enabled=false){
		$enabled = $this->input->post('enabled');
		$data['id'] = false == $this->input->post('patid') ? $id : $this->input->post('patid');
		$data['enabled'] = isset($enabled) ? $enabled : 1;
		$data['deactivatefrom'] = date('Y-m-d',strtotime($this->input->post('FromDate')));
		$data['deactivateto'] = date('Y-m-d',strtotime($this->input->post('ToDate')));
		
		$result = $this->Deliveryboy_model->ChangeStatus($data);
		if($result){
			redirect("admin/deliverypartner");
		}
	}
	
	function validate_phone_number($value) {
		
		$value = trim($value);
		$match = '/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/';
		$replace = '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
		$return = '($1) $2-$3';
		if (preg_match($match, $value)) {
			return preg_replace($replace, $return, $value);
		} else {
			$this->form_validation->set_message('validate_phone_number', 'Invalid Phone.');
		return false;
		}
	}
}