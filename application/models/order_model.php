<?php
Class order_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_gross_monthly_sales($year)
	{
		$this->db->select('SUM(coupon_discount) as coupon_discounts');
		$this->db->select('SUM(gift_card_discount) as gift_card_discounts');
		$this->db->select('SUM(subtotal) as product_totals');
		$this->db->select('SUM(shipping) as shipping');
		$this->db->select('SUM(tax) as tax');
		$this->db->select('SUM(total) as total');
		$this->db->select('YEAR(ordered_on) as year');
		$this->db->select('MONTH(ordered_on) as month');
		$this->db->group_by(array('MONTH(ordered_on)'));
		$this->db->order_by("ordered_on", "desc");
		$this->db->where('YEAR(ordered_on)', $year);
		
		return $this->db->get('orders')->result();
	}
	
	function get_sales_years()
	{
		$this->db->order_by("ordered_on", "desc");
		$this->db->select('YEAR(ordered_on) as year');
		$this->db->group_by('YEAR(ordered_on)');
		$records	= $this->db->get('orders')->result();
		$years		= array();
		foreach($records as $r)
		{
			$years[]	= $r->year;
		}
		return $years;
	}
	
	function get_neworders(){
		$userdata = $this->session->userdata('admin');
		$date = date("Y-m-d 00:00:00");
		$sql = $this->db->query("SELECT a.*,d.order_type,d.ordertype_id,b.* FROM `orders` a, restaurant b, order_type d, admin c WHERE a.`status` = 'Order placed' and a.`restaurant_id` = b.restaurant_id 
		and d.ordertype_id =a.order_type and b.restaurant_manager = c.id and b.restaurant_manager = '".$userdata['id']."' and a.ordered_on >='".$date."'");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function get_delpartnerorders(){
		$userdata = $this->session->userdata('admin');
		$date = date("Y-m-d 00:00:00");
		$sql = $this->db->query("SELECT a.*,d.order_type,d.ordertype_id,b.* FROM `orders` a, restaurant b, order_type d, admin c WHERE a.`restaurant_id` = b.restaurant_id 
		and d.ordertype_id =a.order_type and b.restaurant_manager = c.id and a.restaurant_manager_status = 'Accepted' and a.order_type != 3 and a.ordered_on >='".$date."'");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function AssignDeliveryBoy($data){
		$sql = $this->db->query("update orders set delivered_by='".$data['delBoy']."' , staus='Accepted' where id='".$data['id']."'");
		if($sql){ return true; }
	}
	function get_deliveryboys(){
		$sql = $this->db->query("select * from delivery_boy");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	function get_deliverypartnerneworders(){
		$userdata = $this->session->userdata('admin');
		$date = date("Y-m-d");
		$sql = $this->db->query("SELECT a.*,d.order_type,d.ordertype_id,b.* FROM `orders` a, restaurant b, order_type d, admin c WHERE a.`status` = 'Order placed' and a.`restaurant_id` = b.restaurant_id 
		and d.ordertype_id =a.order_type and b.restaurant_manager = c.id and a.order_type != 3 and a.ordered_on='".$date."' and a.delivery_partner = ''");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	function get_previousorders($data){
		$userdata = $this->session->userdata('admin');
		$sql = $this->db->query("SELECT a.*,d.order_type,d.ordertype_id,b.* FROM `orders` a, restaurant b, order_type d, admin c WHERE a.`status` = 'Order placed' and a.`restaurant_id` = b.restaurant_id 
		and d.ordertype_id =a.order_type and b.restaurant_manager = c.id and b.restaurant_manager = '".$userdata['id']."' and a.ordered_on >= '".$data['fromdate']."' and a.ordered_on <= '".$data['todate']."'");
		
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function GetCustomerReview($id){
		$sql = $this->db->query("select a.*,b.firstname from feedback a, customers b where a.feedbackfrom=b.id and a.feedbacktype=6 and a.feedbackto='".$id."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			$sql1 = $this->db->query("select AVG(ratings) as avg from feedback where feedbacktype=6 and feedbackto='".$id."'");
			$result['avg']	= $sql1->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function GetDelPartnerReview($id){
		$sql = $this->db->query("select a.*,b.firstname from feedback a, admin b  where a.feedbackfrom=b.id and a.feedbacktype=4 and a.feedbackto='".$id."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			$sql1 = $this->db->query("select AVG(ratings) as avg from feedback where feedbacktype=4 and feedbackto='".$id."'");
			$result['avg']	= $sql1->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function get_restpreviousorders($data){
		$userdata = $this->session->userdata('admin');
		$sql = $this->db->query("SELECT a.*,d.order_type,d.ordertype_id,b.* FROM `orders` a, restaurant b, order_type d, admin c WHERE a.`status` = 'Order placed' and a.`restaurant_id` = b.restaurant_id 
		and d.ordertype_id =a.order_type and b.restaurant_manager = c.id and b.restaurant_id='".$data['id']."' and a.ordered_on >= '".$data['fromdate']."' and a.ordered_on <= '".$data['todate']."'");
		
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	function CheckFeedback($order,$type){
		$sql = $this->db->query("select * from feedback where order_number='".$order."' and feedbacktype=".$type."");
		
		if($sql->num_rows() > 0){
			$result	= 1;
		}else{
			$result = 0;
		}
		return $result;
	}
	function InserReview($data){
		$sql = "insert into feedback (feedbackfrom,feedbackto,comments,ratings,feedbacktype,order_number) values('".$data['feedbackfrom']."',
		'".$data['feedbackto']."','".$data['comments']."','".$data['ratings']."','".$data['feedbacktype']."','".$data['order_number']."')";
		 $this->db->query($sql);
	}
	function GetMenudetails($data){
		
		$sql = $this->db->query("select a.*,b.menu from order_items a, restaurant_menu b where a.menu_id=b.menu_id and order_id='".$data['id']."'");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function ChangeRestMangerStatus($status,$id){
		if($status == "1"){ $data = "Accepted"; }else{ $data = "Rejected"; }
		$sql = $this->db->query('update orders set restaurant_manager_status="'.$data.'" where id="'.$id.'"');
		if($sql){
			return true;
		}else{
			return false;
		}
	}
	
	function ChangeDelPartnerStatus($status,$id){
		if($status == "1"){ $data = "Accepted"; }else{ $data = "Rejected"; }
		$userdata = $this->session->userdata('admin');
		
		$sql = $this->db->query('update orders set delivery_partner ="'.$userdata['id'].'",delivery_partner_status="'.$data.'" where id="'.$id.'"');
		if($sql){
			return true;
		}else{
			return false;
		}
	}
	//get an individual customers orders
	function get_customer_orders($id, $offset=0)
	{
		$this->db->join('order_items', 'orders.id = order_items.order_id');
		$this->db->order_by('ordered_on', 'DESC');
		return $this->db->get_where('orders', array('customer_id'=>$id), 15, $offset)->result();
	}
	
	function count_customer_orders($id)
	{
		$this->db->where(array('customer_id'=>$id));
		return $this->db->count_all_results('orders');
	}
	
	function get_order($id)
	{
		$this->db->where('id', $id);
		$result 			= $this->db->get('orders');
		
		$order				= $result->row();
		$order->contents	= $this->get_items($order->id);
		
		return $order;
	}
	
	function get_items($id)
	{
		$this->db->select('order_id, contents');
		$this->db->where('order_id', $id);
		$result	= $this->db->get('order_items');
		
		$items	= $result->result_array();
		
		$return	= array();
		$count	= 0;
		foreach($items as $item)
		{

			$item_content	= unserialize($item['contents']);
			
			//remove contents from the item array
			unset($item['contents']);
			$return[$count]	= $item;
			
			//merge the unserialized contents with the item array
			$return[$count]	= array_merge($return[$count], $item_content);
			
			$count++;
		}
		return $return;
	}
	
	function delete($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('orders');
		
		//now delete the order items
		$this->db->where('order_id', $id);
		$this->db->delete('order_items');
	}
	
	function save_order($data, $contents = false)
	{
		if (isset($data['id']))
		{
			$this->db->where('id', $data['id']);
			$this->db->update('orders', $data);
			$id = $data['id'];
			
			// we don't need the actual order number for an update
			$order_number = $id;
		}
		else
		{
			$this->db->insert('orders', $data);
			$id = $this->db->insert_id();
			
			//create a unique order number
			//unix time stamp + unique id of the order just submitted.
			$order	= array('order_number'=> date('U').$id);
			
			//update the order with this order id
			$this->db->where('id', $id);
			$this->db->update('orders', $order);
						
			//return the order id we generated
			$order_number = $order['order_number'];
		}
		
		//if there are items being submitted with this order add them now
		if($contents)
		{
			// clear existing order items
			$this->db->where('order_id', $id)->delete('order_items');
			// update order items
			foreach($contents as $item)
			{
				$save				= array();
				$save['contents']	= $item;
				
				$item				= unserialize($item);
				$save['product_id'] = $item['id'];
				$save['quantity'] 	= $item['quantity'];
				$save['order_id']	= $id;
				$this->db->insert('order_items', $save);
			}
		}
		
		return $order_number;

	}
	
	function get_best_sellers($start, $end)
	{
		if(!empty($start))
		{
			$this->db->where('ordered_on >=', $start);
		}
		if(!empty($end))
		{
			$this->db->where('ordered_on <',  $end);
		}
		
		// just fetch a list of order id's
		$orders	= $this->db->select('id')->get('orders')->result();
		
		$items = array();
		foreach($orders as $order)
		{
			// get a list of product id's and quantities for each
			$order_items	= $this->db->select('product_id, quantity')->where('order_id', $order->id)->get('order_items')->result_array();
			
			foreach($order_items as $i)
			{
				
				if(isset($items[$i['product_id']]))
				{
					$items[$i['product_id']]	+= $i['quantity'];
				}
				else
				{
					$items[$i['product_id']]	= $i['quantity'];
				}
				
			}
		}
		arsort($items);
		
		// don't need this anymore
		unset($orders);
		
		$return	= array();
		foreach($items as $key=>$quantity)
		{
			$product				= $this->db->where('id', $key)->get('products')->row();
			if($product)
			{
				$product->quantity_sold	= $quantity;
			}
			else
			{
				$product = (object) array('sku'=>'Deleted', 'name'=>'Deleted', 'quantity_sold'=>$quantity);
			}
			
			$return[] = $product;
		}
		
		return $return;
	}
	
	public function get_delpartnerremarks($data){ 
		$sql =$this->db->query("select comments from feedback where feedbackto=".$data->restaurant_id." and order_number='".$data->order_number."' and feedbacktype=5");
		$result = $sql->result();
		return $result;
	}
}
