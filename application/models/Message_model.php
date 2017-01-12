<?php if(!defined('BASEPATH')) exit('No direct script allowed access');
class Message_model extends CI_Model
{
	
	public function get_restmessage($id){
		$query = $this->db->query("select a.*,b.restaurant_name from restaurant_messages a, restaurant b where a.restaurant_id = b.restaurant_id and a.restaurant_id=".$id."");
		//$query = $this->db->query("select *  from restaurant_messages ");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	
	public function get_delmessages(){
		$query = $this->db->query("select a.*,b.firstname from delpartner_messages a, admin b where a.delpartner_id = b.id");
		//$query = $this->db->query("select *  from restaurant_messages ");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	public function get_custmessage(){
		$query = $this->db->query("select * from customer_messages");
		
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	public function get_messagerest($data){
		$date = date('Y-m-d H:i:s');
		$rest_name = explode(") ",$data['rest_name']);
		if(isset($data['rest_nameall']) && $data['rest_nameall'] == "on"){
			$rest_name[0] = 0;
			$rest_name[1] = "";
		}
		$sql = "insert into restaurant_messages (restaurant_id,rest_name, message, date) 
		values('".$rest_name[0]."','".$rest_name[1]."','".$data['message']."','".$date."')";	
		$query = $this->db->query($sql);
		if($this->db->insert_id()){
			return $this->db->insert_id();
		}
	}
	
	public function get_notifications(){
		$query = $this->db->query("select * from notification_message");
		
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	public function insert_notifications($data){
		$date = date('Y-m-d H:i:s');
		$sql = "insert into notification_message (message, date) 
		values('".$data['message']."','".$date."')";	
		$query = $this->db->query($sql);
		if($this->db->insert_id()){
			return $this->db->insert_id();
		}
	}
	
	public function messagedel($data){
		$date = date('Y-m-d H:i:s');
		$sql = "insert into delpartner_messages (delpartner_id, message, date) 
		values('".$data['delpartner_id']."','".$data['message']."','".$date."')";	
		$query = $this->db->query($sql);
		if($this->db->insert_id()){
			return $this->db->insert_id();
		}
	}
	public function messagecust($data){
		$date = date('Y-m-d H:i:s');
		$sql = "insert into customer_messages (message, date) 
		values('".$data['message']."','".$date."')";	
		$query = $this->db->query($sql);
		
		if($this->db->insert_id()){
			$id = $this->db->insert_id();
			$query1 = $this->db->query("SELECT `did` FROM `customers`");	
			if($query1->num_rows() > 0){
				$res	= $query1->result_array();
				foreach($res as $result){
					
					$did= $result['did']; 
					if($did != ""){
						$registatoin_ids = array($did);
						$message = array("type" => "Message");    
						$url = 'https://android.googleapis.com/gcm/send';



						$fields = array(

						'registration_ids' => $registatoin_ids,

						'data' => $message,

						);



						$headers = array(

							'Authorization: key=AIzaSyCB4r56wVzKQdte4Rw8QUwoK9k7AMP0fr4',

							'Content-Type: application/json'

						);

					
						$ch = curl_init();



						// Set the url, number of POST vars, POST data

						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_POST, true);

						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

						curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));


						$result = curl_exec($ch);

						if ($result === FALSE) {

							die('Curl failed: ' . curl_error($ch));

						}


						curl_close($ch);
					}
				
				$i++;
				}
			}
			return true;
		}
	}
	public function get_restmessages(){
		$query = $this->db->query("select a.*,b.restaurant_name from restaurant_messages a, restaurant b where a.restaurant_id = b.restaurant_id");
		//$query = $this->db->query("select *  from restaurant_messages ");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	public function get_restaurants(){
		$query = $this->db->query("select restaurant_id, restaurant_name, restaurant_branch from restaurant");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row['restaurant_id'].") ".$row['restaurant_name']." ".$row['restaurant_branch'];
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
	
	public function get_delpartners(){
		$query = $this->db->query("select id, firstname from admin where access= 'Deliver manager'");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	}
		public function get_restaurants1(){
		$query = $this->db->query("select restaurant_id,restaurant_name from restaurant");
		if($query->num_rows() > 0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[] = $row;
			$i++;
			}
			return $result;
		}else{
			return 0;
		}
	
	}
}