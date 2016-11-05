<?php
Class Home_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	/********************************************************************

	********************************************************************/
	
	function AddReview($data){
		$sql = "INSERT INTO `reviews`(`product_id`, `customer_id`, `comment`, `rate`, `active`) VALUES ('".$data['product_id']."',
		'".$data['customer_id']."','".$data['comments']."','".$data['rate']."',1)";
		$query = $this->db->query($sql);
		if($query){
			return 1;
		}else{
			return 0;
		} 
	}
	
	function GetCategories(){
		$sql = "select * from gc_categories where directlink = 0";
		$query = $this->db->query($sql);
		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
			$reeesult= array();
			foreach($result as $res){
				if($res->company == 1){
					$sql1 = "select * from gc_categories where directlink = 0 and id=".$res->parent_id;
					$query1 = $this->db->query($sql1);
					if($query1->num_rows() > 0){
						$result1 = $query1->row();
						$reeesult[] = $result1->name."-".$res->name;
					}
				}else{
					$reeesult[] = $res->name;
				}
			}
		} 
		return json_encode($reeesult);
	}
	
	function GetReviews($id){
		$sql = "select reviews.*,gc_customers.* from reviews left join gc_products on reviews.product_id=gc_products.id left join gc_customers on gc_customers.id=reviews.customer_id
		where reviews.product_id='".$id."'";
		$query = $this->db->query($sql);


		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
	}
	
	function GetOperators($parent_id="")
	{
		$where="";
		if($parent_id != ""){
			$where.="and `parent_id` = ".$parent_id."";
		}
		$sql = "SELECT * FROM `gc_categories` where company = 1 $where ";
		
		$query = $this->db->query($sql);


		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
		
	}

	function GetOperatorForProduct($id="")
	{
		
		$sql = "SELECT * FROM `gc_categories` where  id='".$id."' ";
		
		$query = $this->db->query($sql);


		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
		
	}
	function GetNumbers($id,$number=''){
		$where="";
		if($number != ""){
			$where.=" and `number` like '%$number%'";
		}
		$sql = "SELECT * FROM `gc_numbers` as a left join gc_categories as b on a.operator = b.id where a.type = 1 and operator = $id $where ";
		
		$query = $this->db->query($sql);


		if($query->num_rows() ==''){
			$result =0; 		
		}else{
			$result = $query->result();
		} 
		
		return $result;
		
	}
	
	public function savemails($content='',$from='',$to=''){
		$sql = "INSERT INTO `gc_mails`(`content`, `from_`, `to_`) VALUES ('".$content."','".$from."','".$to."')";
		$query = $this->db->query($sql);
		if($this->db->insert_id() > 0){
			$return =  1;
		}else{
			$return  =0;
		}
		echo $return;
	}
	
	public function SearchResult($search){
		$search = explode('-',$search);
		if(count($search) == 1){
			$sql = "SELECT slug FROM `gc_categories` where name = '".$search[0]."'";
			$query = $this->db->query($sql);
			$return =  $query->row();
		}else{
			$sql = "SELECT * FROM `gc_categories` where name = '".$search[0]."'";
			$query = $this->db->query($sql);
			$return1 =  $query->row();
			
			$sql1 = "SELECT slug FROM `gc_categories` where parent_id=".$return1->id." and name = '".$search[1]."'";
			
			$query1 = $this->db->query($sql1);
			$return =  $query1->row();
		}
		return $return;
	}
	
	public function getParentCategory($cat){
		$sql = "SELECT parent_id FROM `gc_categories` where  id=".$cat;
		$query = $this->db->query($sql);
		if($query->num_rows() == ''){
			$return  =0;
		}else{
			$return =  $query->row();
		}
		return $return;
	}
	
	public function GetSubCategories($cat,$company=""){
		
		if(isset($company) && $company == ""){
			$company = 0;
		}else{
			$company =1;
		}
		$sql = "SELECT * FROM `gc_categories` where  company=$company and parent_id=".$cat;
		
		$query = $this->db->query($sql);
		if($query->num_rows() == ''){
			$return  =0;
		}else{
			$return =  $query->result();
		}
		return $return;
	}
	public function Getcountproducts($cat,$operator=""){
		$where="";
		if($operator != ""){
			$where.=" and a.operator=".$operator;
		}
		$sql = "SELECT a.id FROM `gc_products` as a left join gc_category_products as b on a.id = b.product_id left join gc_categories as c on
		b.category_id=c.id WHERE  b.category_id=".$cat." $where ";
		
		$query = $this->db->query($sql);
		if($query->num_rows() == ''){
			$return  =0;
		}else{
			$return =  $query->result();
		}
		return $return;
	}
	public function GetProductsPlan($cat,$operator="",$num_rec_per_page1,$start_from1){
		$this->load->library('session');
	
		$where="";
		if($operator != ""){
			$where.=" and a.operator=".$operator;
		}
		$sql = "SELECT a.id FROM `gc_products` as a left join gc_category_products as b on a.id = b.product_id left join gc_categories as c on
		b.category_id=c.id WHERE  b.category_id=".$cat." $where limit ".$num_rec_per_page1." offset ".$start_from1."";
		//echo $sql; exit;
		$query = $this->db->query($sql);
		if($query->num_rows() == ''){
			$return  =0;
		}else{
			$return =  $query->result();
		}
		return $return;
	}
}