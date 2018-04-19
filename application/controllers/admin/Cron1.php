<?php
class Cron1 extends Admin_Controller
{

	function __construct()
	{
		parent::__construct();
		
		
	}

	
	
	public function RestCron(){
		date_default_timezone_set('Asia/Calcutta');
		$date = date('Y-m-d H:i:s');
		$currentDate = strtotime($date);
		$futureDate = $currentDate-(60*4);
		$formatDate = date("Y-m-d H:i:s", $futureDate);
		
		$sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and a.restaurant_manager_status = 0 and a.status='Order Placed' and ordered_on < '".$formatDate."'");
		if($sql->num_rows() > 0){
			$result =  $sql->result_array();
			foreach($result as $row){
				$this->db->query("update orders set status='order cancelled' where id='".$row['id']."'");
				
				 
			}
		}
	}
	

	


}