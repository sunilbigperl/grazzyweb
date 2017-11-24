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

    function Inserthere($here){
		
		foreach($here as $men){
			foreach($men as $pitstop){
				$sql =$this->db->query("INSERT INTO `here`(`name`, `coordinates`, `city`, `enabled`) 
				VALUES ('".$pitstop['Name']."','".$pitstop['coordinates']."','".$pitstop['city']."','".$pitstop['enabled']."')");
			}
		}
	}

	function delete($id)
    {

        $this->db->where('id', $id);
        $this->db->delete('here');
		
    }

    function ChangeStatus($data){
		if ($data['id'])
        {
			$this->db->where('id', $data['id']);
            $this->db->update('here', $data);
			//echo $this->db->last_query(); exit;
			return true;
        }
		
	}
}