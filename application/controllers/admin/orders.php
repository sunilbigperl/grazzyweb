<?php

class Orders extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
        $this->load->model('Order_model');
		$this->load->model('Customer_model');
		$this->load->model('Message_model');
		$this->load->model('Restaurant_model');
		$this->load->model('Roadrunner_model');
		$this->load->model('Pitstop_model');
		$this->load->helper('url');
    }

	function dashboard(){
		$data['orders'] = $this->Order_model->get_neworders();
		 $this->view($this->config->item('admin_folder').'/restaurantdatshboard',$data);
	}
    function neworders()
    {
        $data['orders'] = $this->Order_model->get_neworders();
        $this->view($this->config->item('admin_folder').'/neworders', $data);
    }

	function delpartnerorders()
    {
        $data['orders'] = $this->Order_model->get_delpartnerorders();
		$data['deliveryboys'] = $this->Order_model->get_deliveryboys();
        $this->view($this->config->item('admin_folder').'/delpartnerorders', $data);
    }

	function AssignDeliveryBoy($id){
		$data['delBoy'] = $this->input->post('deliveryboy');
		$data['id'] = $id;
		$results = $this->Order_model->AssignDeliveryBoy($data);
		if($results){
			 redirect('admin/orders/delpartnerorders', 'refresh');
		}

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
	function previousordersdelpartner(){

		if($this->input->post('action') == "Go"){
			$data['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));
			$data['delpartner'] = $this->input->post('delpartner');
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  date('Y-m-d H:i:s',strtotime('last day of last month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}else{

			$data['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  date('Y-m-d H:i:s',strtotime('last day of this month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}

		$data['orders'] = $this->Order_model->get_previousorders($data);
		$this->view($this->config->item('admin_folder').'/previousordersdelpartner',$data);
	}
	function GetPreviousOrders(){
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));
			$data['delpartner'] = $this->input->post('delpartner');
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  date('Y-m-d H:i:s',strtotime('last day of last month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}else{
			$data['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  date('Y-m-d H:i:s',strtotime('last day of this month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}

		$data['orders'] = $this->Order_model->get_previousorders($data);
		$this->view($this->config->item('admin_folder').'/previousorders',$data);
	}

	function GetRestPreviousOrders($id){
		$this->load->model('Restaurant_model');
		$data['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
		$data['todate'] =  date('Y-m-d',strtotime('last day of last month'));
		$data['id'] = $id;
		$restaurant       = $this->Restaurant_model->get_restaurant($id);

		$data['page_title'] = "Previous Orders and sales of ".$restaurant->restaurant_name;
		$data['orders'] = $this->Order_model->get_restpreviousorders($data);
		$this->view($this->config->item('admin_folder').'/previousorders',$data);
	}

	function getOrderDetails(){
		$html="";
		$data = $this->input->post('data');
		$restaurant = $this->Restaurant_model->get_restaurant($data['restaurant_id']);
		if($data['ordertype_id'] == 3){
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $cname = $customer_details->firstname." ".$customer_details->lastname;
			 $cphone = $customer_details->phone;

			 $dname = $customer_details->firstname." ".$customer_details->lastname;
			 $dphone = $customer_details->phone;

		}else{
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $cname = $customer_details->firstname." ".$customer_details->lastname;
			 $cphone = $customer_details->phone;

			$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
			$dname = isset($deliveryboy_details->firstname) ? $deliveryboy_details->firstname." ".$deliveryboy_details->lastname : "Not assigned yet";
			$dphone = isset($deliveryboy_details->phone) ? $deliveryboy_details->phone : "";

		}

		$html.="<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>Delivery of order id: ".$data['order_number']."</h4>
				  </div>
				  <div class='modal-body' class='form-horizontal'>
					<div class='form-group'>
          <table class='table table-bordered'>
          <tr>
						<td>Pikup location:</td><td>".$restaurant->restaurant_address."</td>
            </tr>
            <tr>
						<td>Restaurant Name:</td><td>".$restaurant->restaurant_name."</td>
            </tr>
            <tr>
						<td>Restaurant Contact number:</td><td>".$restaurant->restaurant_phone."</td>
            </tr>
            <tr>
						<td>Customer contact number:</td><td>".$cphone."</td>
            </tr>
            <tr>
						<td>Delivery contact number:</td><td>".$dphone."</td>
            </tr>
            <tr>
						<td>Passcode:</td><td>".$data['passcode']."</td>
            </tr>
            <tr>
						<td>Delivery location:</td><td>".$data['delivery_location']."</td>
            </tr>
            </table>
					</div>
				</div>";
		echo $html;
	}


    function GetMenudetails(){
		$data = $this->input->post('data');
		$menus = $this->Order_model->GetMenudetails($data);
		$html="";
		if($menus != 0){
			if($data['ordertype_id'] == 3){
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
						<label><strong>"; if($data['ordertype_id'] == 3){ $html.="Customer name";}else{$html.="Delivery boy";} $html.=":</strong>".$name."</label></br>
						<label><strong>Mobile No:</strong>".$phone."</label></br>

					</div>
					<table class='table table-bordered'>
					<thead>
						<tr><th>Item name</th><th>Item code</th><th>No of items</th><th>Amount</th><tr>
					</thead>
					<tbody>";
			foreach($menus as $menu){
					$html.="<tr><td>".$menu->menu."</td><td>".$menu->menu_id."</td><td>".$menu->quantity."</td><td>".$menu->cost."</td></td>";

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
		$statuss = $this->Order_model->ChangeRestMangerStatus($status,$id);
		if($statuss && $status == 1){
			$data['order'] = $this->Order_model->get_order($id);
			if($data['order']->order_type != 3){
				$data['restaurant'] = $this->Restaurant_model->get_restaurant($data['order']->restaurant_id);
				$data['customer'] = $this->Customer_model->get_customer($data['order']->customer_id);
				$data['fromaddress'] = $data['restaurant']->restaurant_address;
				$data['fromcity'] = $data['restaurant']->restaurant_branch;
				if($data['order']->order_type == 1 && $data['order']->pitstop_id != ""){
					$pitstop = $this->Pitstop_model->get_pitstop($data['order']->pitstop_id);
					$data['toaddress'] = $pitstop->address;
					$data['tocity'] = $pitstop->city;
				}else{
					$data['toaddress'] = $data['order']->delivery_location;
					$data['tocity'] = $data['restaurant']->restaurant_branch;
				}
			}
			$result = $this->Roadrunner_model->CheckServicability($data);
			$roadrunner = json_decode($result);
			if($roadrunner->status->code ==  200){
				$sql = $this->db->query("update orders set delivery_partner = '123', delivery_partner_status = 'Accepted' where id='".$id."'");
				echo '<script>alert("Order assigned success. delivered by roadrunner.")</script>';
			}
		}
		redirect('admin/orders/dashboard', 'refresh');

	}

	function ChangeDelPartnerStatus($status,$id){
		$status = $this->Order_model->ChangeDelPartnerStatus($status,$id);
		if($status){
			 redirect('admin/orders/delpartnerorders', 'refresh');
		}
	}
	function Review($type){

		$html="";
		$userdata = $this->session->userdata('admin');
		$data = $this->input->post('data');
		if($type ==  2){
			$title = "Review Customer";
			$customer_details = $this->Customer_model->get_customer($data['customer_id']);
			$name = $customer_details->firstname;
			$id= $data['id'];
		}elseif($type == 3 || $type == 5){
			$title ="Review Delivery boy";
			$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
			$name = isset($deliveryboy_details->name) ? $deliveryboy_details->name : "";
			$id= $data['id'];
		}elseif($type == 4){
			$title = "Review Restaurant";
			$name = $data['restaurant_name'];
			$id= $data['restaurant_id'];
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
							<input type='hidden' name='feedbackto' class='form-control' value='".$id."' readonly>
							</div>
						</div>
						<div class='form-group col-sm-12 col-xs-12'>
							<label for='review' class='col-xs-12 col-sm-3'>Review</label>
							<div class='col-sm-8 col-xs-12'>
								<textarea class='form-control'  id='Comments'  name='comments'></textarea>
							</div>
						</div>
						<h4 style='color: #fff;'>OR</h4>

							<label class='col-xs-12 col-sm-3'>Rating</label>
							<div class='col-sm-8 col-xs-12'>
								<input type='text' name='ratings' id='ratings' value='' class='form-control' >
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
		redirect($_SERVER['HTTP_REFERER']);
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
		$this->load->model('Restaurant_model');
		$customerreview = $this->Order_model->GetCustomerReview($id);
		$customerreviewavg= isset($customerreview['avg'][0]->avg) ? $customerreview['avg'][0]->avg : 0;
		$delpartnerreview = $this->Order_model->GetDelPartnerReview($id);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;

		$delboyreview = $this->Order_model->GetDelBoyReview($id);
		$delboyreviewavg = isset($delboyreview['avg'][0]->avg) ? $delboyreview['avg'][0]->avg :0;
		$restaurant       = $this->Restaurant_model->get_restaurant($id);
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$restaurant->restaurant_name."</h4>
		</div>
		<div class='modal-body'>
		<div class=''><strong>Ratings By customer:</strong> ".$customerreviewavg."</div>";
		echo  "<div class=''><strong>Ratings By delivery partner:</strong> ".$delpartnerreviewavg."</div>";
	/*	echo  "<div class=''><strong>Ratings By delivery boy:</strong> ".$delboyreviewavg."</div>";*/
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Feedback</th><th>Starts</th><th>from</th></tr></thead>
			<tbody>";
			if($delpartnerreview['data']){
				foreach($delpartnerreview['data'] as $customer){
					echo "<tr><td>".$customer->date."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->firstname."</td></tr>";
				}
			}
			if($customerreview['data']){
				foreach($customerreview['data'] as $customer1){
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->firstname."</td></tr>";
				}
			}
			if(isset($delboyreview['data']) && $delboyreview['data']){
				foreach($delboyreview['data'] as $customer1){
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->name."</td></tr>";
				}
			}
		echo "</tbody>
		</table></div>";
	}

	public function ShowReviewDetailstodelpartner($id){
		$restaurantreview = $this->Order_model->GetRestaurantreview($id);
		$delpartnerreviewavg = isset($restaurantreview['avg'][0]->avg) ? $restaurantreview['avg'][0]->avg : 0;
		$admin       = $this->Order_model->get_admin($id);
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$admin[0]->firstname."</h4>
		</div>
		<div class='modal-body'>";
		echo  "<div class=''><strong>Ratings By restaurant:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Feedback</th><th>Starts</th><th>from</th></tr></thead>
			<tbody>";
			if($restaurantreview != 0 && $restaurantreview['data']){
				foreach($restaurantreview['data'] as $customer){
					echo "<tr><td>".$customer->date."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->restaurant_name."</td></tr>";
				}
			}else{
				echo "No data found";
			}

		echo "</tbody>
		</table></div>";
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
		if($data['fromdate'] > date("Y-m-d",strtotime('today')) || $data['todate'] > date("Y-m-d",strtotime('today'))){
			echo "<script>alert('Select proper dates'); location.href='http://grazzy.way2gps.com/index.php/admin/orders/RequestBill';</script>";
		}
		$data['orders'] = $this->Order_model->get_previousorders($data);
		$userdata = $this->session->userdata('admin');
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'laxman.bigperl@gmail.com',
			'smtp_pass' => 'bigperl@123',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline'    => "\r\n"
		);
		$this->load->library('email',$config);
		$this->email->from('laxman.bigperl@gmail.com', 'EatsApp');
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
			echo "<script> alert('Your bill will be emailed to registred email id.');location.href='http://grazzy.way2gps.com/index.php/admin/orders/RequestBill';</script>";
		//	echo "Your bill will be emailed to registred email id.";
         }
		 else
		 {
			// print_r($this->email->print_debugger());
			echo "Error in sending Email.";
         }
	}
	public function SalesChart(){

	     $data['saleschart'] = "";
        $this->view($this->config->item('admin_folder').'/saleschart',$data);


	}

	public function renew(){
		$userdata = $this->session->userdata('admin');

		$sql = $this->db->query("update admin set RenewalAppliedStatus = 1 where id='".$userdata['id']."'");
		$message = "".$userdata['firstname']."(Email: ".$userdata['email'].") has requested for renewal)";
		$sql = $this->db->query("insert into notification_message (message) value('".$message."')");
		echo "<script>alert('Notification sent. admin will contact you soon');location.reload();</script>";
		 //redirect($this->config->item('admin_folder').'/orders/dashboard');
	}

}
