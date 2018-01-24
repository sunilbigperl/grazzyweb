<?php
class Cron extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		
		
	}

	public function ActivateUser(){
		$date = date("Y-m-d H:i:s" ,strtotime("+3 months"));
		$sql = $this->db->query("select * from customers where active = 2");
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $res){
				if($res['DeactivatedDate'] >= $date){
					$this->db->query("update customers set DeactivatedDate= '".$res['DeactivatedDate']."' and active = 1 where id='".$res['id']."'");
				}
			}
		}
	}
	
	public function RestCron(){
		date_default_timezone_set('Asia/Calcutta');
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*5);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		
		$sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and restaurant_manager_status = 0 and ordered_on < '".$formatDate."'");
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $row){
				$this->db->query("update orders set status='order cancelled' where id='".$row['id']."'");
				$restaurant_phone = $row['restaurant_phone'];
				$message = "<h4>Your order with order number ".$row['order_number']." is pending</h4>
				<h4>Please accept the order to avoid the penalty</h4>";
				 $config = Array(
					'protocol' => 'smtp',
					'smtp_host' => 'ssl://smtp.gmail.com',
					'smtp_port' => 465,
					'smtp_user' => 'suggest.eatsapp@gmail.com',
					'smtp_pass' => 'devang123',
					'mailtype'  => 'html', 
					'charset'   => 'iso-8859-1',
					'crlf' => "\r\n",
					'newline' => "\r\n"
				);
				$this->load->library('email',$config);
				$this->email->from('suggest@eatsapp.in', 'EatsApp');
				$this->email->to($row['restaurant_email']);
				$this->email->bcc('lvijetha90@gmail.com');
				$this->email->subject('EatsApp: Restaurant order accept pending');
				$this->email->message($message);
				 $this->email->send(); 
			}
		}
	}
	public function DelpartnerCron(){
		date_default_timezone_set('Asia/Calcutta');
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*5);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		$sql = $this->db->query("select * from orders a, admin b where a.delivery_partner = b.id and delivery_partner_status = 0 and ordered_on < '".$formatDate."'");
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $row){
			$partner_phone = $row['phone'];
			$message = "<h4>Your order with order number ".$row['order_number']." is pending</h4>
			<h4>Please accept the order to avoid the penalty</h4>";
			 $config = Array(
				'protocol' => 'smtp',
				'smtp_host' => 'ssl://smtp.gmail.com',
				'smtp_port' => 465,
				'smtp_user' => 'suggest.eatsapp@gmail.com',
				'smtp_pass' => 'devang123',
				'mailtype'  => 'html', 
				'charset'   => 'iso-8859-1',
				'crlf' => "\r\n",
				'newline' => "\r\n"
			);
			$this->load->library('email',$config);
			$this->email->from('suggest@eatsapp.in', 'EatsApp');
			$this->email->to($row['email']);
			$this->email->bcc('lvijetha90@gmail.com');
			$this->email->subject('EatsApp: Delivery partner order accept pending');
			$this->email->message($message);
			 $this->email->send(); 
			}
		}
	}


	public function Restsms(){
		date_default_timezone_set('Asia/Calcutta');
		$date = date('Y-m-d H:i:s');
		//print_r($date);exit;
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*2);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		//print_r($formatDate);exit;
		//print_r($formatDate);exit;
		
		$sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and restaurant_manager_status = 0 and ordered_on < '".$formatDate."'");
		
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $row){
				$this->db->query("update orders set status='order accept' where id='".$row['id']."'");
				//echo "update orders set status='order accept' where id='".$row['id']."'";exit;
                $restaurant_phone = $row['restaurant_phone'];
                //print_r($restaurant_phone);exit;
				
				$url =file("http://193.105.74.159/api/v3/sendsms/plain?user=wolotech&password=FBXM0Fv4&&sender=EATSAP&SMSText=We+regret+to+inform+you+that+the+Food+Outlet+you+selected+is+unable+to+accept+your+order+.+Please+order+from+some+other+Food+Outlet+.+We+will+refund+your+payment+within+5+working+days&type=longsms&GSM=91".$restaurant_phone." ");
			}
		
	}
	
}

public function Delpartnersms(){
		date_default_timezone_set('Asia/Calcutta');
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate+(60*2);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		$sql = $this->db->query("select * from orders a, admin b where a.delivery_partner = b.id and delivery_partner_status = 0 and ordered_on < '".$formatDate."'");
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $row){
			$partner_phone = $row['phone'];
			$url =file("http://193.105.74.159/api/v3/sendsms/plain?user=wolotech&password=FBXM0Fv4&&sender=EATSAP&SMSText=We+regret+to+inform+you+that+the+Food+Outlet+you+selected+is+unable+to+accept+your+order+.+Please+order+from+some+other+Food+Outlet+.+We+will+refund+your+payment+within+5+working+days&type=longsms&GSM=91".$partner_phone." ");
			
			}
		}
	}
}