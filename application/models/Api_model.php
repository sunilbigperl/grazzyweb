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
	
	 function saveAddress($data)
    {
        $data['field_data'] = serialize($data['field_data']);
       
        if(!empty($data['id']))
        {
            $this->db->where('id', $data['id']);
            $this->db->update('customers_address_bank', $data);
            return $data['id'];
        } else {
            $this->db->insert('customers_address_bank', $data);
            return $this->db->insert_id();
        }
    }
	
	public function pitstopsuser($data){
		$sql = "SELECT * FROM `pitstops` WHERE `latitude` > '".$data['southwest_lat']."' and `latitude` < '".$data['northeast_lat']."'
		and `langitude` > '".$data['southwest_lng']."' and`langitude` < '".$data['northeast_lng']."'";
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
		$sql = "SELECT *,( 3959 * acos( cos( radians('".$data['latitude']."') ) * cos( radians( restaurant_latitude ) ) * cos( radians( restaurant_langitude ) - radians('".$data['langitude']."') ) + sin( radians('".$data['latitude']."') ) * sin( radians( restaurant_latitude ) ) ) ) AS distance FROM restaurant HAVING distance < 2";
		
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			return $query->result_array();
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
	public function orderlistnotshipped($id){
		$sql = "SELECT * FROM `orders`a WHERE a.`customer_id` = ".$id." and a.status='Order Placed'  order by a.ordered_on desc";
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
				$result[$i]['order_type'] = $row['order_type'];
				$result[$i]['delivered_by'] = $row['delivered_by'];
				$result[$i]['passcode'] = $row['passcode'];
				
				$sql2 = "select a.menu,b.* from restaurant_menu a, order_items b where b.order_id='".$row['id']."' and a.menu_id=b.menu_id";
				$query2 = $this->db->query($sql2);
				if($query2->num_rows()>0){
					$j=0;
					foreach($query2->result_array() as $row1){
						$result[$i]['items'][]=$row1['menu'];
					$j++;
					}
				}
				$result[$i]['items']= implode(",",$result[$i]['items']);
				$result[$i]['shipping_lat'] = $row['shipping_lat'];
				$result[$i]['shipping_long'] = $row['shipping_long'];
				if($result[$i]['order_type'] == 1 || $result[$i]['order_type'] == 2 || $result[$i]['order_type'] == 4){
					if($result[$i]['delivered_by'] != 0){
						$sql4  = $this->db->query("select * from admin where id='".$result[$i]['delivered_by']."'");
						$res_del = $sql4->result_array();
						$result[$i]['name'] = $res_del[0]['firstname'];
						$result[$i]['phone'] = isset($res_del[0]['phone']) ? $res_del[0]['phone'] : "";
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
		$sql = "SELECT * FROM `orders`a WHERE a.`customer_id` = ".$id." and a.status='Order Shipped'  order by a.ordered_on desc";
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
				$sql2 = "select a.menu,b.* from restaurant_menu a, order_items b where b.order_id='".$row['id']."' and a.menu_id=b.menu_id";
				
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
	
	public function updateprofile($data){
		$sql = "update customers set firstname='".$data['firstname']."',email='".$data['email']."',dob='".$data['dob']."',gender='".$data['gender']."' where id='".$data['id']."'";
		//echo $sql; exit;
		$query = $this->db->query($sql);
		if($query){
			return true;
		}
	}
	
	public function getMenus($id){
		$sql ="SELECT DISTINCT b.category_id, c.name FROM `restaurant_menu` a, menu_categories b, categories c where a.restaurant_id = '".$id."' and a.menu_id = b.menu_category and b.category_id = c.id";
		$query = $this->db->query($sql);
		$result = array();
		if($query->num_rows()>0){
			$data = $query->result_array();
			$i=0;
			foreach($data as $menu){
				$result[$i]['category_id'] = $menu['category_id'];
				$result[$i]['category'] = $menu['name'];
				$sql1 ="SELECT * FROM `restaurant_menu` a, menu_categories b, categories c where a.restaurant_id = '".$id."' and b.category_id='".$menu['category_id']."' and a.menu_id = b.menu_category and b.category_id = c.id";
				$query1 = $this->db->query($sql1);
				if($query1->num_rows()>0){
					$data1 = $query1->result_array();
					$j=0;
					foreach($data1 as $mn){
						$result[$i]['menus'][$j]['menu_id'] = $mn['menu_id'];
						$result[$i]['menus'][$j]['menu'] = $mn['menu'];
						$result[$i]['menus'][$j]['price'] = $mn['price'];
						$result[$i]['menus'][$j]['image'] = 'uploads/images/thumbnails/'.$mn['image'];
						$result[$i]['menus'][$j]['type'] = $mn['type'];
					$j++;
					}
				}
			$i++;
			}
		}
		
		return $result;
	}
	
	public function restaurantforlocation($data){
		
	}
	
	public function restaurantSuggest($data){
		
		$sql =$this->db->query("insert into  restaurant_suggest (id,restaurant_name,restaurant_phone,restaurant_email) values('".$data['id']."','".$data['restaurant_name']."','".$data['restaurant_phone']."','".$data['restaurant_email']."')");
		
		if($sql){
			return true;
		}else{
			return false;
		}		
			
	}
	public function pitstopSuggest($data){
		
		$sql =$this->db->query("insert into  pitstop_suggest (id,restaurant_address) values('".$data['id']."','".$data['restaurant_address']."')");
		
		if($sql){
			return true;
		}
			
	}
	
	public function displayProfile($data){
		
		$sql=$this->db->query("select id,profile_image from customers where id='".$data['id']."'");
        $i=0;
		if($sql->num_rows()>0){
			$result[$i] = true;
			//echo $this->db->last_query(); exit;
			foreach($sql->result_array() as $row){
         	
                $profile_image_path=$row['profile_image'];
			    $profile_image_path=$this->config->base_url()."uploads/".$profile_image_path;
			    $result['data'][$i]['profile_image']=$profile_image_path;
				
			};
		}else{
			$result[0] = false;
		}
		return $result;	
			
	}
	
	public function profilePictureUpdate($data){
		$image =$data['id'].".png";
		$sql=$this->db->query("UPDATE customers SET profile_image='".$image."' where id='".$data['id']."'");
		
	    if($sql==true){
			$path = "uploads/.".$data['id'].".png";
			file_put_contents($path,base64_decode($image));
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
		  $sql="insert into orders (order_number,customer_id,restaurant_id,shipping,ordered_on,status,tax,coupon_discount,coupon_id,order_type,total_cost,shipping_lat,shipping_long)
		   values ('".$order_number."','".$data['user_id']."','".$data['restaurant_id']."','".$data['shipping']."','".$date."','Order Placed','".$data['tax']."','".$data['coupon_discount']."','".$data['coupon_id']."',
		   '".$data['order_type']."','".$data['total_cost']."',  '".$data['shipping_lat']."','".$data['shipping_long']."')";
		   $this->db->query($sql);
		   $id = $this->db->insert_id();
		if($id > 0){
			if(count($data['products']) > 0){
				foreach($data['products'] as $item){
					$sql2 = "insert into order_items (order_id,menu_id,quantity,cost) values ('".$id."','".$item['menu_id']."','".$item['quantity']."','".$item['cost']."') ";
					 $this->db->query($sql2);
				}
				$sql3 = "SELECT * FROM passcode ORDER BY RAND() limit 1";
				$query3 = $this->db->query($sql3);
				if($query3->num_rows()>0){
					$data = $query3->result_array();
					$sql4 =  $this->db->query("update orders set passcode='".$data[0]['passcode']."' where id='".$id."'");
					$result['data'] = $data[0]['passcode'];
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
	  
	  public function deliveryboylocation($id){
		$sql=$this->db->query("select * from deliveryboy_locations where deliveryboy_id='".$id."'");
       
		if($sql->num_rows()>0){
			$data = $sql->result_array();
			
			$result['id'] = $data[0]['langitude'];
			$result['cost'] = $data[0]['langitude'];
			
		}else{
				$result['id'] = 0;
				
		}
			return $result;
	  }
	
}