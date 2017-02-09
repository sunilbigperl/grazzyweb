<?php
Class Pitstop_model extends CI_Model
{

    function get_pitstops_tiered($admin = false)
    {
        if(!$admin)
        $this->db->where('enabled', 1);
        $this->db->order_by('pitstop_name', 'ASC');
        $pitstops = $this->db->get('pitstops')->result();
      
        return $pitstops;
    }
	
   function InsertPitstops($pitstops){
		
		foreach($pitstops as $men){
			foreach($men as $pitstop){
				$sql =$this->db->query("INSERT INTO `pitstops`(`pitstop_name`, `latitude`, `langitude`, `enabled`) 
				VALUES ('".$pitstop['pitstop_name']."','".$pitstop['latitude']."','".$pitstop['langitude']."','".$pitstop['enabled']."')");
			}
		}
	}
	
	function ChangeStatus($id,$status){
		$sql = $this->db->query("update pitstops set enabled='".$status."' where pitstop_id='".$id."'");
		if($sql){ return true; }
	}
	
    function get_pitstop($id,$related_restaurants=true)
    {
		
		$result	= $this->db->get_where('pitstops', array('pitstop_id'=>$id))->row();
		if(!$result)
		{
			return false;
		}
		
		$sql	=  "select * from pitstop_restaurants a, restaurant b where a.pitstop_id = ".$id." and a.restaurants_id =b.restaurant_id";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0){
			$result->related_restaurants	= $query->result();
		}
		else
		{
			$result->related_restaurants	= array();
		}
		return $result;
    }
    
    function get_category_products_admin($id)
    {
        $this->db->order_by('sequence', 'ASC');
        $result = $this->db->get_where('category_products', array('category_id'=>$id));
        $result = $result->result();
        
        $contents   = array();
        foreach ($result as $product)
        {
            $result2    = $this->db->get_where('products', array('id'=>$product->product_id));
            $result2    = $result2->row();
            
            $contents[] = $result2; 
        }
        
        return $contents;
    }
    
   
    
    function save($pitstop,$restaurants)
    {
		
        if ($pitstop['pitstop_id'])
        {
            $this->db->where('pitstop_id', $pitstop['pitstop_id']);
            $this->db->update('pitstops', $pitstop);
            
            $id= $pitstop['pitstop_id'];
        }
        else
        {
            $this->db->insert('pitstops', $pitstop);
            $id = $this->db->insert_id();
        }
		
		if(count($restaurants) > 0){
			 $this->db->where('pitstop_id', $id);
			$this->db->delete('pitstop_restaurants');
			foreach($restaurants as $restaurant){
				$pitstop_restaurants = array('pitstop_id'=> $id,'restaurants_id'=>$restaurant);
				$this->db->insert('pitstop_restaurants', $pitstop_restaurants);
				
			}
		}
    }
    
    function delete($id)
    {
        $this->db->where('pitstop_id', $id);
        $this->db->delete('pitstops');
        
        //delete references to this category in the product to category table
       // $this->db->where('category_id', $id);
        //$this->db->delete('category_products');
    }
	
	function restaurants_autocomplete($name, $limit)
	{
		return	$this->db->like('restaurant_name', $name)->get('restaurant', $limit)->result();
	}
}