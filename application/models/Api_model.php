<?php if(!defined('BASEPATH')) exit('No direct script allowed access');
class Api_model extends CI_Model
{
	
	public function customercheck($data){
		$sql = "select * from customers where  phone ='".$data['phone']."' and active = 1";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0){
			$sql = "insert into customers (phone, did, active) values('".$data['phone']."','".$data['did']."', 1)";	
			$query = $this->db->query($sql);
			$result['id'] = $this->db->insert_id();
			$result['email'] = "";
		}else{
			$datas = $query->result_array();
			if($datas[0]['did'] == $data['did']){
				$result['id'] =  $datas[0]['id'];
				$sql = $this->db->query("update customers set did = '".$data['did']."'  where  id='".$result['id']."'");	
				$result['firstname'] = isset($datas[0]['firstname']) ? $datas[0]['firstname'] : "";
				//to get email
				$result['email'] = isset($datas[0]['email']) ? $datas[0]['email'] : "";
			}else{
				$message = array("msg" => "logout");      
				$url = 'https://android.googleapis.com/gcm/send';

				$fields = array(
					'registration_ids' => array($datas[0]['did']),
					'data' => $message,
				);
				
				$headers = array(
					'Authorization: key=AIzaSyDqoeeH8ACrf31vMWG9bs2R8QCaFkfB5ZI',
					'Content-Type: application/json'
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result1 = curl_exec($ch);
				if ($result1 === FALSE) {
					$result1 = false;
				}
				curl_close($ch);
				$result['id'] =  $datas[0]['id'];
			
				$sql = $this->db->query("update customers set did = '".$data['did']."'  where  id='".$result['id']."'");	
				$result['firstname'] = isset($datas[0]['firstname']) ? $datas[0]['firstname'] : "";
				//to get email
				$result['email'] = isset($datas[0]['email']) ? $datas[0]['email'] : "";

			}
		}
		return $result;
	}
	
	public function UpdateUser($data){
		$sql = "update customers set firstname = '".$data['firstname']."'  where  id='".$data['id']."' ";	
		
		$query = $this->db->query($sql);
		if($query){
			$result[0] = true;
		}else{
			$result[0] = false;
		}
		return $result;
	}
	public function delboycheck($data){
		$sql = "select id from delivery_boy where phone ='".$data['phone']."'";
		$query = $this->db->query($sql);
		if($query->num_rows() == 0){
			$sql = "insert into delivery_boy (name, phone,did) values('".$data['firstname']."','".$data['phone']."','".$data['did']."')";	
			$query = $this->db->query($sql);
			$id = $this->db->insert_id();
			
		}else{
			$sql = $this->db->query("update delivery_boy set name='".$data['firstname']."', did='".$data['did']."' where phone='".$data['phone']."'");	
			
			$id = $query->result();
		}
		return $id;
	}
	
	public function delboyTorestfeedback($data){
		$date = date("Y-m-d H:i:s");
		$sql = "insert into feedback (feedbackfrom, feedbackto, comments,ratings,feedbacktype,order_number,date) 
		values('".$data['feedbackfrom']."','".$data['feedbackto']."','".$data['comments']."','".$data['ratings']."','".$data['feedbacktype']."','".$data['order_number']."','".$date."')";	
		
		$query = $this->db->query($sql);
		if($this->db->insert_id()){
			return "success";
		}
	}
	
	public function getUsers(){
		
		$threadmsg = $this->db->query("select * from customers");

			if($threadmsg->num_rows()>0){

				return $threadmsg->result_array();

			}else{
			
				return false;
				
			}
			
		
	}
	
	public function getHereList(){
		
		$threadmsg = $this->db->query("select * from here order by name asc");

			if($threadmsg->num_rows()>0){
				$i=0;
				foreach($threadmsg->result_array() as $row){ 
					$result[$i]['id'] = $row['id'];
					$result[$i]['name'] = $row['name'];
				$i++;
				}
				return $result;

			}else{
			
				return false;
				
			}
		
	}
	
	public function SearchRest($data){
			$date = date("Y-m-d");
			date_default_timezone_set('Asia/Calcutta');
			$time = date('H:i:s',time());
			$where ='';
			
			if(isset($data['area']) && $data['area'] != ""){
				$sql ="SELECT *,( 3959 * acos( cos( radians('".$data['latitude']."') ) * cos( radians( restaurant_latitude ) ) * cos( radians( restaurant_langitude ) - radians('".$data['langitude']."') ) + sin( radians('".$data['latitude']."') ) * sin( radians( restaurant_latitude ) ) ) ) AS distance FROM restaurant   HAVING distance < 4 and restaurant_address like '%".$data['area']."%'
				and enabled=1 and `delete`=0";
			}
			if(isset($data['name']) && $data['name'] != ""){
				$sql="SELECT *,( 3959 * acos( cos( radians('".$data['latitude']."') ) * cos( radians( restaurant_latitude ) ) * cos( radians( restaurant_langitude ) - radians('".$data['langitude']."') ) + sin( radians('".$data['latitude']."') ) * sin( radians( restaurant_latitude ) ) ) ) AS distance FROM restaurant   HAVING distance < 40 and enabled = 1 and  `restaurant_name` like  '%".$data['name']."%' and `delete`=0" ;
			
			}
			//echo $sql; exit;
			$threadmsg = $this->db->query($sql);

			if($threadmsg->num_rows()>0){
				$result = array();
				$i=0;
				foreach($threadmsg->result_array() as $row){ 
					
					$days = unserialize($row['days']);
					
					$days1 = Array (1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday' );
					$day =  $days1[date("N")];
					
					if(in_array($day,$days) && ($row['fromtime'] == "00:00:00" && $row['totime'] == "00:00:00") || ($row['fromtime'] <= $time && $row['totime'] >= $time)){
					
						$result[$i]['restaurant_id'] = $row['restaurant_id'];
						$result[$i]['restaurant_name'] = $row['restaurant_name'];
						$result[$i]['restaurant_latitude'] = $row['restaurant_latitude'];
						$result[$i]['restaurant_langitude'] = $row['restaurant_langitude'];
						$result[$i]['image'] = $row['image'];
					}
				$i++;
				}
				return $result;	
			}else{
			
				return false;
				
			}
		
	}
	
	public function delboyOrders($id){
		//echo "select * from orders a , customers b, restaurant c where a.customer_id=b.id and a.restaurant_id = c.restaurant_id and a.delivered_by != 0 and a.delivered_by='".$id."' and a.order_type != 3"; exit;
		$threadmsg = $this->db->query("select a.*, b.firstname,b.lastname, b.email, b.phone, c.* from orders a , customers b, restaurant c 
		where a.customer_id=b.id and a.restaurant_id = c.restaurant_id and a.delivered_by != 0  and a.delivered_by='".$id."' and a.order_type != 3");

			if($threadmsg->num_rows()>0){
				foreach($threadmsg->result_array() as $row){ 
					$result[] = $row;
				}
				return $result;
			}else{
			
				return false;
				
			}
			
		
	}
	
	public function Getcoordinates($id){
		$sql = $this->db->query("select * from here where id='".$id."'");
		if($sql->num_rows()>0){
			foreach($sql->result_array() as $row){ 
					$result[] = $row;
				}
				return $result;
		}else{
		
			return false;
			
		}
	}
	
	public function getRestaurants($id){
		$date = date("Y-m-d");
		date_default_timezone_set('Asia/Calcutta');
		$time = date('H:i:s',time());
		$threadmsg = $this->db->query("select a.* from restaurant a, pitstops b, pitstop_restaurants c, admin d where 
		a.restaurant_id = c.restaurants_id and b.pitstop_id=c.pitstop_id  and d.id = a.restaurant_manager and 
		d.NextRenewalDate >= '".$date."' and b.pitstop_id='".$id."' and a.enabled=1 and a.`delete`=0");

			if($threadmsg->num_rows()>0){
				
				$result = array();
				$i=0;
				foreach($threadmsg->result_array() as $row){ 
					
					$days = unserialize($row['days']);
					
					$days1 = Array (1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday' );
					$day =  $days1[date("N")];
					
					if(in_array($day,$days) && ($row['fromtime'] == "00:00:00" && $row['totime'] == "00:00:00") || ($row['fromtime'] <= $time && $row['totime'] >= $time)){
					
						$result[$i]['restaurant_id'] = $row['restaurant_id'];
						$result[$i]['restaurant_name'] = $row['restaurant_name'];
						$result[$i]['commission'] = $row['commission'];
						$result[$i]['penalty'] = $row['penalty'];
						$result[$i]['servicetax'] = $row['servicetax'];
						$result[$i]['image'] = 'uploads/images/thumbnails/'.$row['image']; 
					}
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
			$i=0;
            foreach($addresses as $add)
            {
				
                $addr[$i] = unserialize($add['field_data']);
				$addr[$i]['id'] = $add['id'];
				$addr[$i]['company'] = $add['Entry_name'];
			$i++;
            }
			return $addr;
        }
        else{
			return false;
		}
		
	}
	
	 function saveAddress($data)
    {
        $data['field_data'] = serialize($data['field_data']);
       
        if(!empty($data['id']))
        {
            $this->db->where('id', $data['id']);
            $this->db->update('customers_address_bank', $data);
            return $data['id'];
        } else {
			///print_r($data);exit;
            $this->db->insert('customers_address_bank', $data);
            return $this->db->insert_id();


        }
    }
	
	public function pitstopsuser($data){
		$sql = "SELECT * FROM `pitstops` WHERE `latitude` > '".$data['southwest_lat']."' and `latitude` < '".$data['northeast_lat']." and `delete`=0'
		and `langitude` > '".$data['southwest_lng']."' and`langitude` < '".$data['northeast_lng']."' and enabled = 1";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[$row['pitstop_id']]['pitstop_id'] = $row['pitstop_id'];
				$result[$row['pitstop_id']]['latitude'] = $row['latitude'];
				$result[$row['pitstop_id']]['langitude'] = $row['langitude'];
				$sql1 = "SELECT * FROM `pitstop_restaurants` a, restaurant b where a.pitstop_id ='".$row['pitstop_id']."' and a.restaurants_id = b.restaurant_id";
				$query1 = $this->db->query($sql1);
				if($query1->num_rows()>0){
					$j=0;
					foreach($query->result_array() as $row){ 
						$result[$row['pitstop_id']][$j]['restaurant_name'] = $row['restaurant_name'];
						$result[$row['pitstop_id']][$j]['image'] = 'uploads/images/thumbnails/'.$row['image'];
					$j++;
					}
				}
			$i++;
			}
		}
	}
	
	public function restaurantuser($data){
		
			$date = date("Y-m-d");
			date_default_timezone_set('Asia/Calcutta');
			$time = date('H:i:s',time());
			$threadmsg = $this->db->query("SELECT *,( 3959 * acos( cos( radians('".$data['latitude']."') ) * cos( radians( restaurant_latitude ) )
			* cos( radians( restaurant_langitude ) - radians('".$data['langitude']."') ) + 
			sin( radians('".$data['latitude']."') ) * sin( radians( restaurant_latitude ) ) ) ) AS distance 
			FROM restaurant HAVING distance < 2 and enabled = 1 and `delete`=0");

			if($threadmsg->num_rows()>0){
				
				$result = array();
				$i=0;
				foreach($threadmsg->result_array() as $row){ 
					
					$days = unserialize($row['days']);
					
					$days1 = Array (1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday' );
					$day =  $days1[date("N")];
					
					if(in_array($day,$days) && ($row['fromtime'] == "00:00:00" && $row['totime'] == "00:00:00") || ($row['fromtime'] <= $time && $row['totime'] >= $time)){
					
						$result[] = $row;
					}
				$i++;
				}
				return $result;
			}else{
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
	
	public function adddelboylocation($data){
		$sql = "insert into deliveryboy_locations (deliveryboy_id,latitude,langitude) values('".$data['deliveryboy_id']."','".$data['latitude']."','".$data['langitude']."')";
		//echo $sql; exit;
		$query = $this->db->query($sql);
		if($query){
			return true;
		}
	}
	
	public function orderlistnotshipped($id){
		$sql = "SELECT * FROM `orders`a WHERE a.`customer_id` = ".$id." and a.status!='Shipped' and a.status!='payment pending' order by a.ordered_on desc";
		
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[$i]['order_id'] = $row['id'];
				$sql1 = "select restaurant_name from restaurant where restaurant_id='".$row['restaurant_id']."'";

				$query1 = $this->db->query($sql1);

				if($query1->num_rows()>0){

					$res = $query1->result_array();
					$result[$i]['restaurant_name'] = $res[0]['restaurant_name'];
				}else{
					$result[$i]['restaurant_name'] = "";
				}
                
				$result[$i]['order_type'] = $row['order_type'];
				$result[$i]['delivered_by'] = $row['delivered_by'];
				$result[$i]['passcode'] = $row['passcode'];
				$result[$i]['total_cost'] = $row['total_cost'];
				$result[$i]['order_number'] = $row['order_number'];
				
				$sql2 = "select a.menu,b.* from restaurant_menu a, order_items b where b.order_id='".$row['id']."' and a.menu_id=b.menu_id and a.`delete`=0";
				$query2 = $this->db->query($sql2);
				//print_r($query2->result_array()); exit;
				if($query2->num_rows()>0){
					$j=0;
					foreach($query2->result_array() as $row1){
						$result[$i]['items'][]=$row1['menu'];
					$j++;
					}
				}
				
				if(isset($result[$i]['items'])){	
					$result[$i]['items']= implode(",",$result[$i]['items']);
				}
				$result[$i]['shipping_lat'] = $row['shipping_lat'];
				$result[$i]['shipping_long'] = $row['shipping_long'];
				if($result[$i]['order_type'] == 1 || $result[$i]['order_type'] == 2 || $result[$i]['order_type'] == 4){
					if($result[$i]['delivered_by'] != 0){
						$sql4  = $this->db->query("select * from delivery_boy where id='".$result[$i]['delivered_by']."'");
						 
						//$res_del = $sql4->num_rows();
						 if($sql4->num_rows() > 0){
						 	$res_del = $sql4->result_array();
							$result[$i]['name'] = $res_del[0]['name'];
							$result[$i]['phone'] = isset($res_del[0]['phone']) ? $res_del[0]['phone'] : "";
						}else{
							$result[$i]['name'] = 0;
							$result[$i]['phone'] = 0;
						}
					}else{
						$result[$i]['name'] = 0;
						$result[$i]['phone'] = 0;
					}

				}elseif($result[$i]['order_type'] == 3){
					if($result[$i]['delivered_by'] != 0){
						$sql4  = $this->db->query("select * from restaurant where restaurant_id='".$result[$i]['delivered_by']."'");
						$res_del = $sql4->result_array();
						$result[$i]['name'] = $res_del[0]['restaurant_name'];
						$result[$i]['phone'] = $res_del[0]['restaurant_phone'];
					}else{
						$result[$i]['name'] = 0;
						$result[$i]['phone'] = 0;

					}

				}else{
					$result[$i]['name'] = 0;
					$result[$i]['phone'] = 0;

				}

			$i++;
			}
			return $result;
		}else{
			return false;
		}
	}
	public function orderlist($id){
		$sql = "SELECT * FROM `orders`a WHERE a.`customer_id` = ".$id." and a.status='Shipped'  order by a.ordered_on desc";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$result = array();
			$i=0;
			foreach($query->result_array() as $row){ 
				$result[$i]['order_number'] = $row['id'];
				$sql1 = "select restaurant_name from restaurant where restaurant_id='".$row['restaurant_id']."'";
				$query1 = $this->db->query($sql1);
				if($query1->num_rows()>0){
					$res = $query1->result_array();
					$result[$i]['restaurant_name'] = $res[0]['restaurant_name'];
				}
				$sql2 = "select a.menu,b.* from restaurant_menu a, order_items b where b.order_id='".$row['id']."' and a.menu_id=b.menu_id and a.`delete`=0";
				
				$query2 = $this->db->query($sql2);
				if($query2->num_rows()>0){
					$j=0;
					foreach($query2->result_array() as $row1){
						
						$result[$i]['items'][$j]['id'] = $row1['id'];
						$result[$i]['items'][$j]['ordered_on'] = $row['ordered_on'];
						$result[$i]['items'][$j]['menu_id'] = $row1['menu_id'];
						$result[$i]['items'][$j]['menu'] = $row1['menu'];
						$result[$i]['items'][$j]['quantity'] = $row1['quantity'];
						$result[$i]['items'][$j]['cost'] = $row1['cost'];
					$j++;
					}
				}
			$i++;
			}
			return $result;
		}else{
			return false;
		}
	}
	

	// public function orderlist($id){
	// 	$sql = "SELECT * FROM `orders`a WHERE a.`customer_id` = ".$id." and a.status='Shipped'  order by a.ordered_on desc";
	// 	$query = $this->db->query($sql);
	// 	if($query->num_rows()>0){
	// 		$result = array();
	// 		$i=0;
	// 		foreach($query->result_array() as $row){ 
	// 			$result[$i]['order_number'] = $row['id'];
	// 			// $sql1 = "select restaurant_name from restaurant where restaurant_id='".$row['restaurant_id']."'";
	// 			$sql2 = "select a.menu,c.restaurant_name,b.* from restaurant_menu a, order_items b,restaurant c where b.order_id='".$row['id']."' and a.menu_id=b.menu_id and c.restaurant_id='".$row['restaurant_id']."' and a.`delete`=0";
	// 			$query1 = $this->db->query($sql2);
	// 			if($query1->num_rows()>0){
	// 				$res = $query1->result_array();
	// 				$result[$i]['order_number'] = $row['id'];
	// 				$result[$i]['restaurant_name'] = $res[0]['restaurant_name'];
	// 			}
			 	
				
	// 			$query2 = $this->db->query($sql2);

	// 			if($query2->num_rows()>0){

	// 				$j=0;
	// 				foreach($query2->result_array() as $row1){
	// 					$result[$i]['items'][$j]['id'] = $row1['id'];
	// 					$result[$i]['items'][$j]['ordered_on'] = $row['ordered_on'];
	// 					$result[$i]['items'][$j]['menu_id'] = $row1['menu_id'];
	// 					$result[$i]['items'][$j]['menu'] = $row1['menu'];
	// 					$result[$i]['items'][$j]['quantity'] = $row1['quantity'];
	// 					$result[$i]['items'][$j]['cost'] = $row1['cost'];
	// 				$j++;
	// 				}
	// 			}
	// 		$i++;
	// 		}
	// 		return $result;
	// 	}else{
	// 		return false;
	// 	}
	// }
	public function updateprofile($data){
		$sql = "update customers set firstname='".$data['firstname']."',email='".$data['email']."',dob='".$data['dob']."',gender='".$data['gender']."' where id='".$data['id']."'";
		//echo $sql; exit;
		$query = $this->db->query($sql);
		if($query){
			return true;
		}
	}
	public function changeorderstatus($data){
		$sql = "update orders set status='".$data['status']."', distance= '".$data['distance']."' where id='".$data['id']."'";
		$query = $this->db->query($sql);
		if($query){
			return "success";
		}
	}
	public function getMenus($id){
		$date = date('Y-m-d H:i:s');
		
		// $sql3 = $this->db->query("select servicetax,delivery_charge from restaurant where restaurant_id='".$id."'");
		$sql3 = $this->db->query("select servicetax,deliverycharge,minordervalue from charges order by 
			start_date desc limit 1 ");
		$servicetax =  $sql3->result_array();
		
		$sql ="SELECT DISTINCT b.category_id,  c.parent_id, c.name,d.discount1,d.discount2,d.reimb FROM `restaurant_menu` a, menu_categories b, categories c,restaurant d where 
		a.restaurant_id = '".$id."' and a.menu_id = b.menu_category and b.category_id = c.id and a.`delete`=0 and a.`enabled`=1 order by c.`id`";

		// $sql ="SELECT DISTINCT b.category_id,  c.parent_id, c.name FROM `restaurant_menu` a, menu_categories b, categories c where 
		// a.restaurant_id = '".$id."' and a.menu_id = b.menu_category and b.category_id = c.id and a.`delete`=0 and a.`enabled`=1 order by c.`name`";
		
		$query = $this->db->query($sql);
		$result = array();
		if($query->num_rows()>0){
			$data = $query->result_array();
			$i=0;
			foreach($data as $menu){
				if($menu['parent_id'] == 0){
					$result[$i]['category_id'] = $menu['category_id'];
					$result[$i]['category'] = $menu['name'];
					$result[$i]['discount1'] = $menu['discount1'];
					$result[$i]['discount2'] = $menu['discount2'];
					$result[$i]['reimb'] = $menu['reimb'];
					$result[$i]['servicetax'] =  $servicetax[0]['servicetax'];
					$result[$i]['delivery_charge'] =  $servicetax[0]['deliverycharge'];
					$result[$i]['minordervalue'] =  $servicetax[0]['minordervalue'];
					$sql1 ="SELECT *,a.description FROM `restaurant_menu` a, menu_categories b, categories c where a.restaurant_id = '".$id."' and b.category_id='".$menu['category_id']."' 
					and a.menu_id = b.menu_category and b.category_id = c.id and a.`delete`=0 and a.`enabled`=1";
					//echo $sql1; exit;
                      
					$query1 = $this->db->query($sql1);
				 
					if($query1->num_rows()>0){
						$data1 = $query1->result_array();
						
						$j=0;
						foreach($data1 as $mn){
							
							$sqlq =  $this->db->query("select b.name from menu_categories a, categories b where a.category_id = b.id and a.menu_category ='".$mn['menu_id']."' and b.parent_id='".$menu['category_id']."'");
							if($sqlq->num_rows()>0){
								$parent = $sqlq->result_array();
                                $result[$i]['menus'][$j]['subcat'] = $parent;

								
							}

							$result[$i]['menus'][$j]['menu_id'] = $mn['menu_id'];
							$result[$i]['menus'][$j]['menu'] = $mn['menu'];
							$result[$i]['menus'][$j]['description'] = $mn['description'];
							$result[$i]['menus'][$j]['price'] = $mn['price'];
							$result[$i]['menus'][$j]['size'] = $mn['size'];
							$result[$i]['menus'][$j]['image'] = 'uploads/images/thumbnails/'.$mn['image'];
							$result[$i]['menus'][$j]['type'] = $mn['type'];
							$result[$i]['menus'][$j]['itemPreparation_time'] = $mn['itemPreparation_time'];	
							
							
							if($mn['customisation'] != "" && strlen($mn['customisation']) > 5){
								$cust = unserialize($mn['customisation']);
								$data= array();
								$l=0;
								foreach($cust as $str){
									$data[$l]['type'] = $str['type'];
									$data[$l]['name'] = $str['name'];
									if(isset($str['values']) && count($str['values']) > 0){
										$m=0;
										foreach($str['values'] as $value){
											$data[$l]['values'][$m]['name'] = $value['name'];
											$data[$l]['values'][$m]['weight'] = $value['weight'];
											$data[$l]['values'][$m]['price'] = $value['price'];
										$m++;
										}		
									}
								$l++;
								}
							}else{
								$data= false;
							}

							$result[$i]['menus'][$j]['customisation'] = $data;
						$j++;
						}
					}
					$i++;
				}
			
			}
		}
		//print_r($result); exit;
		return $result;
	}
	
	public function pitstopsuser1($data){
		$sql = $this->db->query("select * from pitstops where enabled=1 and `delete`=0");
		if($sql->num_rows()>0){
			$data = $sql->result_array();
			$i=0;
			foreach($data as $pitstop){
				$result[$i] = $pitstop;
				$sql1=$this->db->query("select b.image from pitstop_restaurants a, restaurant b where a.pitstop_id='".$pitstop['pitstop_id']."' and a.restaurants_id=b.restaurant_id");
				if($sql1->num_rows()>0){
					$data1 = $sql1->result_array();
					foreach($data1 as $rest){
						$result[$i]['restaurants'][] = "uploads/images/thumbnails/".$rest['image'];
					}
				}
			$i++;
			}
		}else{
			$result =0;
		}
		print_r(json_encode($result)); exit;
	}


	
	public function restaurantSuggest($data){

		$sql =$this->db->query("insert into  restaurant_suggest (restaurant_name,restaurant_phone,restaurant_address,restaurant_email,customer) 
		values('".$data['restaurant_name']."','".$data['restaurant_phone']."','".$data['restaurant_address']."','".$data['restaurant_email']."','".$data['customer']."')");
		
		if($sql){
		$message="<h3>New restaurant suggestion</h3>
	     <h6>Restaurant_name: ".$data['restaurant_name']."</h6>
		<h6>Restaurant phone: ".$data['restaurant_phone']."</h6>
		<h6>Restaurant address: ".$data['restaurant_address']."</h6>";
		 // <h6>Customer id: ".$data['customer']."</h6>";
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
			$this->email->to('suggest.eatsapp@gmail.com');
			$this->email->cc('gkamatagi@gmail.com');
			//$this->email->bcc('lvijetha90@gmail.com');

			$this->email->subject('EatsApp: Restaurant suggestion');
			$this->email->message($message);
			$this->email->send(); 
			return true;
		}else{
			return false;
		}	

		
			
	}
	public function pitstopSuggest($data){
		
		$sql =$this->db->query("insert into  pitstop_suggest (restaurant_address,restaurant_latitude,restaurant_langitude,customer) values('".$data['restaurant_address']."',
		'".$data['restaurant_latitude']."','".$data['restaurant_langitude']."','".$data['customer']."')");
		
		if($sql){
			$message="<h3>New Delivery Point suggestion</h3>
			<h6>Delivery Point address: ".$data['restaurant_address']."</h6>
			<h6>Delivery Point latitude: ".$data['restaurant_latitude']."</h6>
			<h6>Delivery Point longitude: ".$data['restaurant_langitude']."</h6>
			<h6>Customer id: ".$data['customer']."</h6>";
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
			$this->email->to('suggest.eatsapp@gmail.com');
				$this->email->cc('gkamatagi@gmail.com');
			//$this->email->bcc('lvijetha90@gmail.com');
			$this->email->subject('EatsApp: pitstop suggestion');
			$this->email->message($message);
			$this->email->send(); 
			return true; 
		}
			
	}
	
	public function displayProfile($data){
		
			$sql=$this->db->query("select profile_image from customers where id='".$data['id']."'");
        $i=0;
		if($sql->num_rows()>0){
			$result[$i] = true;
			//echo $this->db->last_query(); exit;
			foreach($sql->result_array() as $row){
				if(isset($row['profile_image']) && $row['profile_image'] != ""){
					$profile_image_path=$this->config->base_url()."uploads/images/thumbnails/".$row['profile_image'];
					$result['data']=$profile_image_path;
				}else{
					$result['data'] = "no_picture";
				}
				
			};
		}else{
			$result[0] = false;
		}
		return $result;	
		
	}
	
	public function orderProfile($data){
		
		$sql=$this->db->query("select customer_image from orders where id='".$data['id']."'");
        $i=0;
		if($sql->num_rows()>0){
			$result[$i] = true;
			//echo $this->db->last_query(); exit;
			foreach($sql->result_array() as $row){
				if(isset($row['customer_image']) && $row['customer_image'] != ""){
					$profile_image_path=$this->config->base_url()."uploads/images/thumbnails/".$row['customer_image'];
					$result['data']=$profile_image_path;
				}else{
					$result['data'] = "no_picture";
				}
				
			};
		}else{
			$result[0] = false;
		}
		return $result;	
		
	}
	
	public function profilePictureUpdate($data){
		$image ="image".$data['id'].".jpg";
		if(file_exists("uploads/images/thumbnails/".$image)){
			unlink("uploads/images/thumbnails/".$image);
		}
		$sql=$this->db->query("UPDATE customers SET profile_image='".$image."' where id='".$data['id']."'");
		
	    if($sql){
			$path = "uploads/images/thumbnails/".$image;
			$ifp = fopen( $path, 'wb' ); 
			//$data = explode( ',', $data['profile_image'] );
			fwrite( $ifp, base64_decode($data['profile_image']));
			fclose( $ifp ); 

			//file_put_contents($path,base64_decode($data['profile_image']));
			$result[0] = true;
		}else{
			$result[0] = false;
		}
		return $result;
		
	}
	
	public function validateCoupon($data){
		
		$sql=$this->db->query("select * from coupons where coupon_code='".$data['coupon_code']."' and used =0");
       
		if($sql->num_rows()>0){
			$data = $sql->result_array();
			
			$result['id'] = $data[0]['id'];
			$result['cost'] = $data[0]['cost'];
			
		}else{
				$result['id'] = 0;
				
		}
			return $result;
		
	}
	public function getnotifications(){
		$sql=$this->db->query("select * from notification_message order by date desc");
       
		if($sql->num_rows()>0){
			$result = $sql->result_array();
		}else{
				$result = '';
				
		}
			return $result;
	}
	
	public function GetOrderStatus($id){
		$sql=$this->db->query("select * from orders where id='".$id."'");
       
		if($sql->num_rows()>0){
			$res = $sql->result_array();
			$result = $res[0]['status'];
		}else{
			$result = '';	
		}
			return $result;
	}
	
	public function addFeedback($data){
	
		$sql =$this->db->query("insert into  feedback (customer_id,user_feedback) values('".$data['customer_id']."','".$data['user_feedback']."')");
		if($sql){
			$result[0] = true;
		}else{
			$result[0] = false;
		}
		return $result;
	}
	
	 public function orderInsert($data){
		$order_number = strtotime(date("Y-m-d H:i:s",time()));
		$date = date('Y-m-d H:i:s');
		$image =$order_number.".png";
		$path = "uploads/images/thumbnails/".$image;
		if(isset($data['photo'])){
			file_put_contents($path,base64_decode($data['photo']));
		}
		$pitstop_id = isset($data['pitstop_id']) ? $data['pitstop_id'] : '';
		$keep_ready = isset($data['keep_ready']) ? $data['keep_ready'] : '';
		$shipping_address =  isset($data['shipping_address']) ? $data['shipping_address'] :  '';
		$delivered_on = isset($data['delivered_on']) ? $data['delivered_on'] : '';
		$pitstop_id = isset($data['pitstop_id']) ? $data['pitstop_id'] : '';
		$sql="insert into orders (order_number,customer_id,restaurant_id,shipping,ordered_on,status,tax,coupon_discount,coupon_id,order_type,total_cost,shipping_lat,shipping_long,customer_image,delivery_location,delivered_on,keep_ready,pitstop_id)
		values ('".$order_number."','".$data['user_id']."','".$data['restaurant_id']."','".$data['shipping']."','".$date."','Payment pending','".$data['tax']."','".$data['coupon_discount']."','".$data['coupon_id']."',
		'".$data['order_type']."','".$data['total_cost']."',  '".$data['shipping_lat']."','".$data['shipping_long']."','".$image."','".$shipping_address."','".$delivered_on."','".$keep_ready."','".$pitstop_id."')";
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($id > 0){
			if(count($data['products']) > 0){
				foreach($data['products'] as $item){
					$contents = isset($item['contents']) ? $item['contents'] : '';
					$sql2 = "insert into order_items (order_id,menu_id,quantity,cost,contents) values ('".$id."','".$item['menu_id']."','".$item['quantity']."',
					'".$item['cost']."','".$contents."') ";
					 $this->db->query($sql2);
				}
				$sql3 = "SELECT * FROM passcode ORDER BY RAND() limit 1";
				$query3 = $this->db->query($sql3);
				if($query3->num_rows()>0){
					$data = $query3->result_array();
					$sql4 =  $this->db->query("update orders set passcode='".$data[0]['passcode']."' where id='".$id."'");
					$result['data'] = $data[0]['passcode'];
					$result['order_id'] = $id;
					$result['order_number'] = $order_number;
					$result[0] = true;
				}
			}else{
				$result[0] = false;
			}
			
		}else{
			$result[0] = false;
		}
		return $result;
	  }
	  
	  public function deliveryboylocation($data){
		if(isset($data['deliveryboy_id']) && $data['deliveryboy_id'] != ""){
			$sql=$this->db->query("select * from deliveryboy_locations where deliveryboy_id='".$data['deliveryboy_id']."' order by location_id desc limit 1");
		   
			if($sql->num_rows()>0){
				$data = $sql->result_array();
				
				$result['langitude'] = $data[0]['langitude'];
				$result['latitude'] = $data[0]['latitude'];
				
			}else{
					$result =  false;
					
			}
		}else{
			$results = $this->Roadrunner_model->TrackOrder($data['order_id']);
			$roadrunner = json_decode($results);
			
			if($roadrunner->status->code ==  200){
				
				 $result['order_status'] = $roadrunner->order_status;
				
				//$result['langitude'] = $roadrunner->location;
				//$result['latitude'] = $roadrunner->location->latitude;
				$result['driver_name'] = $roadrunner->driver_name;
				$result['driver_phone'] = $roadrunner->driver_phone;
				$result['driver_image_url'] = $roadrunner->driver_image_url;
				$result['driver_rating'] = $roadrunner->driver_rating; 
			}else{
				$result =  false;
			}
		}
			return $result;
	  }
	  
	  public function customerlocation($id){
		$sql=$this->db->query("select * from customer_locations where customer_id='".$id."' order by location_id desc limit 1");
       
		if($sql->num_rows()>0){
			$data = $sql->result_array();
			
			$result['langitude'] = $data[0]['langitude'];
			$result['latitude'] = $data[0]['latitude'];
			
		}else{
				$result = false;
				
		}
			return $result;
	  }
	  
	   public function userOrderEmail($data){
		  
		 $sql=$this->db->query("select order_id from order_items  "); 
		 if($sql->num_rows()>0){
			$data = $sql->result_array();
			
			$result['data']['order_id'] =['order_id'];
		//	$result['cost'] = $data[0]['langitude'];
			
		}else{
				$result['order_id'] = 0;
				
		}
			return $result;
		  
		  
		  
		  
		  
	  
	   }
	   public function delete_customer($data){
	   
	   $sql=$this->db->query("delete from customers_address_bank where id='".$data['id']."'");
		if($sql){
			$result[0] = true;
		}else{
			$result[0] = false;
		}
		return $result;

	   }   
	
		
   public function feedbackGet($data){
		   
		  $sql=$this->db->query("select id,user_feedback from feedback where customer_id='".$data['customer_id']."' ORDER BY DATE DESC");
		    $i=0;
		if($sql->num_rows()>0){
			$result[$i] = true;
			//echo $this->db->last_query(); exit;
			foreach($sql->result_array() as $row){
			$result['data'][$i]['id']=$row['id']	;
	        $result['data'][$i]['user_feedback']=$row['user_feedback']	;
				$i++;	
			}
		

		}else{
			$result[0] = false;
		}
		return $result;	
		
		   
	   }
	   
      public function feedbackUpdate($data){
		 $sql=$this->db->query("update feedback set user_feedback='".$data['user_feedback']."' where id='".$data['id']."' ");
		  if($sql){
			$result[0] = true;
		}else{
			$result[0] = false;
		}
		return $result;

		  
		  
	  }	
	  
		public function feedbackdelete($data)
		{
			$sql=$this->db->query("delete  from feedback where id='".$data['id']."'");
			  if($sql){
					$result[0] = true;
				}else{
					$result[0] = false;
				}
				return $result;
		}	
}