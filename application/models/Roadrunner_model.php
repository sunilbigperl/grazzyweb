<?php if(!defined('BASEPATH')) exit('No direct script allowed access');
class Roadrunner_model extends CI_Model
{
	
	public function GetToken(){
		$endpoint = "http://apitest.roadrunnr.in/oauth/token";
		$postData = "client_id=BZbxIpNrEjwHeLGTOlxOH6eaAxP5VHXygjPHsv6p&client_secret=x3rQBeVW64dez1xZnkZbmZSXJnVg5AuXj0e4C1gw&grant_type=client_credentials";

		$curl = curl_init($endpoint);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER,'Content-Type: application/x-www-form-urlencoded');
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

		$json_response = curl_exec($curl);
		$result = json_decode($json_response)->access_token;
		return $result;
	}
	
	public function TrackOrder($orderid){
		$token = $this->GetToken();
		$ch = curl_init("http://apitest.roadrunnr.in/v1/orders/".$orderid."/track"); 

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));

		 
		$result =  curl_exec($ch);
		return $result;
	}
	public function CheckServicability($data){
		$token = $this->GetToken();
		$restaurant = $data['restaurant'];
		$order = $data['order'];
		$constomer = $data['customer'];
		
		$Datapost = '{
			"pickup": {
				"user": {
					"name": "'.$restaurant->restaurant_name.'",
					"phone_no": "'.$restaurant->restaurant_phone.'",
					 "type": "merchant",
					 "full_address": {
						 "address": "'.$data['fromaddress'].'",
						 "city": {
							"name": "'.$data['fromcity'].'"
						 },
						 "geo": {
							"latitude": "'.$restaurant->restaurant_latitude.'",
							"longitude": "'.$restaurant->restaurant_langitude.'"
						}
					 }
				 }
			 },
			 "drop": {
				 "user": {
					 "name": "'.$constomer->firstname.'",
					 "phone_no": "'.$constomer->phone.'",
					 "type": "customer",
					 "full_address": {
					 "address": "'.$data['toaddress'].'", 
					 "geo": {
							"latitude": "'.$order->shipping_lat.'",
							"longitude": "'.$order->shipping_long.'"
						},
						"city": {
							"name": "'.$data['tocity'].'" 
						}
					 }
				 }
			 },
			 "order_details": {
				 "order_id": "'.$order->order_number.'",
				 "order_value": "'.$order->total_cost.'",
				 "amount_to_be_collected": "'.$order->total_cost.'",
				 "amount_to_be_paid": "'.$order->total_cost.'",
				 "order_type": {
					"name": "CashOnDelivery"
				 }
			 },
			 "created_at": "'.$order->ordered_on.'"
			 }';
	
			$ch = curl_init("http://apitest.roadrunnr.in/v1/orders/ship"); 

			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $Datapost);
			 
			$result =  curl_exec($ch);
			return $result;
	}
}