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
		$sql = "insert into feedback (feedbackfrom, feedbackto, comments,ratings,feedbacktype) 
		values('".$data['feedbackfrom']."','".$data['feedbackto']."','".$data['comments']."','".$data['ratings']."','".$data['feedbacktype']."')";	
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
	
	public function delboyOrders($id){
		$threadmsg = $this->db->query("select * from orders a , customers b, restaurant c where a.customer_id=b.id and a.restaurant_id = c.restaurant_id and a.delivered_by != 0 and a.delivered_by='".$id."' and a.order_type != 3");

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
	
	public function adddelboylocation($data){
		$sql = "insert into deliveryboy_locations (deliveryboy_id,latitude,langitude) values('".$data['deliveryboy_id']."','".$data['latitude']."','".$data['langitude']."')";
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
				$result[$i]['order_id'] = $row['id'];
				$sql1 = "select restaurant_name from restaurant where restaurant_id='".$row['restaurant_id']."'";
				$query1 = $this->db->query($sql1);
				if($query1->num_rows()>0){
					$res = $query1->result_array();
					$result[$i]['restaurant_name'] = $res[0]['restaurant_name'];
				}
				$result[$i]['order_type'] = $row['order_type'];
				$result[$i]['delivered_by'] = $row['delivered_by'];
				$result[$i]['passcode'] = $row['passcode'];
				$result[$i]['total_cost'] = $row['total_cost'];
				$result[$i]['order_number'] = $row['order_number'];
				
				$sql2 = "select a.menu,b.* from restaurant_menu a, order_items b where b.order_id='".$row['id']."' and a.menu_id=b.menu_id";
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
	public function changeorderstatus($data){
		$sql = "update orders set status='".$data['status']."' where id='".$data['id']."'";
		$query = $this->db->query($sql);
		if($query){
			return "success";
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
	
	public function pitstopsuser1($data){
		$sql = $this->db->query("select * from pitstops where enabled=1");
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
		$sql =$this->db->query("insert into  restaurant_suggest (restaurant_name,restaurant_phone,restaurant_address,restaurant_email) 
		values('".$data['restaurant_name']."','".$data['restaurant_phone']."','".$data['restaurant_address']."','".$data['restaurant_email']."')");
		
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
	
	public function profilePictureUpdate($data){
		$image ="image".$data['id'].".jpg";
		if(file_exists("uploads/images/thumbnails/".$image)){
			unlink("uploads/images/thumbnails/".$image);
		}
		$sql=$this->db->query("UPDATE customers SET profile_image='".$image."' where id='".$data['id']."'");
		
	    if($sql){
			$path = "uploads/images/thumbnails/".$image;
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
		$image =$order_number.".png";
		$path = "uploads/images/thumbnails/".$image;
		file_put_contents($path,base64_decode($image));
		
		$sql="insert into orders (order_number,customer_id,restaurant_id,shipping,ordered_on,status,tax,coupon_discount,coupon_id,order_type,total_cost,shipping_lat,shipping_long,customer_image,delivery_location)
		values ('".$order_number."','".$data['user_id']."','".$data['restaurant_id']."','".$data['shipping']."','".$date."','Order Placed','".$data['tax']."','".$data['coupon_discount']."','".$data['coupon_id']."',
		'".$data['order_type']."','".$data['total_cost']."',  '".$data['shipping_lat']."','".$data['shipping_long']."','".$image."','".$data['shipping_address']."')";
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
	
	
}