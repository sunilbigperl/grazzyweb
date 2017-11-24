<?php
Class Here_model extends CI_Model
{
   function get_here($id)
    {
		
		$result	= $this->db->get_where('here', array('id'=>$id))->row();
		if(!$result)
		{
			return false;
		}
		
		return $result;
    }

    function get_here_tiered($admin = false)
    {
        if(!$admin)
        $this->db->where('enabled', 1);
		$this->db->order_by('name', 'ASC');
        $here = $this->db->get('here')->result();
      
        return $here;
    }

    function save($pitstop)
    {
		
        if ($pitstop['id'])
        {
            $this->db->where('id', $pitstop['id']);
            $this->db->update('here', $pitstop);
            
            $id= $pitstop['id'];
        }
        else
        {
            $this->db->insert('here', $pitstop);
            $id = $this->db->insert_id();
        }
		
	
    }
}