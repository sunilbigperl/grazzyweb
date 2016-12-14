<?php
Class Restaurant_model extends CI_Model
{

    function get_restaurants()
    {
		$userdata = $this->session->userdata('admin');
        $this->db->select('*');
		if($this->auth->check_access('Restaurant manager')){ 
		 $this->db->where('restaurant_manager', $userdata['id']);
		}
        $this->db->order_by('restaurant_name', 'ASC');

        $result = $this->db->get('restaurant');
        
        $restaurants = array();
        foreach($result->result() as $rest)
        {
            $restaurants[]   = $rest;
        }
        
        return $restaurants;
    }
	
	function GetMessages(){
		$yes = date('Y-m-d H:i:s',strtotime("-1 days"));
		$today =  date('Y-m-d H:i:s');
		$userdata = $this->session->userdata('admin');
		$sql = $this->db->query("select * from restaurant_messages a, restaurant b where a.restaurant_id = b.restaurant_id and b.restaurant_manager='".$userdata['id']."'
		and date between '".$yes."' and '".$today."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result_array();
		}else{
			$result = 0;
		}
		return $result;
	}
	function get_managers(){
		 $this->db->select('*');
		 $this->db->where('access', 'Restaurant manager');
		 $result = $this->db->get('admin');
		 $managers = array();
        foreach($result->result() as $rest)
        {
            $managers[]   = $rest;
        }
        
        return $managers;
	}
    function get_restaurant($id,$related_pitstops=true)
    {
        $result = $this->db->get_where('restaurant', array('restaurant_id'=>$id))->row();
		if(!$result)
		{
			return false;
		}
		
		$sql	=  "select * from pitstop_restaurants a, pitstops b where a.restaurants_id = ".$id." and a.pitstop_id =b.pitstop_id";
		$query = $this->db->query($sql);
		
		if($query->num_rows() > 0){
			$result->related_pitstops	= $query->result();
		}
		else
		{
			$result->related_pitstops	= array();
		}
		
		return $result;
    }
    
   
    function save($restaurant,$pitstops)
    {
        if ($restaurant['restaurant_id'])
        {
            $this->db->where('restaurant_id', $restaurant['restaurant_id']);
            $this->db->update('restaurant', $restaurant);
            
            $id= $restaurant['restaurant_id'];
        }
        else
        {
            $this->db->insert('restaurant', $restaurant);
            $id= $this->db->insert_id();
        }
		
		if(count($pitstops) > 0){
			$this->db->where('restaurants_id', $id);
			$this->db->delete('pitstop_restaurants');
			foreach($pitstops as $pitstop){
				$pitstop_restaurants = array('pitstop_id'=> $pitstop,'restaurants_id'=>$id);
				$this->db->insert('pitstop_restaurants', $pitstop_restaurants);
				
			}
		}
    }
    
    function delete($id)
    {
        $this->db->where('restaurant_id', $id);
        $this->db->delete('restaurant');
        
        //delete references to this category in the product to category table
        $this->db->where('restaurant_id', $id);
		$this->db->delete('restaurant_menu');
		
    }
	
	function pitstops_autocomplete($name, $limit)
	{
		return	$this->db->like('pitstop_name', $name)->get('pitstops', $limit)->result();
	}
	
	function RestaurantStatusChange($data){
		
		 if ($data['restaurant_id'])
        {
            $this->db->where('restaurant_id', $data['restaurant_id']);
            $this->db->update('restaurant', $data);
        }
	}
}