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
	public function get_messagerest($data){
		$date = date('Y-m-d H:i:s');
		$rest_name = explode(") ",$data['rest_name']);
		$sql = "insert into restaurant_messages (restaurant_id,rest_name, message, date) 
		values('".$rest_name[0]."','".$rest_name[1]."','".$data['message']."','".$date."')";	
		$query = $this->db->query($sql);
		if($this->db->insert_id()){
			return $this->db->insert_id();
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
}