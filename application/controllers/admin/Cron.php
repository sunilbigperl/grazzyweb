<?php
class Cron extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		
		
	}

	public function RestCron(){
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*5);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		$sql = mysqli_query($con,"select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and restaurant_manager_status = 0 and ordered_on < '".$formatDate."'");
		while($row = mysqli_fetch_array($sql)){
			$restaurant_phone = $row['restaurant_phone'];
			$message = "<h4>Your order with order number ".$row['order_number']." is pending</h4>
			<h4>Please accept the order to avoid the penalty</h4>";
			 $config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => 'suggest@wolotech.com',
				'smtp_pass' => 'Devang123',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1',
				'crlf' => "\r\n",
				'newline' => "\r\n"
			);
			$this->load->library('email',$config);
			$this->email->from('suggest@wolotech.com', 'EatsApp');
			$this->email->to($row['restaurant_email']);
			$this->email->bcc('lvijetha90@gmail.com');
			$this->email->subject('EatsApp: Restaurant order accept pending');
			$this->email->message($message);
			echo $this->email->send();  exit;
		}
		
		public function DelpartnerCron(){
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*5);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		$sql = mysqli_query($con,"select * from orders a, admin b where a.delivery_partner = b.id and delivery_partner_status = 0 and ordered_on < '".$formatDate."'");
		while($row = mysqli_fetch_array($sql)){
			$partner_phone = $row['phone'];
			$message = "<h4>Your order with order number ".$row['order_number']." is pending</h4>
			<h4>Please accept the order to avoid the penalty</h4>";
			 $config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => 'suggest@wolotech.com',
				'smtp_pass' => 'Devang123',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1',
				'crlf' => "\r\n",
				'newline' => "\r\n"
			);
			$this->load->library('email',$config);
			$this->email->from('suggest@wolotech.com', 'EatsApp');
			$this->email->to($row['email']);
			$this->email->bcc('lvijetha90@gmail.com');
			$this->email->subject('EatsApp: Delivery partner order accept pending');
			$this->email->message($message);
			echo $this->email->send();  exit;
		}
	}
	
}