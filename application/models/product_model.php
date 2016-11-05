<?php
Class Product_model extends CI_Model
{
	
	function product_autocomplete($name, $limit)
	{
		return	$this->db->like('name', $name)->get('products', $limit)->result();
	}
	
	function product_categories($id){
		$sql = "SELECT * FROM `gc_category_products` as a left join gc_categories as b on a.category_id = b.id 
		where a.product_id=$id";
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
	}
	function getMyPlans(){
		$sql = "SELECT * FROM `gc_products` where operator = 31 order by saleprice asc";
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
	}
	
	function products($data=array(), $return_count=false)
	{
		if(empty($data))
		{
			//if nothing is provided return the whole shabang
			$this->get_all_products();
		}
		else
		{
			//grab the limit
			if(!empty($data['rows']))
			{
				$this->db->limit($data['rows']);
			}
			
			//grab the offset
			if(!empty($data['page']))
			{
				$this->db->offset($data['page']);
			}
			
			//do we order by something other than category_id?
			if(!empty($data['order_by']))
			{
				//if we have an order_by then we must have a direction otherwise KABOOM
				$this->db->order_by($data['order_by'], $data['sort_order']);
			}
			
			//do we have a search submitted?
			if(!empty($data['term']))
			{
				$search	= json_decode($data['term']);
				//if we are searching dig through some basic fields
				if(!empty($search->term))
				{
					$this->db->like('name', $search->term);
					$this->db->or_like('description', $search->term);
					$this->db->or_like('excerpt', $search->term);
					$this->db->or_like('sku', $search->term);
				}
				
				if(!empty($search->category_id))
				{
					//lets do some joins to get the proper category products
					$this->db->join('category_products', 'category_products.product_id=products.id', 'right');
					$this->db->where('category_products.category_id', $search->category_id);
					$this->db->order_by('sequence', 'ASC');
				}
			}
			
			if($return_count)
			{
				return $this->db->count_all_results('products');
			}
			else
			{
				return $this->db->get('products')->result();
			}
			
		}
	}
	
	function get_all_products()
	{
		//sort by alphabetically by default
		$this->db->order_by('name', 'ASC');
		$result	= $this->db->get('products');

		return $result->result();
	}
	
	function get_filtered_products($product_ids, $limit = false, $offset = false)
	{
		
		if(count($product_ids)==0)
		{
			return array();
		}
		
		$this->db->select('id, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)->from('products');
		
		if(count($product_ids)>1)
		{
			$querystr = '';
			foreach($product_ids as $id)
			{
				$querystr .= 'id=\''.$id.'\' OR ';
			}
		
			$querystr = substr($querystr, 0, -3);
			
			$this->db->where($querystr, null, false);
			
		} else {
			$this->db->where('id', $product_ids[0]);
		}
		
		$result	= $this->db->limit($limit)->offset($offset)->get()->result();

		//die($this->db->last_query());

		$contents	= array();
		$count		= 0;
		foreach ($result as $product)
		{

			$contents[$count]	= $this->get_product($product->id);
			$count++;
		}

		return $contents;
		
	}
	
	function get_products($category_id = false, $limit = false, $offset = false, $by=false, $sort=false, $saleprice=false, $SimPlanType=false)
	{
		$this->load->library('session');
		$filter = $this->session->userdata('filter');
		
		//if we are provided a category_id, then get products according to category
		if ($category_id)
		{
			
			$this->db->select('category_products.*, products.*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false)
			->from('category_products')->join('products', 'category_products.product_id=products.id')
			
			->where('enabled',1);
			$this->db->where('operator >=', 1);
			
			if($saleprice != false){
				$res = explode('-',$saleprice);
				$this->db->where('saleprice >=', $res[0]);
				$this->db->where('saleprice <=', $res[1]);
				
			}
			if(isset($filter['SimPlanType']) &&  $filter['SimPlanType'] != false){
				$res = explode('-',$filter['SimPlanType']);
				$this->db->where('SimPlanType', $res[0]);
				
			}
			if(isset($filter['SetupBoxType']) && $filter['SetupBoxType'] != false){
				$res = explode('-',$filter['SetupBoxType']);
				$this->db->where('SetupBoxType', $res[0]);
				
			}
			if(isset($filter['SetupBoxregion']) && $filter['SetupBoxregion'] != false){
				$res = explode('-',$filter['SetupBoxregion']);
				$this->db->where('SetupBoxregion', $res[0]);
				
			}
			if(isset($filter['category']) && $filter['category'] != false){
				if($category_id){
					$rr = explode(',',$filter['category']);
				}
				
				$this->db->where_in('category_id', $rr);
				
			}else{
				$this->db->where('category_id',$category_id);
			}
			
			if(isset($filter['SetupBoxmonth']) && $filter['SetupBoxmonth'] != false){
				$rr = explode(',',$filter['SetupBoxmonth']);
				$this->db->where_in('SetupBoxmonth', $rr);
				
			}
			
			if(isset($filter['SubscriptionType']) && $filter['SubscriptionType'] != false){
				$rr = explode(',',$filter['SubscriptionType']);
				$this->db->where_in('SubscriptionType', $rr);
				
			}
			if(isset($filter['DataCardDeviceType']) && $filter['DataCardDeviceType'] != false){
				$rr = explode(',',$filter['DataCardDeviceType']);
				$this->db->where_in('DataCardDeviceType', $rr);
				
			}
			if(isset($filter['DataCardDeviceType']) && $filter['DataCardDeviceType'] != false){
				$rr = explode(',',$filter['DataCardDeviceType']);
				$this->db->where_in('DataCardDeviceType', $rr);
				
			}
			
			if(isset($filter['InternationalSim']) && $filter['InternationalSim'] != false){
				$rr = explode(',',$filter['InternationalSim']);
				$this->db->where_in('InternationalSim', $rr);
				
			}
			
			if(isset($filter['BroadbandDataUsage']) && $filter['BroadbandDataUsage'] != false){
				$res = explode('-',($filter['BroadbandDataUsage']));
				$this->db->where('BroadbandDataUsage >=', $res[0]);
				$this->db->where('BroadbandDataUsage <=', $res[1]);
				
			}
			
			$this->db->order_by($by, $sort);
			
			$result	= $this->db->limit($this->session->userdata('num_rec_per_page'))->offset($this->session->userdata('start_from'))->get()->result();
			//echo $this->db->last_query(); exit;
			return $result;
		}
		else
		{
			//sort by alphabetically by default
			$this->db->order_by('name', 'ASC');
			$result	= $this->db->get('products');

			return $result->result();
		}
	}
	
	function count_all_products()
	{
		return $this->db->count_all_results('products');
	}
	
	function count_products($id, $saleprice=false)
	{
	
		 $this->db->select('count(id) as count')->from('category_products')
		 ->join('products', 'category_products.product_id=products.id')->where('category_id',$id)
			->where('enabled',1);

			
			if($saleprice != false){
			
				$res = explode('-',$saleprice);
				$this->db->where('saleprice >=', $res[0]);
				$this->db->where('saleprice <=', $res[1]);
				
			}
			
			
			$result	= $this->db->get()->result();
			
		return $result[0];
		
	}

	function get_product($id, $related=true)
	{
		$result	= $this->db->get_where('products', array('id'=>$id))->row();
		//echo $this->db->last_query(); exit;
		if(!$result)
		{
			return false;
		}

		$related	= json_decode($result->related_products);
		
		if(!empty($related))
		{
			//build the where
			$where = array();
			foreach($related as $r)
			{
				$where[] = '`id` = '.$r;
			}

			$this->db->where('('.implode(' OR ', $where).')', null);
			$this->db->where('enabled', 1);

			$result->related_products	= $this->db->get('products')->result();
		}
		else
		{
			$result->related_products	= array();
		}
		$result->categories			= $this->get_product_categories($result->id);

		return $result;
	}

	function get_product_categories($id)
	{
		return $this->db->where('product_id', $id)->join('categories', 'category_id = categories.id')->get('category_products')->result();
	}

	function get_slug($id)
	{
		return $this->db->get_where('products', array('id'=>$id))->row()->slug;
	}

	function check_slug($str, $id=false)
	{
		$this->db->select('slug');
		$this->db->from('products');
		$this->db->where('slug', $str);
		if ($id)
		{
			$this->db->where('id !=', $id);
		}
		$count = $this->db->count_all_results();

		if ($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function save($product, $options=false, $categories=false)
	{
		if ($product['id'])
		{
			$this->db->where('id', $product['id']);
			$this->db->update('products', $product);

			$id	= $product['id'];
		}
		else
		{
			$this->db->insert('products', $product);
			$id	= $this->db->insert_id();
		}

		//loop through the product options and add them to the db
		if($options !== false)
		{
			$obj =& get_instance();
			$obj->load->model('Option_model');

			// wipe the slate
			$obj->Option_model->clear_options($id);

			// save edited values
			$count = 1;
			foreach ($options as $option)
			{
				$values = $option['values'];
				unset($option['values']);
				$option['product_id'] = $id;
				$option['sequence'] = $count;

				$obj->Option_model->save_option($option, $values);
				$count++;
			}
		}
		
		if($categories !== false)
		{
			if($product['id'])
			{
				//get all the categories that the product is in
				$cats	= $this->get_product_categories($id);
				
				//generate cat_id array
				$ids	= array();
				foreach($cats as $c)
				{
					$ids[]	= $c->id;
				}

				//eliminate categories that products are no longer in
				foreach($ids as $c)
				{
					if(!in_array($c, $categories))
					{
						$this->db->delete('category_products', array('product_id'=>$id,'category_id'=>$c));
					}
				}
				
				//add products to new categories
				foreach($categories as $c)
				{
					if(!in_array($c, $ids))
					{
						$this->db->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
					}
				}
			}
			else
			{
				//new product add them all
				foreach($categories as $c)
				{
					$this->db->insert('category_products', array('product_id'=>$id,'category_id'=>$c));
				}
			}
		}
		
		
		//return the product id
		return $id;
	}
	
	function delete_product($id)
	{
		// delete product 
		$this->db->where('id', $id);
		$this->db->delete('products');

		//delete references in the product to category table
		$this->db->where('product_id', $id);
		$this->db->delete('category_products');
		
		// delete coupon reference
		$this->db->where('product_id', $id);
		$this->db->delete('coupons_products');

	}

	function add_product_to_category($product_id, $optionlist_id, $sequence)
	{
		$this->db->insert('product_categories', array('product_id'=>$product_id, 'category_id'=>$category_id, 'sequence'=>$sequence));
	}

	function search_products($term, $limit=false, $offset=false, $by=false, $sort=false)
	{
		$results		= array();
		
		$this->db->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
		//this one counts the total number for our pagination
		$this->db->where('enabled', 1);
		$this->db->where('(name LIKE "%'.$term.'%" OR description LIKE "%'.$term.'%" OR excerpt LIKE "%'.$term.'%" OR sku LIKE "%'.$term.'%")');
		$results['count']	= $this->db->count_all_results('products');


		$this->db->select('*, LEAST(IFNULL(NULLIF(saleprice, 0), price), price) as sort_price', false);
		//this one gets just the ones we need.
		$this->db->where('enabled', 1);
		$this->db->where('(name LIKE "%'.$term.'%" OR description LIKE "%'.$term.'%" OR excerpt LIKE "%'.$term.'%" OR sku LIKE "%'.$term.'%")');
		
		if($by && $sort)
		{
			$this->db->order_by($by, $sort);
		}
		
		$results['products']	= $this->db->get('products', $limit, $offset)->result();
		
		return $results;
	}

	// Build a cart-ready product array
	function get_cart_ready_product($id, $quantity=false)
	{
		$product	= $this->db->get_where('products', array('id'=>$id))->row();
		
		//unset some of the additional fields we don't need to keep
		if(!$product)
		{
			return false;
		}
		
		$product->base_price	= $product->price;
		
		if ($product->saleprice != 0.00)
		{ 
			$product->price	= $product->saleprice;
		}
		
		
		// Some products have n/a quantity, such as downloadables
		//overwrite quantity of the product with quantity requested
		if (!$quantity || $quantity <= 0 || $product->fixed_quantity==1)
		{
			$product->quantity = 1;
		}
		else
		{
			$product->quantity = $quantity;
		}
		
		
		// attach list of associated downloadables
		$product->file_list	= $this->Digital_Product_model->get_associations_by_product($id);
		
		return (array)$product;
	}
	
	function GetFancyNumbers($number){
		$where="";
		if($number != ""){
			$where.="and a.`number` like '%$number%'";
		}
		$sql = "SELECT * FROM `gc_numbers` as a left join gc_categories as b on a.operator = b.id where a.type = 2 and b.company = 1 $where ";
		
		$query = $this->db->query($sql);


		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
		
	}
	
	function GetCategory($slug){
		$sql = "SELECT b.category_id FROM `gc_products` as a left join gc_category_products as b on a.id = b.product_id left join gc_categories as c on b.category_id=c.id WHERE  a.slug = '".$slug."' and c.parent_id = 0";
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->row();
		} 
		return $result;
	}
	
	function getCoupons(){
		$sql = "SELECT * FROM `gc_products` as a left join gc_category_products as b on a.id = b.product_id  WHERE  b.category_id = '21'";
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		return $result;
	}
}