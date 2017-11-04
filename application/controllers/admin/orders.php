<?php

class Orders extends Admin_Controller {

    function __construct()
    {
        parent::__construct();

        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
        $this->load->model('Order_model');
		$this->load->model('Customer_model');
		$this->load->model('Message_model');
		$this->load->model('Restaurant_model');
		$this->load->model('Roadrunner_model');
		$this->load->model('Pitstop_model');
		$this->load->model('Deliveryboy_model');
		$this->load->helper('url');
		$this->load->library('session');

    }

	function dashboard(){
		$data['orders'] = $this->Order_model->get_neworders();
		 $this->view($this->config->item('admin_folder').'/restaurantdatshboard',$data);
	}
    function neworders()
    {
        $data['orders'] = $this->Order_model->get_neworders();
        $this->view($this->config->item('admin_folder').'/neworders', $data);
    }

	function delpartnerorders()
    {
        $data['orders'] = $this->Order_model->get_delpartnerorders();
		$data['deliveryboys'] = $this->Order_model->get_deliveryboys();
        $this->view($this->config->item('admin_folder').'/delpartnerorders', $data);
    }

	function AssignDeliveryBoy($id){
		$data['delBoy'] = $this->input->post('deliveryboy');
		$data['id'] = $id;
		$results = $this->Order_model->AssignDeliveryBoy($data);
		if($results){
			 redirect('admin/orders/delpartnerorders', 'refresh');
		}

	}

	function orders()
    {
        $data['orders'] = $this->Order_model->get_deliverypartnerneworders();
        $this->view($this->config->item('admin_folder').'/delorders', $data);
    }

	function previousorders(){
		$data['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of this month'));
		$data['todate'] =  date('Y-m-d H:i:s',strtotime('last day of this month'));
		$data['orders'] = $this->Order_model->get_previousorders($data);
		//$data['orders'] = "";
        $this->view($this->config->item('admin_folder').'/previousorders',$data);
	}
	function previousordersdelpartner(){

		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdated'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = $_SESSION['todated'] = date("y-m-d H:i:s",strtotime($this->input->post('todate')));
			$delpartner_post = $this->input->post('delpartner');
			$delpartner_get = $this->uri->segment(4);
			$data['delpartner'] = isset($delpartner_get) ? $delpartner_get : $delpartner_post;
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] = $_SESSION['fromdated'] = date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  $_SESSION['todated'] =date('Y-m-d H:i:s',strtotime('last day of last month'));
			$delpartner_post = $this->input->post('delpartner');
			$delpartner_get = $this->uri->segment(4);
			$data['delpartner'] = isset($delpartner_get) ? $delpartner_get : $delpartner_post;
		}else{

			$data['fromdate'] =  $_SESSION['fromdated'] = date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] = $_SESSION['todated'] = date('Y-m-d H:i:s',strtotime('last day of this month'));
			$delpartner_post = $this->input->post('delpartner');
			$delpartner_get = $this->uri->segment(4);
			$data['delpartner'] = isset($delpartner_get) ? $delpartner_get : $delpartner_post;
		}
		
		//$sql = $this->db->query("select * from delpartner_charges where id = 1");
               $sql = $this->db->query("select * from charges where id = 1");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['servicetax'] = $res[0]['servicetax'];
			$data['deliverycharge'] = $res[0]['deliverycharge'];
		}else{
			$data['servicetax'] = '';
			$data['deliverycharge'] = '';    
		}
		
		$data['orders'] = $this->Order_model->get_previousorders($data);
		// $data['orders1'] = $this->Order_model->get_previousorders1($data);
		$this->view($this->config->item('admin_folder').'/previousordersdelpartner',$data);
	}
	function GetPreviousOrders(){
		
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = $_SESSION['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));
			$data['delpartner'] = $this->input->post('delpartner');
		}elseif($this->input->post('action') == "Previous Month"){
			$data['fromdate'] = $_SESSION['fromdate'] =  date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of last month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of this month'));
			$data['delpartner'] = $this->input->post('delpartner');
		}
		$sql = $this->db->query("select * from charges where id = 1");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['servicetax'] = $res[0]['servicetax'];
			$data['deliverycharge'] = $res[0]['deliverycharge'];
		}else{
			$data['servicetax'] = '';
			$data['deliverycharge'] = '';
		}
		$data['orders'] = $this->Order_model->get_previousorders($data);
		$this->view($this->config->item('admin_folder').'/previousorders',$data);
	}

	

	function GetRestPreviousOrders($id){
		$this->load->model('Restaurant_model');
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			// $data['todate'] = $_SESSION['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime($this->input->post('todate').' +1 day'));

		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of this month'));

		}
		$data['id'] = $id;
		$restaurant  = $this->Restaurant_model->get_restaurant($id);

		$data['page_title'] = "Previous Orders and sales of ".$restaurant->restaurant_name;
		$data['orders'] = $this->Order_model->get_restpreviousorders($data);
		$sql = $this->db->query("select * from charges where id = 1");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['servicetax'] = $res[0]['servicetax'];
			$data['deliverycharge'] = $res[0]['deliverycharge'];
		}else{
			$data['servicetax'] = '';
			$data['deliverycharge'] = '';
		}
		$this->view($this->config->item('admin_folder').'/previousordersrest',$data);
	}

	function GetRestPreviousOrdersbill(){
		$this->load->model('Restaurant_model');
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = $_SESSION['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));

		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of this month'));

		}
		
		
		$this->view($this->config->item('admin_folder').'/previousordersrestbill',$data);
	}

	function GetRestPreviousOrdersbill1(){
		$this->load->model('Restaurant_model');
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdate'] = date("Y-m-d H:i:s",strtotime($this->input->post('fromdate')));
			$data['todate'] = $_SESSION['todate'] = date("Y-m-d H:i:s",strtotime($this->input->post('todate')));

		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of last month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d H:i:s',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d H:i:s',strtotime('last day of this month'));

		}
		
		
		$this->view($this->config->item('admin_folder').'/previousordersrestbill1',$data);
	}
	
	function restbill($id,$type){
		$fromdate=$this->input->post('fromdate');
		$todate=$this->input->post('todate');

		if($this->input->post('action') == "Go"){
			$data['fromdate'] =  $_SESSION['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			// $data['todate'] = $_SESSION['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime($this->input->post('todate').' +1 day'));
			$data['todate1'] = $_SESSION['todate1'] = date("Y-m-d",strtotime($this->input->post('todate')));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  $_SESSION['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));

		}
		
		
		$data['date'] = date("Y-m-d");
		$restaurant       = $this->Restaurant_model->get_restaurant($id);
		//print_r($restaurant); exit;
		$orders = $this->Restaurant_model->get_restaurantorders($id);
		$corders = $this->Restaurant_model->get_restaurantorderscancel($id);
		$data['name'] = $restaurant->restaurant_name;
		$data['address'] = $restaurant->restaurant_address;
		$data['branch'] = $restaurant->restaurant_branch;
		$data['email'] = $restaurant->restaurant_email;
		
		//$data['id'] = $id;
		//$data['orders1'] = $this->Restaurant_model->get_restorders($data);

		 // $sql1 = $this->db->query("select SUM(total_cost) FROM `orders` where restaurant_id='".$id."' and ordered_on >='2017-01-29' and ordered_on <='2017-02-28'  ");

        // print_r("select SUM(total_cost) FROM `orders` where restaurant_id='".$id."' and ordered_on >='".$fromdate."' and ordered_on <= '".$todate."'  ");exit;

		 $sql1=$this->db->query("select SUM(total_cost) FROM `orders` where restaurant_id='".$id."' and ordered_on >='".$_SESSION['fromdate']."' and ordered_on <= '".$_SESSION['todate']."'  ");
		
		if($sql1->num_rows() > 0){
			$res1	= $sql1->result_array();
			$data['cost'] = $res1[0]['SUM(total_cost)'];
			
			
		}else{

			$data['cost'] = '';
			
		}
	
		if($corders == 0){ $data['cancelorders'] = 0;}else{ $data['cancelorders'] = count($corders);
}

		if($orders == 0){ $data['noorders'] = 0;}else{ $data['noorders'] = count($orders);

		 }
		 $sql1 = $this->db->query("select servicetax from charges order by start_date desc limit 1 ");
		 if($sql1->num_rows() > 0){
			$res	= $sql1->result_array();
			$data['servicetax'] = $res[0]['servicetax'];
			
		 }else{
			$data['servicetax'] = '';
			
		}
		$sql = $this->db->query("select * from restaurant where restaurant_id ='".$id."' ");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['commission'] = $res[0]['commission'];
			$data['penalty'] = $res[0]['penalty'];
			$data['reimb'] = $res[0]['reimb'];
		}else{
			$data['commission'] = '';
			$data['penalty'] = '';
			$data['reimb'] = '';
		}
		$data['commision1']=(($data['cost']*$data['commission'])/100);
		$data['servicetax1']=(($data['commision1']*$data['servicetax'])/100);
		$data['reimb1'] = $data['noorders']*$data['reimb'];
		$data['servicetax2']=(($data['reimb1']*$data['servicetax'])/100);
		$data['penalty1'] = $data['cancelorders']*$data['penalty'];
		$data['servicetax3']=(($data['penalty1']*$data['servicetax'])/100);
		$data['total1']=$data['commision1']+$data['servicetax1'];
		$data['total2']=$data['reimb1']+$data['servicetax2'];
		$data['total3']=$data['penalty1']+$data['servicetax3'];
		$data['totalbill']=$data['total1']+$data['total2']+$data['total3'];
		$data['netamount']=$data['cost']-$data['totalbill'];
		


		$html =$this->load->view($this->config->item('admin_folder').'/restbill',$data,true);
		$filename = $restaurant->restaurant_name;
		if($type == "pdf"){
            $fnamee = $filename.date('Y-m-d',strtotime($_SESSION['fromdate'])).date('Y-m-d',strtotime($_SESSION['todate'])).".pdf";
			$filename  = "bills/".$fnamee;
		
		}else{
			
			$fnamee =  $filename.date('Y-m-d',strtotime($_SESSION['fromdate'])).date('Y-m-d',strtotime($_SESSION['todate'])).".xls";
			 $filename  = "bills/".$fnamee;
			 
		} 
		
	     fopen($filename,"w");
		chmod($fnamee,0777);
		$this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");
	    //redirect("http://localhost/grazzyweb/".$filename);
		redirect("http://app.eatsapp.in/".$filename);

	}


	public function custbill($id,$type){

       $data['date'] = date("Y-m-d");

       $customer       = $this->Customer_model->get_customer1($id);
		//print_r($customer); exit;
	  
		
		$data['firstname'] = $customer->firstname;
		$data['email'] = $customer->email;
		
		$sql = $this->db->query("select * from charges order by start_date desc limit 1  ");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['servicetax'] = $res[0]['servicetax'];
			$data['deliverycharge'] = $res[0]['deliverycharge'];
			
		}else{
			$data['servicetax'] = '';
			$data['deliverycharge'] = '';
			
		}

		$data['deliverycharge1'] = (($data['deliverycharge'])/1.18);
		$data['servicetax1']=(($data['deliverycharge1']*$data['servicetax']/100)/2);
		$data['totalbill']=$data['deliverycharge1']+$data['servicetax1']+$data['servicetax1'];
		
		
		


		$html =$this->load->view($this->config->item('admin_folder').'/custbill',$data,true);
		$filename ='custbill';
		if($type == "pdf"){
            $fnamee = $filename.".pdf";
			$filename  = "bills/".$fnamee;
		
		}
		// else{
			
		// 	$fnamee =  $filename.".xls";
		// 	 $filename  = "bills/".$fnamee;
			 
		// } 
		
	     fopen($filename,"w");
		chmod($fnamee,0777);
		$this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");
		redirect("http://localhost/grazzyweb/".$filename);
		//redirect("http://app.eatsapp.in/".$filename);

	}

	public function custrestbill($type){

       $data['date'] = date("Y-m-d");
		
		// $sql = $this->db->query("select * from charges order by start_date desc limit 1  ");
		// if($sql->num_rows() > 0){
		// 	$res	= $sql->result_array();
		// 	$data['servicetax'] = $res[0]['servicetax'];
		// 	$data['deliverycharge'] = $res[0]['deliverycharge'];
			
		// }else{
		// 	$data['servicetax'] = '';
		// 	$data['deliverycharge'] = '';
			
		// }
		//  $data['deliverycharge1']=(($data['deliverycharge'])/1.18);
		//  $data['servicetax1']=(($data['servicetax']*9)/100);
		//  $data['totalbill']=$data['deliverycharge1']+$data['servicetax1']+$data['servicetax1'];
		
		


		$html =$this->load->view($this->config->item('admin_folder').'/custrestbill',$data,true);
		$filename ='custrestbill';
		if($type == "pdf"){
            $fnamee = $filename.".pdf";
			$filename  = "bills/".$fnamee;
		
		}else{
			
			$fnamee =  $filename.".xls";
			 $filename  = "bills/".$fnamee;
			 
		} 
		
	     fopen($filename,"w");
		chmod($fnamee,0777);
		$this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");
		redirect("http://localhost/grazzyweb/".$filename);
		//redirect("http://app.eatsapp.in/".$filename);

	}


	function excel($id)
	{
		$this->load->model('Restaurant_model');
		if($this->input->post('action') == "Go"){
			$data['fromdate'] =  $_SESSION['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			// $data['todate'] = $_SESSION['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime($this->input->post('todate').' +1 day'));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  $_SESSION['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			 $data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));

		}
		 $restaurant       = $this->Restaurant_model->get_restaurant($id);
		 $orders = $this->Restaurant_model->get_restaurantorders($id);
	     $corders = $this->Restaurant_model->get_restaurantorderscancel($id);
		
		
	$this->load->library('excel');
    //Create a new Object
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0); 

    $heading=array('Fromdate','Todate','Value of orders forwarded','Commission%','Commission','GST%','GST','Total','Number of Orders','Reimbursement of Delivery Charges','Reimbursement of Delivery Charges','GST%','GST','Total','Number of Orders Cancelled','Penalty','Penalty','GST%','GST','Total','Total Bill(Keep Amount)','Value of orders forwarded','Amount due to us(Keep Amount)','Netamount payable to you'); //set title in excel sheet
    $rowNumberH = 1; //set in which row title is to be printed
    $colH = 'A'; //set in which column title is to be printed
    
    $objPHPExcel->getActiveSheet()->getStyle($rowNumberH)->getFont()->setBold(true);
    
	for($col = ord('A'); $col <= ord('X'); $col++){ //set column dimension 
		 $objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         $objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
	}
    foreach($heading as $h){ 

        $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
        $colH++;    
    }


	$sql1=$this->db->query("select SUM(total_cost) FROM `orders` where restaurant_id='".$id."' and ordered_on >='".$data['fromdate']."' and ordered_on <= '".$data['todate']."'  ");
		
		if($sql1->num_rows() > 0){
			$res1	= $sql1->result_array();
			$data['cost'] = $res1[0]['SUM(total_cost)'];
			
		}else{

			$data['cost'] = '';
			
		}

		$sql = $this->db->query("select *,b.servicetax from restaurant a,charges b where a.restaurant_id ='".$id."' order by b.start_date desc limit 1 ");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['commission'] = $res[0]['commission'];
			$data['commision1']=(($data['cost']*$data['commission'])/100);
			$data['servicetax'] = $res[0]['servicetax'];
			$data['servicetax1']=(($data['commision1']*$data['servicetax'])/100);
			$data['total1']=$data['commision1']+$data['servicetax1'];
			if($orders == 0){ $data['noorders'] = 0;}else{ $data['noorders'] = count($orders);	 }
			$data['reimb'] = $res[0]['reimb'];
			$data['reimb1'] = $data['noorders']*$data['reimb'];
			$data['servicetax2']=(($data['reimb1']*$data['servicetax'])/100);
			$data['total2']=$data['reimb1']+$data['servicetax2'];
		 		if($corders == 0){ $data['cancelorders'] = 0;}else{ $data['cancelorders'] = count($corders); }
			$data['penalty'] = $res[0]['penalty'];
			$data['penalty1'] = $data['cancelorders']*$data['penalty'];
			$data['servicetax3']=(($data['penalty1']*$data['servicetax'])/100);
			$data['total3']=$data['penalty1']+$data['servicetax3'];
			$data['totalbill']=$data['total1']+$data['total2']+$data['total3'];
			$data['netamount']=$data['cost']-$data['totalbill'];
		}else{
			$data['commission'] = '';
			$data['commision1']='';
			$data['servicetax'] = '';
			$data['servicetax1']='';
			$data['total1']='';
			$data['reimb'] = '';
			$data['reimb1'] = '';
			$data['servicetax2']='';
			$data['penalty'] = '';
			$data['penalty1'] = '';
			$data['servicetax3']='';
			$data['total3']='';
			$data['totalbill']='';
			$data['netamount']='';
		}
		
        $rowCount = 2; // set the starting row from which the data should be printed
     
	

        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $data['fromdate']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $data['todate']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $data['cost']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $data['commission']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $data['commision1']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $data['servicetax']); 
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $data['servicetax1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $data['total1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $data['noorders']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $data['reimb']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $data['reimb1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $data['servicetax']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $data['servicetax2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $data['total2']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $data['cancelorders']);
        $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $data['penalty']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $data['penalty1']);
        $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount, $data['servicetax']);
        $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount, $data['servicetax3']);
        $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount, $data['total3']);
        $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount, $data['totalbill']);
        $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount, $data['cost']);
        $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount, $data['totalbill']);
         $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount, $data['netamount']);

        
          
         $rname= $restaurant->restaurant_name;
	 	 $filename=$rname.date('Y-m-d',strtotime($_SESSION['fromdate'])).date('Y-m-d',strtotime($_SESSION['todate'])).'.xls'; //save our workbook as this file name

    // Instantiate a Writer 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'" ');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    //exit();

	$this->load->view($this->config->item('admin_folder').'/restbill',$data,true);
		
		
	}

	function delpartnerbill($id,$type){
		if($this->input->post('action') == "Go"){
			$data['fromdate'] =  $_SESSION['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			// $data['todate'] = $_SESSION['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime($this->input->post('todate').' +1 day'));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  $_SESSION['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] = $_SESSION['todate'] = date('Y-m-d',strtotime('last day of last month'));

		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));

		}
		$data['date'] = date("Y-m-d");
		$Deliveryboy       = $this->Deliveryboy_model->get_deliveryPartner($id);
		$orders = $this->Deliveryboy_model->get_deliveryPartnerorders($id);
		if($orders == 0){ $data['deliveries'] = 0;}else{ $data['deliveries'] = count($orders); }
		$data['name'] = $Deliveryboy->firstname;
		$data['email'] = $Deliveryboy->email;
		$sql = $this->db->query("select * from charges where id = 1");
		if($sql->num_rows() > 0){
			$res	= $sql->result_array();
			$data['servicetax1'] = $res[0]['servicetax'];
			$data['rate'] = $res[0]['deliverycharge'];
		}else{
			$data['servicetax1'] = '';
			$data['rate'] = '';
		}
		$data['delivery_charge'] = $data['rate'] * $data['deliveries'];
		$data['servicetax'] = (($data['delivery_charge'] * $data['servicetax1'])/100);
		$data['total']	=$data['delivery_charge']+ $data['servicetax'];
		$html = $this->load->view($this->config->item('admin_folder').'/delpartnertbill',$data, true);
		
		$filename = $Deliveryboy->firstname;
		 if($type == "pdf"){
		 	 $fnamee = $filename.date('Y-m-d',strtotime($_SESSION['fromdate'])).date('Y-m-d',strtotime($_SESSION['todate'])).".pdf";
			//$fnamee = rand()."purplkite0105201731052017.pdf";
			$filename  = "bills/".$fnamee;
		}else{
			$fnamee = $filename.date('Y-m-d',strtotime($_SESSION['fromdate'])).date('Y-m-d',strtotime($_SESSION['todate'])).".xls";
			//$fnamee =  rand()."purplkite0105201731052017.xls";
			 $filename  = "bills/".$fnamee;
			 
			 } 
			 
		fopen($filename,"w");
		chmod($fnamee,0777);
		$this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");
		
		redirect("http://app.eatsapp.in/".$filename);
		//redirect("http://localhost/grazzyweb/".$filename);
	}
	
	function getOrderDetails(){
		$html="";
		$data = $this->input->post('data');
		$restaurant = $this->Restaurant_model->get_restaurant($data['restaurant_id']);
		$orders = $this->Order_model->get_orders($data['id']);
		if($data['ordertype_id'] == 3){
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $cname = $customer_details->firstname." ".$customer_details->lastname;
			 $cphone = $customer_details->phone;

			 $dname1 = $customer_details->firstname." ".$customer_details->lastname;
			 $dphone = $customer_details->phone;

		}else{
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $cname = $customer_details->firstname." ".$customer_details->lastname;
			 $cphone = $customer_details->phone;
      $name = $customer_details->firstname." ".$customer_details->lastname;

			$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
			$dname = isset($deliveryboy_details->name) ? $deliveryboy_details->name : "Not Assigned Yet";
			$dphone = isset($deliveryboy_details->phone) ? $deliveryboy_details->phone : "";

		}

		$html.="<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>Order Number: ".$data['order_number']."</h4>
				  </div>
				  <div class='modal-body' class='form-horizontal'>
					<div class='form-group'>
          <table class='table table-bordered'>
          <tr>
          <th>Restaurant Name:</th><td>".$restaurant->restaurant_name."</td>
          </tr>
          <tr>
          <th>Restaurant Contact Number:</th><td>".$restaurant->restaurant_phone."</td>
          </tr>
          <tr>
						<th>Restaurant Location:</th><td>".$restaurant->restaurant_address."</td>
            </tr>
            <tr>
						<th>Customer Name:</th><td>".$customer_details->firstname." ".$customer_details->lastname."</td>
            </tr>
            <tr>
						<th>Customer Contact Number:</th><td>".$cphone."</td>
            </tr>
            <tr>
            <th>Delivery Location:</th><td>".$data['delivery_location']."</td>
            </tr>
            <tr>
            <th>Delivery Boy Name:</th><td>".$dname."</td>
            </tr>
            <tr>
						<th>Delivery Boy Contact Number:</th><td>".$dphone."</td>
            </tr>
            <tr>
						<th>Passcode:</th><td>".$data['passcode']."</td>
            </tr>
             <tr>
						<th>Scheduled Pickup Time:</th><td>".$data['keep_ready']."</td>
            </tr>
            <tr>
						<th>Scheduled Delivery Time:</th><td>".$data['delivered_on']."</td>
            </tr>

            </table>
					</div>
				</div>";
		echo $html;
	}


    function GetMenudetails(){
		$data = $this->input->post('data');
		
		$menus = $this->Order_model->GetMenudetails($data);
		
		$html="";
		if($menus != 0){
			if($data['ordertype_id'] == 3){
				
				
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $name = $customer_details->firstname." ".$customer_details->lastname;
			 $phone = $customer_details->phone;
			 $email = $customer_details->email;
			  
			}else{
				
				$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
				$name = isset($deliveryboy_details->name) ? $deliveryboy_details->name : "Not assigned yet";
				$phone = isset($deliveryboy_details->phone) ? $deliveryboy_details->phone : "";
				$email = isset($deliveryboy_details->email) ? $deliveryboy_details->email : "";
				
				
				// &ensp;</strong>(".$deliverypartnername.")
			}
			$html.="<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>Order number: ".$data['order_number']."</h4>
				  </div>
				  <div class='modal-body' class='form-horizontal'>
					<div class='form-group'>
						<label><strong>"; if($data['ordertype_id'] == 3){ $html.="Customer name";}else{$html.="Delivery boy";} $html.=":&ensp;</strong>".$name." </label></br>

						<label><strong>Mobile No:&ensp;</strong>".$phone."</label></br>
						
						
						

					</div>
					<table class='table table-bordered'>
					<thead>
						<tr><th>Item name</th><th>Amount</th><tr>
					</thead>
					<tbody>";
			foreach($menus as $menu){
					$html.="<tr><td>".$menu->menu."</td><td>".$menu->cost."</td></td>";

			}
			$html.="</tbody>
				</table>
				</div>
			  <div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			  </div>";
		}
		echo $html;
	}

	function ChangeRestMangerStatus($status,$id){
		$statuss = $this->Order_model->ChangeRestMangerStatus($status,$id);
		if($statuss && $statuss == 1){
			$data['order'] = $this->Order_model->get_order($id);
			if($data['order']->order_type != 3){
				$data['restaurant'] = $this->Restaurant_model->get_restaurant($data['order']->restaurant_id);
				$data['customer'] = $this->Customer_model->get_customer($data['order']->customer_id);
				$data['fromaddress'] = $data['restaurant']->restaurant_address;
				$data['fromcity'] = $data['restaurant']->restaurant_branch;
				if($data['order']->order_type == 1 && $data['order']->pitstop_id != ""){
					$pitstop = $this->Pitstop_model->get_pitstop($data['order']->pitstop_id);
					$data['toaddress'] = $pitstop->address;
					$data['tocity'] = $pitstop->city;
				}else{
					$data['toaddress'] = $data['order']->delivery_location;
					$data['tocity'] = $data['restaurant']->restaurant_branch;
				}
			
				
				$result = $this->Roadrunner_model->CheckServicability($data);
				$roadrunner = json_decode($result);
				if($roadrunner->status->code ==  200){
					$sql = $this->db->query("update orders set delivery_partner = '123', delivery_partner_status = 'Accepted' where id='".$id."'");
					echo '<script>alert("Order assigned success. delivered by roadrunner.")</script>';
				}
			}
		}
 



		redirect('admin/orders/dashboard', 'refresh');

	}

	function ChangeDelPartnerStatus($status,$id){
		$status = $this->Order_model->ChangeDelPartnerStatus($status,$id);
		if($status){
			 redirect('admin/orders/delpartnerorders', 'refresh');
		}
	}
	function Review($type){

		$html="";
		$userdata = $this->session->userdata('admin');
		$data = $this->input->post('data');
		if($type ==  2){
			$title = "Review Customer";
			$customer_details = $this->Customer_model->get_customer($data['customer_id']);
			$name = $customer_details->firstname;
			$id= $data['id'];
		}elseif($type == 3 || $type == 5){
			$title ="Review Delivery boy";
			$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
			$name = isset($deliveryboy_details->name) ? $deliveryboy_details->name : "";
			$id= $data['id'];
		}elseif($type == 4){
			$title = "Review Restaurant";
			$name = $data['restaurant_name'];
			$id= $data['restaurant_id'];
		}

		$html.="<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal'>&times;</button>
				<h4 class='modal-title'>".$title."</h4>
			  </div>

					<form id='review' class='form-horizontal' method='post'  action='InserReview'>
			  <div class='modal-body' class='form-horizontal'>
						<input type='hidden' name='order_number' value='".$data['order_number']."'>
						<input type='hidden' name='feedbacktype' value='".$type."'>
						<input type='hidden' name='feedbackfrom' value='".$userdata['id']."'>
						<div class='form-group col-sm-12 col-xs-12'>
							<label for='review' class='col-xs-12 col-sm-3'>Feedback to</label>
							<div class='col-sm-8 col-xs-12'>
							<input type='text' name='' class='form-control' value='".$name."' readonly>
							<input type='hidden' name='feedbackto' class='form-control' value='".$id."' readonly>
							</div>
						</div>
						<div class='form-group col-sm-12 col-xs-12'>
							<label for='review' class='col-xs-12 col-sm-3'>Review</label>
							<div class='col-sm-8 col-xs-12'>
								<textarea class='form-control'  id='Comments'  name='comments'></textarea>
							</div>
						</div>
						<h4 style='color: #fff;'>OR</h4>

							<label class='col-xs-12 col-sm-3'>Rating</label>
							<div class='col-sm-8 col-xs-12'>
								<input type='text' name='ratings' id='ratings' value='' class='form-control' >
							</div>
						</div>
						<div class='pop-btn'>

						</div>

			</div>
		  <div class='modal-footer'>
			<input type='submit' class='btn btn-danger' value='Review'>&nbsp;<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
		  </div></form>";
		$html.="
			<script>
			 $('#rating-input').rating({
			  min: 0,
			  max: 5,
			  step: 1,
			  size: 'xs'
			});
			</script>";
		echo $html;
	}

	function InserReview(){
		$data = $this->input->post();
		$this->Order_model->InserReview($data);
		redirect($_SERVER['HTTP_REFERER']);
	}
    function delete($id)
    {
        $category   = $this->Restaurant_model->get_restaurant($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($category)
        {

            $this->Restaurant_model->delete($id);

            $this->session->set_flashdata('message', "The restaurant has been deleted.");
            redirect($this->config->item('admin_folder').'/restaurant');
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }

	function pitstops_autocomplete()
	{
		$name	= trim($this->input->post('name'));
		$limit	= $this->input->post('limit');

		if(empty($name))
		{
			echo json_encode(array());
		}
		else
		{
			$results	= $this->Restaurant_model->pitstops_autocomplete($name, $limit);

			$return		= array();

			foreach($results as $r)
			{
				$return[$r->pitstop_id]	= $r->pitstop_name;
			}
			echo json_encode($return);
		}

	}

	public function ShowReviewDetails($id){
		$this->load->model('Restaurant_model');
		$customerreview = $this->Order_model->GetCustomerReview($id);
		$customerreviewavg= isset($customerreview['avg'][0]->avg) ? $customerreview['avg'][0]->avg : 0;
		$delpartnerreview = $this->Order_model->GetDelPartnerReview($id);
		$delpartnerreviewavg = isset($delpartnerreview['avg'][0]->avg) ? $delpartnerreview['avg'][0]->avg :0;

		$delboyreview = $this->Order_model->GetDelBoyReview($id);
		$delboyreviewavg = isset($delboyreview['avg'][0]->avg) ? $delboyreview['avg'][0]->avg :0;
		$restaurant       = $this->Restaurant_model->get_restaurant($id);
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$restaurant->restaurant_name."</h4>
		</div>
		<div class='modal-body'>
		<div class=''><strong>Ratings By customer:</strong> ".$customerreviewavg."</div>";
		// echo  "<div class=''><strong>Ratings By delivery partner:</strong> ".$delpartnerreviewavg."</div>";
	echo  "<div class=''><strong>Ratings By delivery boy:</strong> ".$delboyreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Order_Number</th><th>Feedbacktype</th><th>Feedback</th><th>Stars</th><th>from</th></tr></thead>
			<tbody>";
			// if($delpartnerreview['data']){
			// 	foreach($delpartnerreview['data'] as $customer){
			// 		echo "<tr><td>".$customer->date."</td><td>".$customer->order_number."</td><td>".$customer->feedbacktype."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->firstname."</td></tr>";
			// 	}
			// }
			if($customerreview['data']){
				foreach($customerreview['data'] as $customer1){
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->order_number."</td><td>".$customer1->feedbacktype."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->firstname."</td></tr>";
				}
			}

			if($delboyreview['data']){
				foreach($delboyreview['data'] as $customer1){
					echo "<tr><td>".$customer1->date."</td><td>".$customer1->order_number."</td><td>".$customer1->feedbacktype."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->name."</td></tr>";
				}
			}
			// if(isset($delboyreview['data']) && $delboyreview['data']){
			// 	foreach($delboyreview['data'] as $customer1){
			// 		echo "<tr><td>".$customer1->date."</td><td>".$customer1->comments."</td><td>".$customer1->ratings."</td><td>".$customer1->name."</td></tr>";
			// 	}
			// }
		echo "</tbody>
		</table></div>";
	}

	public function ShowReviewDetailstodelpartner($id){
		$restaurantreview = $this->Order_model->GetRestaurantreview($id);
		$delpartnerreviewavg = isset($restaurantreview['avg'][0]->avg) ? $restaurantreview['avg'][0]->avg : 0;
		$admin       = $this->Order_model->get_admin($id);
		echo  "<div class='modal-header'>
		  <button type='button' class='close' data-dismiss='modal'>&times;</button>
		  <h4 class='modal-title'>Rating & reviews of ".$admin[0]->firstname."</h4>
		</div>
		<div class='modal-body'>";
		echo  "<div class=''><strong>Ratings By restaurant:</strong> ".$delpartnerreviewavg."</div>";
		echo "<table class='table table-bordered'>
			<thead><tr><th>Date</th><th>Feedback</th><th>Stars</th><th>from</th></tr></thead>
			<tbody>";
			if($restaurantreview != 0 && $restaurantreview['data']){
				foreach($restaurantreview['data'] as $customer){
					echo "<tr><td>".$customer->date."</td><td>".$customer->comments."</td><td>".$customer->ratings."</td><td>".$customer->restaurant_name."</td></tr>";
				}
			}else{
				echo "No data found";
			}

		echo "</tbody>
		</table></div>";
	}

	public function RequestBill(){
		$data['orders'] = "";
        $this->view($this->config->item('admin_folder').'/requestbill',$data);
	}

	public function GenerateBillMail()
	{
		if($this->input->post('action') == "Go"){
			$data['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			$data['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
		}elseif($this->input->post('action') == "PreviousMonth"){
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of last month'));
		}else{
			$data['fromdate'] =  date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  date('Y-m-d',strtotime('last day of this month'));
		}
		if($data['fromdate'] > date("Y-m-d",strtotime('today')) || $data['todate'] > date("Y-m-d",strtotime('today'))){
			echo "<script>alert('Select proper dates'); location.href='http://grazzy.way2gps.com/index.php/admin/orders/RequestBill';</script>";
		}
		$data['orders'] = $this->Order_model->get_previousorders($data);
		$userdata = $this->session->userdata('admin');
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://smtp.gmail.com',
			'smtp_port' => 465,
			'smtp_user' => 'suggest.eatsapp@gmail.com',
			'smtp_pass' => 'devang123',
			'mailtype'  => 'html',
			'charset'   => 'utf-8',
			'newline'    => "\r\n"
		);
		$this->load->library('email',$config);
		$this->email->from('order@eatsapp.in', 'EatsApp');
		$this->email->to($userdata['email']);

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');



		$html= $this->load->view($this->config->item('admin_folder').'/bill',$data,true);

        $filename  = "bill.pdf";

        $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($html);
		$this->m_pdf->pdf->Output($filename, "F");
		$this->email->attach($filename);

		 //Send mail
         if($this->email->send())
		 {
			echo "<script> alert('Your bill will be emailed to registred email id.');location.href='http://grazzy.way2gps.com/index.php/admin/orders/RequestBill';</script>";
		//	echo "Your bill will be emailed to registred email id.";
         }
		 else
		 {
			// print_r($this->email->print_debugger());
			echo "Error in sending Email.";
         }
	}
	
	public function SalesChart(){

		if($this->input->post('action') == "Go"){
			$data['fromdate'] = $_SESSION['fromdate'] = date("Y-m-d",strtotime($this->input->post('fromdate')));
			 $data['todate'] = $_SESSION['todate'] = date("Y-m-d",strtotime($this->input->post('todate')));
			
		}elseif($this->input->post('action') == "Previous Month"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d',strtotime('first day of last month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of last month'));


		}elseif($this->input->post('action') == "Three Months"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d',strtotime('-3 months',strtotime('first day of this month')));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));


		}elseif($this->input->post('action') == "Six Months"){
			$data['fromdate'] = $_SESSION['fromdate'] = date('Y-m-d',strtotime('-6 months',strtotime('first day of this month')));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));


		}else{
			$data['fromdate'] =  $_SESSION['fromdate'] = date('Y-m-d',strtotime('first day of this month'));
			$data['todate'] =  $_SESSION['todate'] = date('Y-m-d',strtotime('last day of this month'));
			  // echo "select date(c.ordered_on) AS day ,SUM(a.cost) AS daily_total from order_items a,restaurant_menu b,orders c,restaurant d,admin e where c.restaurant_id=b.restaurant_id and c.id=a.order_id and b.menu_id=a.menu_id and c.restaurant_id=d.restaurant_id and d.restaurant_manager = '".$userdata['id']."'  and c.ordered_on >= '".$_SESSION['fromdate']."' and c.ordered_on <= '".$_SESSION['todate']."'  GROUP BY date(ordered_on)";exit;

			
		}

        $this->view($this->config->item('admin_folder').'/saleschart',$data);
       }  

     public function data() 
        { 
  
          echo json_encode($this->Order_model->getdata());

  
        } 
	
	public function renew(){
		$userdata = $this->session->userdata('admin');

		$sql = $this->db->query("update admin set RenewalAppliedStatus = 1 where id='".$userdata['id']."'");
		$message = "".$userdata['firstname']."(Email: ".$userdata['email'].") has requested for renewal)";
		$sql = $this->db->query("insert into notification_message (message) value('".$message."')");
		echo "<script>alert('Notification sent. admin will contact you soon');location.reload();</script>";
		 redirect($this->config->item('admin_folder').'/orders/dashboard');
	}

	function GetMenudetails1(){
		$data = $this->input->post('data');
		
		$menus = $this->Order_model->GetMenudetails1($data);
		
		$html="";
		if($menus != 0){
			if($data['ordertype_id'] == 3){
			 $customer_details = $this->Customer_model->get_customer($data['customer_id']);
			 $name = $customer_details->firstname." ".$customer_details->lastname;
			 $phone = $customer_details->phone;
			 $email = $customer_details->email;
			  $email = $customer_details->email;
			}else{
				
				$deliveryboy_details = $this->Customer_model->get_deliveryboy($data['delivered_by']);
				$name = isset($deliveryboy_details->name) ? $deliveryboy_details->name : "Not assigned yet";
				$phone = isset($deliveryboy_details->phone) ? $deliveryboy_details->phone : "";
				$email = isset($deliveryboy_details->email) ? $deliveryboy_details->email : "";
				
			}
			$html.="<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
					<h4 class='modal-title'>Order Number: ".$data['order_number']."</h4>
				  </div>
				  <div class='modal-body' class='form-horizontal'>
					<div class='form-group'>
						<label><strong>"; if($data['ordertype_id'] == 3){ $html.="Customer name";}else{$html.="Delivery boy";} $html.=":</strong>".$name."</label></br>
						<label><strong>Mobile No:</strong>".$phone."</label></br>
						
						

					</div>
					<table class='table table-bordered'>
					<thead>
						<tr><th>RestName</th><th>Order Type</th><th>Scheduled Pickup Time</th><th>Scheduled Delivery Time</th><tr>
					</thead>
					<tbody>";
			foreach($menus as $menu){
					$html.="<tr><td>".$menu->restaurant_name."</td><td>".$menu->order_type."</td><td>".$menu->keep_ready."</td><td>".$menu->delivered_on."</td></td>";

			}
			$html.="</tbody>
				</table>
				</div>
			  <div class='modal-footer'>
				<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
			  </div>";
		}
		echo $html;
	}


}
