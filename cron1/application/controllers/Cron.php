<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
require (APPPATH . '/libraries/REST_Controller.php');


class Cron extends CI_Controller 
{


function __construct()
    {
        // Construct the parent class
       
        parent::__construct();
		$this->load->database();
		//$this->load->model('Main_model');
       
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }



public function RestCron(){
	
    date_default_timezone_set('Asia/Calcutta');
    $date = date('Y-m-d H:i:s');
    $currentDate = strtotime($date);
    $futureDate = $currentDate-(60*4);
    $formatDate = date("Y-m-d H:i:s", $futureDate);
    
    $sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and a.restaurant_manager_status = 0 and a.status='Order Placed' and  ordered_on < '".$formatDate."'");
    if($sql->num_rows() > 0){
      $result =  $sql->result_array();
      foreach($result as $row){
        $this->db->query("update orders set status='order cancelled' where id='".$row['id']."'");
		$query1 = $this->db->query("SELECT a.`phone` FROM `customers` a,orders b where b.customer_id=a.id and b.id=".$row['id']." ");
	     
	
	         if($query1->num_rows() > 0){
		      $res= $query1->result_array();
		
		      foreach($res as $result){
			   $registatoin_ids[0]=$result['phone'];
			
			    //print_r($registatoin_ids[0]);exit;
		     }

		    
		     $url =file("http://193.105.74.159/api/v3/sendsms/plain?user=wolotech&password=FBXM0Fv4&&sender=EATSAP&SMSText=We+regret+to+inform+you+that+the+Food+Outlet+you+selected+is+unable+to+accept+your+order+.+Please+order+from+some+other+Food+Outlet+.+We+will+refund+your+payment+within+5+working+days&type=longsms&GSM=91".$registatoin_ids[0]." ");
		     
    

	       }
		   $query2 = $this->db->query("SELECT * FROM `customers` a,orders b where b.customer_id=a.id and b.id=".$row['id']." ");
			if($query2->num_rows() > 0){
				foreach($query2->result_array() as $row){ 
					$logo1='http://eatsapp.in/login/uploads/images/3.png';
			        $image1="<img src='".$logo1."' height='150' width='150'  alt='logo'>";
					$message=" <center>".$image1." 
					         <p>Dear ".$row['firstname'].",</p>
							 <p>Thank you for placing the order with us. We regret to inform you that the order has been cancelled. We will refund the amount at earliest.</p>
							 <p><b>Order No:</b> ".$row['order_number']."</p>
							 
							 <p style=color:#bdbdbf;>152, 15th Floor, Mittal Court (B), Nariman Point, Mumbai 400021<br><a href=http://eatsapp.in style=text-decoration:none;color:#bdbdbf;>eatsapp.in</a></p>
                             </center>
					          ";
					// <h6>".$data['message']."</h6>";
						  $config = Array(
							'protocol' => 'smtp',
							'smtp_host' => 'ssl://smtp.gmail.com',
							'smtp_port' => 465,
							'smtp_user' => 'orders@eatsapp.in',
							'smtp_pass' => 'DEVANG123d',
							'mailtype'  => 'html', 
							'charset'   => 'iso-8859-1',
							'crlf' => "\r\n",
							'newline' => "\r\n"
						);
						$this->load->library('email',$config);
						$this->email->from('orders@eatsapp.in', 'eatsapp');
						$this->email->to($row['email']);
						$this->email->bcc('eatsapp.orders@gmail.com');
						$this->email->subject('eatsapp:Thanks for Placing Order on eatsapp');
						$this->email->message($message);
						$this->email->send();
						
				}
			} 
	 }
    }
  }

  
  public function DelpartnerCron(){
    date_default_timezone_set('Asia/Calcutta');
    $date = date('Y-m-d H:i:s');
    $currentDate = strtotime($date);
    $futureDate = $currentDate-(60*4);
    $formatDate = date("Y-m-d H:i:s", $futureDate);
    
    $sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and a.restaurant_manager_status ='Accepted' and a.status='Order Placed' and  ordered_on < '".$formatDate."'");
    if($sql->num_rows() > 0){
      $result =  $sql->result_array();
      foreach($result as $row){
        $this->db->query("update orders set status='order cancelled' where id='".$row['id']."'");
		$query1 = $this->db->query("SELECT a.`phone` FROM `customers` a,orders b where b.customer_id=a.id and b.id=".$row['id']." ");
	     
	
	         if($query1->num_rows() > 0){
		      $res= $query1->result_array();
		
		      foreach($res as $result){
			   $registatoin_ids[0]=$result['phone'];
			
			    //print_r($registatoin_ids[0]);exit;
		     }

		    
		     $url =file("http://193.105.74.159/api/v3/sendsms/plain?user=wolotech&password=FBXM0Fv4&&sender=EATSAP&SMSText=We+regret+to+inform+you+that+your+order+was+not+accepted+.+We+We+will+refund+your+payment+within+5+working+days&type=longsms&GSM=91".$registatoin_ids[0]." ");
		     
    

	       }
		   $query2 = $this->db->query("SELECT * FROM `customers` a,orders b where b.customer_id=a.id and b.id=".$row['id']." ");
			if($query2->num_rows() > 0){
				foreach($query2->result_array() as $row){ 
					$logo1='http://eatsapp.in/login/uploads/images/3.png';
			        $image1="<img src='".$logo1."' height='150' width='150'  alt='logo'>";
					$message=" <center>".$image1." 
					         <p>Dear ".$row['firstname'].",</p>
							 <p>Thank you for placing the order with us. We regret to inform you that the order has been cancelled. We will refund the amount at earliest.</p>
							 <p><b>Order No:</b> ".$row['order_number']."</p>
							 
							 <p style=color:#bdbdbf;>152, 15th Floor, Mittal Court (B), Nariman Point, Mumbai 400021<br><a href=http://eatsapp.in style=text-decoration:none;color:#bdbdbf;>eatsapp.in</a></p>
                             </center>
					          ";
					// <h6>".$data['message']."</h6>";
						  $config = Array(
							'protocol' => 'smtp',
							'smtp_host' => 'ssl://smtp.gmail.com',
							'smtp_port' => 465,
							'smtp_user' => 'orders@eatsapp.in',
							'smtp_pass' => 'DEVANG123d',
							'mailtype'  => 'html', 
							'charset'   => 'iso-8859-1',
							'crlf' => "\r\n",
							'newline' => "\r\n"
						);
						$this->load->library('email',$config);
						$this->email->from('orders@eatsapp.in', 'eatsapp');
						$this->email->to($row['email']);
						$this->email->bcc('eatsapp.orders@gmail.com');
						$this->email->subject('eatsapp:Thanks for Placing Order on eatsapp');
						$this->email->message($message);
						$this->email->send();
						
				}
			} 
	 }
    }
  }

  public function RestSms(){
	
   date_default_timezone_set('Asia/Calcutta');
    $date = date('Y-m-d H:i:s');
    $currentDate = strtotime($date);
    $futureDate = $currentDate-(60*2);
    $formatDate = date("Y-m-d H:i:s", $futureDate);
    
    $sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and a.restaurant_manager_status = 0 and a.delivery_partner=0 and a.status='Order Placed' and  ordered_on <='".$formatDate."'");
    //print_r($sql->num_rows());exit;
	if($sql->num_rows() > 0){
      $result =  $sql->result_array();
	  //print_r($result);exit;
      foreach($result as $row){
      
		$query1 = $this->db->query("SELECT a.`phone` FROM `customers` a,orders b where b.customer_id=a.id and b.id=".$row['id']." ");
	     
	
	         if($query1->num_rows() > 0){
		      $res= $query1->result_array();
		
		      foreach($res as $result){
			   $registatoin_ids[0]=$result['phone'];
			
			    //print_r($registatoin_ids[0]);exit;
		     }

		    
		     $url =file("http://voiceapi.kapsystem.com/Campaignapi.asmx/setcampaign?&username=wolotech&password=kap@user!123&camp_name=test&destination=7022633206&start_date=2018-08-10&end_date=2018-08-10&start_time=12:27&end_time=17:45&script_id=1123004&retry=0&interval=1&phonebook_id=0");
		     
    

	       }
		   
	 }
    }
		     
    

	       }


    

}