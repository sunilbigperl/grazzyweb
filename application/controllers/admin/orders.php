<?php

class Orders extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
        $this->load->model('Order_model');
		$this->load->model('Customer_model');
		$this->load->helper('url');
    }
    
    function neworders()
    {
        $data['orders'] = $this->Order_model->get_neworders();
        $this->view($this->config->item('admin_folder').'/neworders', $data);
    }
	
	function orders()
    {
        $data['orders'] = $this->Order_model->get_deliverypartnerneworders();
        $this->view($this->config->item('admin_folder').'/delorders', $data);
    }
	
	function previousorders(){
		//$data['orders'] = $this->Order_model->get_previousorders();
		$data['orders'] = "";
        $this->view($this->config->item('admin_folder').'/previousorders',$data);
	}
	function GetPreviousOrders(){
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			$data['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of last month'));
		}else{
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of this month'));
		}
		
		$data['orders'] = $this->Order_model->get_previousorders($data);
		$this->view($this->config->item('admin_folder').'/previousorders',$data);
	}
	
	function GetRestPreviousOrders($id){
	
		$data['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
		$data['todate'] =  date('Y-m-d',strtotime('last day of last month'));
		$data['id'] = $id;
		$data['orders'] = $this->Order_model->get_restpreviousorders($data);
		$this->view($this->config->item('admin_folder').'/previousorders',$data);
	}
    
    function GetMenudetails(){
		$data = $this->input->post('data');
		$menus = $this->Order_model->GetMenudetails($data);
		$html="";
		if($menus != 0){
			if($data['order_type'] == 3){
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $name = $customer_details->firstname." ".$customer_details->lastname;
			 $phone = $customer_details->phone;
			 $email = $customer_details->email;
			}else{
				$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
				$name = isset($deliveryboy_details->firstname) ? $deliveryboy_details->firstname." ".$deliveryboy_details->lastname : "Not assigned yet";
				$phone = isset($deliveryboy_details->phone) ? $deliveryboy_details->phone : "";
				$email = isset($deliveryboy_details->email) ? $deliveryboy_details->email : "";
			}
			$html.="<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>Delivery of order id: ".$data['order_number']."</h4>
				  </div>
				  <div class='modal-body' class='form-horizontal'>
					<div class='form-group'>
						<label><strong>"; if($data['order_type'] == 3){ $html.="Customer name";}else{$html.="Delivery boy";} $html.=":</strong>".$name."</label>
						<label><strong>Mobile No:</strong>".$phone."</label>
						<label><strong>Email:</strong>".$email."</label>
						<label><strong>Delivery location:</strong>".$data['delivery_location']."</label>
					</div>
					<table class='table table-bordered'>
					<thead>
						<tr><th>Item name</th><th>Item code</th><th>Amount</th><th></th><tr>
					</thead>
					<tbody>";
			foreach($menus as $menu){
					$html.="<tr><td>".$menu->menu."</td><td>".$menu->menu_id."</td><td>".$menu->cost."</td><td></td></td>";
					
			}
			$html.="</tbody>
				</table> 
				</div>
			  <div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			  </div>";
		}
		echo $html;
	}
	
	function ChangeRestMangerStatus($status,$id){
		$status = $this->Order_model->ChangeRestMangerStatus($status,$id);
		if($status){
			 redirect('orders/neworders', 'refresh');
		}
	}
	
	function ChangeDelPartnerStatus($status,$id){
		$status = $this->Order_model->ChangeDelPartnerStatus($status,$id);
		if($status){
			 redirect('orders/orders', 'refresh');
		}
	}
	function Review($type){
		
		$html="";
		$userdata = $this->session->userdata('admin');
		$data = $this->input->post('data');
		if($type ==  4){
			$title = "Review Restaurant";
			$name = $data['restaurant_name'];
			$id= $data['restaurant_id'];
		}elseif($type == 5){ 
			$title ="Review Delivery boy";
			$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
			$name = isset($deliveryboy_details->firstname) ? $deliveryboy_details->firstname." ".$deliveryboy_details->lastname : "";
			$id= $data['id'];
		}else{
			$title="";
		}
		
		
		$html.="<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal'>&times;</button>
				<h4 class='modal-title'>".$title."</h4>
			  </div>
			  
					<form id='review' class='form-horizontal' method='post'  action='InserReview'>
			  <div class='modal-body' class='form-horizontal'>
						<input type='hidden' name='order_number' value='".$data['order_number']."'>
						<input type='hidden' name='feedbacktype' value='".$type."'>
						<input type='hidden' name='feedbackfrom' value='".$userdata['id']."'>
						<div class='form-group col-sm-12 col-xs-12'>
							<label for='review' class='col-xs-12 col-sm-3'>Feedback to</label>
							<div class='col-sm-8 col-xs-12'>
							<input type='text' name='' class='form-control' value='".$name."' readonly>
							<input type='text' name='feedbackto' class='form-control' value='".$id."' readonly>
							</div>
						</div>
						<div class='form-group col-sm-12 col-xs-12'>
							<label for='review' class='col-xs-12 col-sm-3'>Review</label>
							<div class='col-sm-8 col-xs-12'>
								<textarea class='form-control'  id='Comments'  name='comments'></textarea>
							</div>
						</div>
						<h4 style='color: #fff;'>OR</h4>
						
							<label '>Rating</label>
							<div >
								
							</div>
							<div class='col-sm-3 col-xs-12'>
								<input type='text' name='ratings' id='ratings' value=''>
							</div>
						</div>
						<div class='pop-btn'>
							
						</div>
					
			</div>
		  <div class='modal-footer'>
			<input type='submit' class='btn btn-danger' value='Review'>&nbsp;<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
		  </div></form>";
		$html.="
			<script>
			 $('#rating-input').rating({
			  min: 0,
			  max: 5,
			  step: 1,
			  size: 'xs'
			});
			</script>";
		echo $html;
	}
	
	function InserReview(){
		$data = $this->input->post();
		$this->Order_model->InserReview($data);
		redirect('orders/orders');
	}
    function delete($id)
    {
        $category   = $this->Restaurant_model->get_restaurant($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($category)
        {
            
            $this->Restaurant_model->delete($id);
            
            $this->session->set_flashdata('message', "The restaurant has been deleted.");
            redirect($this->config->item('admin_folder').'/restaurant');
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }
	
	function pitstops_autocomplete()
	{
		$name	= trim($this->input->post('name'));
		$limit	= $this->input->post('limit');
		
		if(empty($name))
		{
			echo json_encode(array());
		}
		else
		{
			$results	= $this->Restaurant_model->pitstops_autocomplete($name, $limit);
			
			$return		= array();
			
			foreach($results as $r)
			{
				$return[$r->pitstop_id]	= $r->pitstop_name;
			}
			echo json_encode($return);
		}
		
	}
	
	public function ShowReviewDetails($id){
		$customerreview = $this->Order_model->GetCustomerReview($id);
		$customerreviewavg= isset($customerreview['avg'][0]->avg) ? $customerreview['avg'][0]->avg : 0;
		$delpartnerreview = $this->Order_model->GetDelPartnerReview($id);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;
		
		echo  "<div class=''><strong>Ratings By customer:</strong> ".$customerreviewavg."</div>";
		echo  "<div class=''><strong>Ratings By delivery partner:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Feedback</th><th>Starts</th><th>from</th></tr></thead>
			<tbody>";
			if($delpartnerreview['data']){
				foreach($delpartnerreview['data'] as $customer){ 
					echo "<tr><td>".$customer->date."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->firstname."</td></tr>";
				}
			}
			if($customerreviewavg['data']){
				foreach($customerreviewavg['data'] as $customer1){ 
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->firstname."</td></tr>";
				}
			}
		echo "</tbody>
		</table>";
	}
	
	public function RequestBill(){
		$data['orders'] = "";
        $this->view($this->config->item('admin_folder').'/requestbill',$data);
	}
	
	public function GenerateBillMail()
	{
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			$data['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of last month'));
		}else{
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of this month'));
		}
		$data['orders'] = $this->Order_model->get_previousorders($data);
		$userdata = $this->session->userdata('admin');
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'lvijetha90@gmail.com',
			'smtp_pass' => '',
			'mailtype'  => 'html', 
			'charset'   => 'utf-8',
			'newline'    => "\r\n"
		);
		$this->load->library('email',$config);
		$this->email->from('lvijetha90@gmail.com', 'vijetha');
		$this->email->to($userdata['email']);
		 
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		
		
		
		$html= $this->load->view($this->config->item('admin_folder').'/bill',$data,true);
		
        $filename  = "bill.pdf";
		
        $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");  
		$this->email->attach($filename);
		
		 //Send mail 
         if($this->email->send()) 
		 {
			echo "Your bill will be emailed to registred email id."; 
         }
		 else
		 {	
			// print_r($this->email->print_debugger());
			echo "Error in sending Email."; 
         }
	}
	
	
}