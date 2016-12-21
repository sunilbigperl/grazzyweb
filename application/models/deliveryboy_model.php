<?php
Class Deliveryboy_model extends CI_Model
{

    var $CI;

    function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->CI->load->database(); 
        $this->CI->load->helper('url');
    }
	function get_deliverypartner_list(){
		$sql = $this->db->query("select * from admin where access='Deliver manager'");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
    function get_lists()
    {
        $result = $this->db->get('delivery_boy');
        return $result->result();
    }
    
	function get_deliveryboys($parent)
	{
		
		$this->db->order_by('id', 'ASC');
		$this->db->where('id', $parent);
		$this->db->where('enabled', 1);
		$result = $this->db->get('delivery_boy')->result();
		//echo $this->db->last_query(); exit;
		$return	= array();
		foreach($result as $page)
		{

			$return[$page->id]				= $page;
		}
		
		return $return;
	}
	
	 function get_deliveryboy($id)
    {
        $result = $this->db->get_where('delivery_boy', array('id'=>$id));
        return $result->row();
    }
   
    
    function save($delivery_boy)
    {
        if ($delivery_boy['id'])
        {
            $this->db->where('id', $delivery_boy['id']);
            $this->db->update('delivery_boy', $delivery_boy);
            return $delivery_boy['id'];
        }
        else
        {
            $this->db->insert('delivery_boy', $delivery_boy);
            return $this->db->insert_id();
        }
    }
    
    function deactivate($id)
    {
        $customer   = array('id'=>$id, 'active'=>0);
        $this->save_customer($customer);
    }
    
    function DeleteDeliveryBoy($id)
    {
       
        $this->db->where('id', $id);
        $this->db->delete('delivery_boy');
        
        
    }
}