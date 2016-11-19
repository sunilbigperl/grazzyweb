<?php if(!defined('BASEPATH')) exit('No direct script allowed access');
class Api_model extends CI_Model
{
	
	public function customercheck($data){
		$sql = "select id from customers where firstname='".$data['firstname']."' and phone ='".$data['phone']."'";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0){
			$sql = "insert into customers (firstname, phone) values('".$data['firstname']."','".$data['phone']."')";	
			$query = $this->db->query($sql);
			$id = $this->db->insert_id();
			
		}else{
			$id = $query->result();
		}
		return $id;
	}
	
	public function getUsers(){
		
		$threadmsg = $this->db->query("select * from customers");

			if($threadmsg->num_rows()>0){

				return $threadmsg->result_array();

			}else{
			
				return false;
				
			}
			
		
	}
	
	public function getRestaurants($id){
		
		$threadmsg = $this->db->query("select a.* from restaurant a, pitstops b, pitstop_restaurants c where 
		a.restaurant_id = c.restaurants_id and b.pitstop_id=c.pitstop_id and b.pitstop_id=".$id);

			if($threadmsg->num_rows()>0){
				$result = array();
				$i=0;
				foreach($threadmsg->result_array() as $row){ 
					$result[$i]['restaurant_id'] = $row['restaurant_id'];
					$result[$i]['restaurant_name'] = $row['restaurant_name'];
					$result[$i]['image'] = 'uploads/images/thumbnails/'.$row['image']; 
				$i++;
				}
				return $result;
			}else{
			
				return false;
				
			}
			
		
	}
	
	public function getAddress($id){
		
		$addresses = $this->db->where('customer_id', $id)->get('customers_address_bank')->result_array();
		
        // unserialize the field data
        if($addresses)
        {
            foreach($addresses as $add)
            {
                $addr[] = unserialize($add['field_data']);
            }
			return $addr;
        }
        else{
			return false;
		}
		
	}
	
	public function adduserslocation($data){
		$sql = "insert into customer_locations (customer_id,latitude,langitude) values('".$data['customer_id']."','".$data['latitude']."','".$data['langitude']."')";
		//echo $sql; exit;
		$query = $this->db->query($sql);
		if($query){
			return true;
		}
	}
	public function RestaurantMenuDetails($input_data){
		
		$sql=$this->db->query("select id,name,logo  from restaurants where id=".$input_data);
		$i=0;
		if($sql->num_rows()>0){
			$result[$i] = true;
			$data = $sql->result_array();
			$result['data']['ID'] = $data[0]['id'];
			$result['data']['RestaurantName'] = $data[0]['name'];
			$restorent_logo_path=$data[0]['logo'];
			$restorent_logo_path=$this->config->base_url()."images/".$restorent_logo_path;
			$result['data']['Image']=$restorent_logo_path;
			

			$group_sql = $this->db->query("select * from groups where restaurant_id=".$data[0]['id']);	

			$group_count=0;
			foreach($group_sql->result_array() as $group_data){

			    $result['data']['RestaurantMenu'][$group_count]['ID'] = $group_data['id'];
			    $result['data']['RestaurantMenu'][$group_count]['Name'] = $group_data['name'];
			    
				$dish_sql = $this->db->query("select * from dishes join dish_prices on dishes.id= dish_prices.dish_id where dishes.restaurant_id=".$group_data['restaurant_id']." and dishes.group_id=".$group_data['id']);

				//echo $this->db->last_query();

				$dish_count=0;
				foreach($dish_sql->result_array() as $dish_data){

//
					$dish_logo_path=$dish_data['image'];
					$dish_logo_path=$this->config->base_url()."images/".$dish_logo_path;
				
					//print_r($dish_data); exit;

					$result['data']['RestaurantMenu'][$group_count]['CategoryDish'][$dish_count]['ID'] = $dish_data['id'];
			    	$result['data']['RestaurantMenu'][$group_count]['CategoryDish'][$dish_count]['DishName'] = $dish_data['name'];
			    	$result['data']['RestaurantMenu'][$group_count]['CategoryDish'][$dish_count]['Image'] = $dish_logo_path;
			    	$result['data']['RestaurantMenu'][$group_count]['CategoryDish'][$dish_count]['Description'] = $dish_data['description'];
			    	$result['data']['RestaurantMenu'][$group_count]['CategoryDish'][$dish_count]['Rate'] = 'KD '.$dish_data['price'];

			    	$dish_count++;

				}
					
				$group_count++;
			}


		}else{
			$result[0] = false;
		}	
		return json_encode($result);
	}
}