<?php
Class Restaurant_model extends CI_Model
{

    function get_restaurants()
    {
        $this->db->select('*');
        $this->db->order_by('restaurant_name', 'ASC');
        $result = $this->db->get('restaurant');
        
        $restaurants = array();
        foreach($result->result() as $rest)
        {
            $restaurants[]   = $rest;
        }
        
        return $restaurants;
    }
 
    function get_restaurant($id)
    {
        return $this->db->get_where('restaurant', array('restaurant_id'=>$id))->row();
    }
    
   
    function save($restaurant)
    {
        if ($restaurant['restaurant_id'])
        {
            $this->db->where('restaurant_id', $restaurant['restaurant_id']);
            $this->db->update('restaurant', $restaurant);
            
            return $restaurant['restaurant_id'];
        }
        else
        {
            $this->db->insert('restaurant', $restaurant);
            return $this->db->insert_id();
        }
    }
    
    function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('restaurant');
        
        //delete references to this category in the product to category table
        $this->db->where('restaurant_id', $id);
		$this->db->delete('restaurant_menu');
        $this->db->delete('menu_categories');
    }
}