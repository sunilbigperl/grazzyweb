<?php
Class Number_model extends CI_Model
{

	function GetCategories(){
		
		$sql = 'SELECT * FROM  gc_categories where parent_id =0 and enabled=1';
		$query = $this->db->query($sql);
		
		$numbers =	$query->result();
	
		
		return $numbers;
	}
	/********************************************************************
	Page functions
	********************************************************************/
	function get_numbers($parent = 0)
	{
		$this->db->order_by('id', 'ASC');
		$this->db->where('id', $parent);
		$this->db->where('active', 1);
		$result = $this->db->get('numbers')->result();
		//print_r($this->db->last_query()); exit;
		$return	= array();
		foreach($result as $page)
		{

			// Set a class to active, so we can highlight our current page
			if($this->uri->segment(1) == $page->slug) {
				$page->active = true;
			} else {
				$page->active = false;
			}

			$return[$page->id]				= $page;
			$return[$page->id]->children	= $this->get_numbers($page->id);
		}
		
		return $return;
	}
	
	function GetOperators($id){
		$sql = 'SELECT * FROM gc_categories where parent_id="'.$id.'" and company=1';
		$query = $this->db->query($sql);
		
		$numbers =	$query->result();
	
		
		return $numbers;
		
	}
	
	function getsubcategories($id){
		$sql = 'SELECT * FROM gc_categories where parent_id="'.$id.'" and company =0';
		$query = $this->db->query($sql);
		
		$numbers =	$query->result();
	
		
		return $numbers;
		
	}
	
	function get_numbers_tiered()
    {
		
	/*	$sql = 'SELECT * FROM `gc_numbers` left join gc_categories on gc_numbers.operator = gc_categories.id and gc_numbers.active=1';*/
		$sql = 'SELECT * FROM `gc_numbers` left join gc_categories on gc_numbers.operator = gc_categories.id ';
		$query = $this->db->query($sql);
		
		$numbers =	$query->result();
	
		
		return $numbers;
	}

	function get_page($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->get('numbers')->row();
		
		return $result;
	}
	
	function get_slug($id)
	{
		$page = $this->get_page($id);
		if($page) 
		{
			return $page->slug;
		}
	}
	
	function save($data)
	{
		if($data['id'])
		{
			$this->db->where('id', $data['id']);
			$this->db->update('numbers', $data);
			return $data['id'];
		}
		else
		{
			$this->db->insert('numbers', $data);
			return $this->db->insert_id();
		}
	}
	
	function delete_page($id)
	{
		//delete the page
		$this->db->where('id', $id);
		$this->db->delete('numbers');
	
	}
	
	function get_page_by_slug($slug)
	{
		$this->db->where('slug', $slug);
		$result = $this->db->get('numbers')->row();
		
		return $result;
	}
}