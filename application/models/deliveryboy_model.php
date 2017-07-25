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
	
	function get_deliveryPartnerorders($id){
		$sql = $this->db->query("select * from orders where delivery_partner='".$id."' and delivery_partner_status = 'Accepted'");
		if($sql->num_rows() > 0){
			$result	= $sql->result();
		}else{
			$result = 0;
		}
		return $result;
	}
    function get_lists()
    {
		$userdata = $this->session->userdata('admin');
		$this->db->order_by('id', 'ASC');
		$this->db->where('delivery_partner', $userdata['id']);
		$this->db->where('enabled', 1);
        $result = $this->db->get('delivery_boy');
		//echo $this->db->last_query(); exit;
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

			$return[]				= $page;
		}
		
		return $return;
	}
	
	 function get_deliveryboy($id)
    {
        $result = $this->db->get_where('delivery_boy', array('id'=>$id));
        return $result->row();
    }
    function get_deliveryPartner($id)
    {
        $result = $this->db->get_where('admin', array('id'=>$id,'access'=>'Deliver manager'));
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
	
	function GetReviewDelPartner($id){
		$sql = $this->db->query("select a.*,b.firstname,c.feedbacktype from feedback a, admin b,feedbacktype c where a.feedbackfrom=b.id and a.feedbacktype=5 and a.feedbacktype=c.feedbacktype_id and a.feedbackto='".$id."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			$sql1 = $this->db->query("select AVG(ratings) as avg from feedback where feedbacktype=5 and feedbackto='".$id."'");
			$result['avg']	= $sql1->result();
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function get_ListValues($id){
		$sql = $this->db->query("select * from delpartner_charges where delpartner_id='".$id."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			
		}else{
			$result = 0;
		}
		return $result;
	}
	
	function SaveCharges($data,$id){
		
		$sql = $this->db->query("delete from delpartner_charges where delpartner_id='".$id."'");
		if($sql){
			foreach($data as $datas){
				$datas['delpartner_id'] = $id;
				$this->db->insert('delpartner_charges', $datas);
			}
		}
	}
	function ChangeStatus($data){
		if ($data['id'])
        {
			$this->db->where('id', $data['id']);
            $this->db->update('admin', $data);
			return true;
        }
		
	}
	
	function check_phone($str, $id=false)
    {
        $this->db->select('phone');
        $this->db->from('delivery_boy');
        $this->db->where('phone', $str);
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

    function GetReviewboyPartner($id,$type)
    {
    	$sql = $this->db->query("select a.*,b.name from feedback a, delivery_boy b where a.feedbackfrom=b.id and a.feedbacktype='".$type."' and a.feedbackto='".$id."'");
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			$sql1 = $this->db->query("select AVG(ratings) as avg from feedback where feedbacktype='".$type."' and feedbackto='".$id."'");
			$result['avg']	= $sql1->result();
		}else{
			$result = 0;
		}
		return $result;
    }

    function GetReviewRest($id){
		$sql = $this->db->query("select a.*,b.restaurant_name from feedback a, restaurant b where a.feedbackfrom=b.restaurant_id and a.feedbacktype=3 and  a.feedbackto='".$id."'");
        // echo "select a.*,b.restaurant_name from feedback a, restaurant b where a.feedbackfrom=b.restaurant_id and a.feedbacktype='".$type."' and a.feedbackto='".$id."'";exit;
		if($sql->num_rows() > 0){
			$result['data']	= $sql->result();
			$sql1 = $this->db->query("select AVG(ratings) as avg from feedback where feedbacktype=3 and feedbackto='".$id."'");
			$result['avg']	= $sql1->result();
		}else{
			$result = 0;
		}
		return $result;
	}
}