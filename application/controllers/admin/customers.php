<?php

class Customers extends Admin_Controller {

	//this is used when editing or adding a customer
	var $customer_id	= false;	

	function __construct()
	{		
		parent::__construct();
		$this->auth->check_access('Admin', true);
		$this->load->model(array('Customer_model', 'Location_model'));
		$this->load->helper('formatting_helper');
		$this->lang->load('customer');
	}
	
	function charges(){
		$this->load->helper('form');
		$sql = $this->db->query("select * from charges where id = 1");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data = $res[0];
		}else{
			$data['servicetax'] = '';
			$data['deliverycharge'] = '';
		}
		$this->view($this->config->item('admin_folder').'/charges_form',$data);
	}
	
	function SaveCharges(){
		$this->load->helper('form');
		$data['servicetax'] = $this->input->post('servicetax');
		$data['deliverycharge'] = $this->input->post('deliverycharge');
		$sql = $this->db->query("update charges set servicetax='".$data['servicetax']."', deliverycharge='".$data['deliverycharge']."' where id =1");
		if($sql){
			$this->session->set_flashdata('message', 'Charges saved successfuly');
		}
		redirect($this->config->item('admin_folder').'/customers/charges');
	}
	
	function index($field='lastname', $by='ASC', $page=0)
	{
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		//$this->session->set_flashdata('message', 'this is our message');
		
		$data['page_title']	= lang('customers');
		$data['customers']	= $this->Customer_model->get_customers(50,$page, $field, $by);
		
		$this->load->library('pagination');

		$config['base_url']		= base_url().'/'.$this->config->item('admin_folder').'/customers/index/'.$field.'/'.$by.'/';
		$config['total_rows']	= $this->Customer_model->count_customers();
		$config['per_page']		= 50;
		$config['uri_segment']	= 6;
		$config['first_link']		= 'First';
		$config['first_tag_open']	= '<li>';
		$config['first_tag_close']	= '</li>';
		$config['last_link']		= 'Last';
		$config['last_tag_open']	= '<li>';
		$config['last_tag_close']	= '</li>';

		$config['full_tag_open']	= '<div class="pagination"><ul>';
		$config['full_tag_close']	= '</ul></div>';
		$config['cur_tag_open']		= '<li class="active"><a href="#">';
		$config['cur_tag_close']	= '</a></li>';
		
		$config['num_tag_open']		= '<li>';
		$config['num_tag_close']	= '</li>';
		
		$config['prev_link']		= '&laquo;';
		$config['prev_tag_open']	= '<li>';
		$config['prev_tag_close']	= '</li>';

		$config['next_link']		= '&raquo;';
		$config['next_tag_open']	= '<li>';
		$config['next_tag_close']	= '</li>';
		
		$this->pagination->initialize($config);
		
		
		$data['page']	= $page;
		$data['field']	= $field;
		$data['by']		= $by;
		
		$this->view($this->config->item('admin_folder').'/customers', $data);
	}
	
	function export_xml()
	{
		$data['customers'] = (array)$this->Customer_model->get_customers();
		
		$this->load->helper('download_helper');
		force_download_content('customers.xml',	$this->load->view($this->config->item('admin_folder').'/customers_xml', $data, true));
	}

	function form($id = false)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= lang('customer_form');
		
		//default values are empty if the customer is new
		$data['id']					= '';
		$data['group_id']			= '';
		$data['firstname']			= '';
		$data['lastname']			= '';
		$data['email']				= '';
		$data['dob']				= '';
		$data['gender']				= '';
		$data['phone']				= '';
		$data['company']			= '';
		$data['email_subscribe']	= '';
		$data['active']				= false;
				
		// get group list
		$groups = $this->Customer_model->get_groups();
		foreach($groups as $group)
		{
			$group_list[$group->id] = $group->name;
		}
		$data['group_list'] = $group_list;
		
		
		
		if ($id)
		{	
			$this->customer_id	= $id;
			$customer		= $this->Customer_model->get_customer($id);
			//if the customer does not exist, redirect them to the customer list with an error
			if (!$customer)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder').'/customers');
			}
			
			//set values to db values
			$data['id']					= $customer->id;
			$data['group_id']			= $customer->group_id;
			$data['firstname']			= $customer->firstname;
			$data['lastname']			= $customer->lastname;
			$data['email']				= $customer->email;
			$data['dob']				= $customer->dob;
			$data['gender']				= $customer->gender;
			$data['phone']				= $customer->phone;
			$data['company']			= $customer->company;
			$data['active']				= $customer->active;
			$data['email_subscribe']	= $customer->email_subscribe;
			
		}
		
		$this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]|callback_check_email');
		$this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('company', 'lang:company', 'trim|max_length[128]');
		$this->form_validation->set_rules('active', 'lang:active');
		$this->form_validation->set_rules('dob', 'lang:dob', 'trim|required');
		$this->form_validation->set_rules('gender', 'lang:gender', 'trim|required');
		$this->form_validation->set_rules('group_id', 'group_id', 'numeric');
		$this->form_validation->set_rules('email_subscribe', 'email_subscribe', 'numeric|max_length[1]');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
		if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id)
		{
			//echo $this->input->post('confirm'); exit;
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
			$this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]|sha1');
		}
		
				
		if ($this->form_validation->run() == FALSE)
		{
			$this->view($this->config->item('admin_folder').'/customer_form', $data);
		}
		else
		{
			$save['id']		= $id;
			$save['group_id'] 	= $this->input->post('group_id');
			$save['firstname']	= $this->input->post('firstname');
			$save['lastname']	= $this->input->post('lastname');
			$save['email']		= $this->input->post('email');
			$save['phone']		= $this->input->post('phone');
			$save['company']	= $this->input->post('company');
			$save['dob']	= $this->input->post('dob');
			$save['gender']	= $this->input->post('gender');
			$save['active']		= $this->input->post('active');
			$save['email_subscribe'] = $this->input->post('email_subscribe');

			
			if ($this->input->post('password') != '' || !$id)
			{
				$save['password']	= $this->input->post('password');
			}
			
			$this->Customer_model->save($save);
			
			$this->session->set_flashdata('message', lang('message_saved_customer'));
			
			//go back to the customer list
			redirect($this->config->item('admin_folder').'/customers');
		}
	}
	
	function addresses($id = false)
	{
		$data['customer']		= $this->Customer_model->get_customer($id);

		//if the customer does not exist, redirect them to the customer list with an error
		if (!$data['customer'])
		{
			$this->session->set_flashdata('error', lang('error_not_found'));
			redirect($this->config->item('admin_folder').'/customers');
		}
		
		$data['addresses'] = $this->Customer_model->get_address_list($id);
		
		$data['page_title']	= sprintf(lang('addresses_for'), $data['customer']->firstname.' '.$data['customer']->lastname);
		
		$this->view($this->config->item('admin_folder').'/customer_addresses', $data);
	}
	
	function delete($id = false)
	{
		if ($id)
		{	
			$customer	= $this->Customer_model->get_customer($id);
			//if the customer does not exist, redirect them to the customer list with an error
			if (!$customer)
			{
				$this->session->set_flashdata('error', lang('error_not_found'));
				redirect($this->config->item('admin_folder').'/customers');
			}
			else
			{
				//if the customer is legit, delete them
				$delete	= $this->Customer_model->delete($id);
				
				$this->session->set_flashdata('message', lang('message_customer_deleted'));
				redirect($this->config->item('admin_folder').'/customers');
			}
		}
		else
		{
			//if they do not provide an id send them to the customer list page with an error
			$this->session->set_flashdata('error', lang('error_not_found'));
			redirect($this->config->item('admin_folder').'/customers');
		}
	}
	
	//this is a callback to make sure that customers are not sharing an email address
	function check_email($str)
	{
		$email = $this->Customer_model->check_email($str, $this->customer_id);
        	if ($email)
        	{
			$this->form_validation->set_message('check_email', lang('error_email_in_use'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function order_list($status = false)
	{
		//we're going to use flash data and redirect() after form submissions to stop people from refreshing and duplicating submissions
		$this->load->model('Order_model');
		
		$data['page_title']	= 'Order List';
		$data['orders']		= $this->Order_model->get_orders($status);
		
		$this->view($this->config->item('admin_folder').'/order_list', $data);
	}
	
	
	// download email blast list (subscribers)
	function get_subscriber_list()
	{
		$subscribers = $this->Customer_model->get_subscribers();
		
		$sub_list = '';
		foreach($subscribers as $subscriber)
		{
			$sub_list .= $subscriber['email'].",\n";
		}
		
		$data['sub_list']	= $sub_list;
		
		$this->load->view($this->config->item('admin_folder').'/customer_subscriber_list', $data);
	}	
	
	//  customer groups
	function groups()
	{
		$data['groups']		= $this->Customer_model->get_groups();
		$data['page_title']	= lang('customer_groups');
		
		$this->view($this->config->item('admin_folder').'/customer_groups', $data);
	}
	
	function edit_group($id=0)
	{
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$data['page_title']		= lang('customer_group_form');
		
		//default values are empty if the customer is new
		$data['id']				= '';
		$data['name']   		= '';
		$data['discount']		= '';
		$data['discount_type'] 	= '';
		
		if($id)
		{
			$group = $this->Customer_model->get_group($id);
			
			$data['id']				= $group->id;
			$data['name']   		= $group->name;
			$data['discount']		= $group->discount;
			$data['discount_type'] 	= $group->discount_type;
		}
		
		$this->form_validation->set_rules('name', 'lang:group_name', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('discount', 'lang:discount', 'trim|required|numeric');
		$this->form_validation->set_rules('discount_type', 'lang:discount_type', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->view($this->config->item('admin_folder').'/customer_group_form', $data);
		}
		else
		{
			
			if($id)
			{
				$save['id'] = $id;
			}
			
			$save['name'] 			= set_value('name');
			$save['discount'] 		= set_value('discount');
			$save['discount_type']	= set_value('discount_type');
			
			$this->Customer_model->save_group($save);
			$this->session->set_flashdata('message', lang('message_saved_group'));
			
			//go back to the customer group list
			redirect($this->config->item('admin_folder').'/customers/groups');
		}
	}
	
	
	function get_group()
	{
		$id = $this->input->post('id');
		
		if(empty($id)) return;
		
		echo json_encode($this->Customer_model->get_group($id));
	}
	
	
	function delete_group($id)
	{
		
		if(empty($id))
		{
			return;
		}
		
		$this->Customer_model->delete_group($id);
		
		//go back to the customer list
		redirect($this->config->item('admin_folder').'/customers/groups');
	}
	
	function address_list($customer_id)
	{
		$data['address_list'] = $this->Customer_model->get_address_list($customer_id);
		
		$this->view($this->config->item('admin_folder').'/address_list', $data);
	}
	
	function address_form($customer_id, $id = false)
	{
		$data['id']				= $id;
		$data['entry_name']		= '';
		$data['company']		= '';
		$data['firstname']		= '';
		$data['lastname']		= '';
		$data['email']			= '';
		$data['phone']			= '';
		$data['address1']		= '';
		$data['address2']		= '';
		$data['city']			= '';
		$data['country_id']		= '';
		$data['zone_id']		= '';
		$data['zip']			= '';
		
		$data['customer_id']	= $customer_id;
		
		$data['page_title']		= lang('address_form');
		//get the countries list for the dropdown
		$data['countries_menu']	= $this->Location_model->get_countries_menu();
		
		if($id)
		{
			$address			= $this->Customer_model->get_address($id);
			
			//fully escape the address
			form_decode($address);
			
			//merge the array
			$data				= array_merge($data, $address);
			
			$data['zones_menu']	= $this->Location_model->get_zones_menu($data['country_id']);
		}
		else
		{
			//if there is no set ID, the get the zones of the first country in the countries menu
			$countries_menu = $data['countries_menu'];
			$res1 = array_keys($countries_menu);
			$res = array_shift($res1);
			$data['zones_menu']	= $this->Location_model->get_zones_menu($res);
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('entry_name', 'lang:entry_name', 'trim|max_length[128]');
		$this->form_validation->set_rules('firstname', 'lang:firstname', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('lastname', 'lang:lastname', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('email', 'lang:email', 'trim|required|valid_email|max_length[128]');
		$this->form_validation->set_rules('phone', 'lang:phone', 'trim|required|max_length[32]');
		$this->form_validation->set_rules('address1', 'lang:address', 'trim|required|max_length[128]');
		$this->form_validation->set_rules('address2', 'lang:address', 'trim|max_length[128]');
		$this->form_validation->set_rules('city', 'lang:city', 'trim|required');
		$this->form_validation->set_rules('country_id', 'lang:country', 'trim|required');
		$this->form_validation->set_rules('zip', 'lang:zip', 'trim|required|max_length[32]');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->view($this->config->item('admin_folder').'/customer_address_form', $data);
		}
		else
		{
			
			$a['customer_id']				= $customer_id; // this is needed for new records
			$a['id']						= (empty($id))?'':$id;
			$a['field_data']['entry_name']				= $this->input->post('entry_name');
			$a['field_data']['company']		= $this->input->post('company');
			$a['field_data']['firstname']	= $this->input->post('firstname');
			$a['field_data']['lastname']	= $this->input->post('lastname');
			$a['field_data']['email']		= $this->input->post('email');
			$a['field_data']['phone']		= $this->input->post('phone');
			$a['field_data']['address1']	= $this->input->post('address1');
			$a['field_data']['address2']	= $this->input->post('address2');
			$a['field_data']['city']		= $this->input->post('city');
			$a['field_data']['zip']			= $this->input->post('zip');
			
			
			$a['field_data']['zone_id']		= $this->input->post('zone_id');
			$a['field_data']['country_id']	= $this->input->post('country_id');
			
			$country	= $this->Location_model->get_country($this->input->post('country_id'));
			$zone		= $this->Location_model->get_zone($this->input->post('zone_id'));
			
			$a['field_data']['zone']			= $zone->name;  // save the state for output formatted addresses
			$a['field_data']['country']			= $country->name; // some shipping libraries require country name
			$a['field_data']['country_code']	= $country->iso_code_2; // some shipping libraries require the code 
			
			$this->Customer_model->save_address($a);
			$this->session->set_flashdata('message', lang('message_saved_address'));
			
			redirect($this->config->item('admin_folder').'/customers/addresses/'.$customer_id);
		}
	}
	
	
	function delete_address($customer_id = false, $id = false)
	{
		if ($id)
		{	
			$address	= $this->Customer_model->get_address($id);
			//if the customer does not exist, redirect them to the customer list with an error
			if (!$address)
			{
				$this->session->set_flashdata('error', lang('error_address_not_found'));
				
				if($customer_id)
				{
					redirect($this->config->item('admin_folder').'/customers/addresses/'.$customer_id);
				}
				else
				{
					redirect($this->config->item('admin_folder').'/customers');
				}
				
			}
			else
			{
				//if the customer is legit, delete them
				$delete	= $this->Customer_model->delete_address($id, $customer_id);				
				$this->session->set_flashdata('message', lang('message_address_deleted'));
				
				if($customer_id)
				{
					redirect($this->config->item('admin_folder').'/customers/addresses/'.$customer_id);
				}
				else
				{
					redirect($this->config->item('admin_folder').'/customers');
				}
			}
		}
		else
		{
			//if they do not provide an id send them to the customer list page with an error
			$this->session->set_flashdata('error', lang('error_address_not_found'));
			
			if($customer_id)
			{
				redirect($this->config->item('admin_folder').'/customers/addresses/'.$customer_id);
			}
			else
			{
				redirect($this->config->item('admin_folder').'/customers');
			}
		}
	}
	
	public function ShowReviewDetails($id){
		$RestReview = $this->Customer_model->GetReviewRest($id,2);
		$RestReviewavg= isset($RestReview['avg'][0]->avg) ? $RestReview['avg'][0]->avg : 0;
		$delpartnerreview = $this->Customer_model->GetReviewDelPartner($id,8);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;
		$customer       = $this->Customer_model->get_customer($id); 
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$customer->firstname."</h4>
		</div>
		<div class='modal-body'>
		<div class=''><strong>Ratings By Restaurants:</strong> ".$RestReviewavg."</div>";
		echo  "<div class=''><strong>Ratings By deliver Partner:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Feedback</th><th>Stars</th><th>from</th></tr></thead>
			<tbody>";
			if($delpartnerreview['data']){
				foreach($delpartnerreview['data'] as $customer){ 
					echo "<tr><td>".$customer->date."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->firstname."</td></tr>";
				}
			}
			if($RestReviewavg['data']){
				foreach($customerreviewavg['data'] as $customer1){ 
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->restaurant_name."</td></tr>";
				}
			}
		echo "</tbody>
		</table></div>";
	}
	
	public function ShowCustomerDetails($id){
		$customer		= $this->Customer_model->get_customer($id);
		$addresses		= $this->Customer_model->get_address_list($id);
		$FoodOutlets =$this->Customer_model->PreferedRest($id);
		$from = new DateTime($customer->dob);
		$to   = new DateTime('today');

		$add ="";
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title pull-left'> Customer details: ".$customer->firstname."</h4>
		  <img src='uploads/images/thumbnails/".$customer->profile_image."' class='pull-right'>
		</div>
		<div class='modal-body'>
		<div class=''><strong>Phone:</strong> ".$customer->phone."</div>
		<div class=''><strong>Email:</strong> ".$customer->email."</div>
		<div class=''><strong>Age:</strong> ".$from->diff($to)->y."</div>
		<div><strong>Prefered Food outlets </strong>";
		echo "<ul>";
		foreach($FoodOutlets['data'] as $FoodOutlet){
			echo "<li>".$FoodOutlet->restaurant_name."</li>";
		}
		echo "</ul>";
			if (count($addresses) > 1){
				$i=1;
				foreach($addresses as $address){ 
					$f = $address['field_data'];
					echo "<div>";
					echo "<strong>Address".$i." :</strong> "; echo isset($f['address1']) ? $f['address1'] : "";
					echo isset($f['address2']) ? $f['address2'] : ""; 
					echo isset($f['city']) ? $f['city'] : "";
					echo isset($f['zip']) ? $f['zip'] : "";
					echo "</div>";
				$i++;
				}
			}
			
			
		echo "</div>";
	}
	
	public function ChangeStatus($id,$status){
		$result = $this->Customer_model->ChangeStatus($id,$status);
		if($result){
			redirect("admin/customers");
		}
	}
	
	public function suggestions(){
		$data['RestSuggestions'] = $this->Customer_model->GetRestSuggestions($all = true);  
		$data['PitstopSuggestion'] = $this->Customer_model->GetPitstopSuggestion($all = true); 
		$this->view($this->config->item('admin_folder').'/suggestions', $data);
	}
	
	public function ShowAlert(){
		$html = "";
		date_default_timezone_set('Asia/Calcutta');
		if($this->auth->check_access('Restaurant manager')){ 
			$date = date('Y-m-d H:i:s'); 
			$date1 = date("Y-m-d H:i:s",strtotime($date." -15 minutes"));
			
			$sql = $this->db->query("select * from orders as a left join restaurant as b on a.restaurant_id = b.restaurant_id
			where a.ordered_on >= '".$date1."' and b.restaurant_manager='".$userdata['id']."'");
			if($sql->num_rows() > 0){
				$result =  $sql->result_array();
				foreach($result as $res){
					$html.="There is new order : ".$res['order_number']."\n";
				}
			}
		}
		elseif($this->auth->check_access('Deliver manager')){ 
			$date = date('Y-m-d H:i:s'); 
			$date1 = date("Y-m-d H:i:s",strtotime($date." -15 minutes"));
			
			$sql = $this->db->query("select * from orders where ordered_on >= '".$date1."' and delivery_partner='".$userdata['id']."'");
			if($sql->num_rows() > 0){
				$result =  $sql->result_array();
				foreach($result as $res){
					$html.="There is new order : ".$res['order_number']."\n";
				}
			}
		}
		else{
			$date = date('Y-m-d H:i:s'); 
			$date1 = date("Y-m-d H:i:s",strtotime($date." -15 minutes"));
			$userdata = $this->session->userdata('admin');
			$sql = $this->db->query("select * from pitstop_suggest where date >= '".$date1."'");
			if($sql->num_rows() > 0){
				$result =  $sql->result_array();
				foreach($result as $res){
					$html.="There is new pitstop suggest : ".$res['restaurant_address']."\n";
					$p = 1;
				}
			}
			
			$sql = $this->db->query("select * from restaurant_suggest where date >= '".$date1."'");
			if($sql->num_rows() > 0){
				$result =  $sql->result_array();
				foreach($result as $res){
					$html.="There is new restaurant suggest : ".$res['restaurant_name']."\n";
					
				}
			}
			
			$sql = $this->db->query("select * from orders where ordered_on >= '".$date1."'");
			if($sql->num_rows() > 0){
				$result =  $sql->result_array();
				foreach($result as $res){
					$html.="There is new order : ".$res['order_number']."\n";
				}
			}
		}
		
			echo $html;
		
	}
}