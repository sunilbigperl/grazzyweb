<?php

class Restaurant extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
		$this->lang->load('admin');
        $this->load->model('Restaurant_model');
		$this->load->library('user_agent');
    }
    
    function index()
    {
		$data['page_title1'] = 'Restaurants';
        $data['restaurants'] = $this->Restaurant_model->get_restaurants();
        $name = isset($data['restaurants'][0]->restaurant_name) ? $data['restaurants'][0]->restaurant_name : '';
		$data['page_title'] = $name;
        $this->view($this->config->item('admin_folder').'/restaurant', $data);
		
    }
    
    
    function form($id = false)
    {
    	$date = date("Y-m-d");
        $config['upload_path']      = 'uploads/images/full';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = $this->config->item('size_limit');
        $config['max_width']        = '1024';
        $config['max_height']       = '768';
        $config['encrypt_name']     = true;
		$data['related_pitstops']	= array();
        $this->load->library('upload', $config);
        
        
        $this->category_id  = $id;
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        // $data['getcity'] = $this->Restaurant_model->getrestaurant();
        $data['getcity'] = $this->Restaurant_model->get_class();
        $data['delpartner'] = $this->Restaurant_model->get_deliverypartner();
		$data['managers'] = $this->Restaurant_model->get_managers();
        $data['restaurants']     = $this->Restaurant_model->get_restaurants();
        $data['page_title']     = "Restaurant form";
        
        //default values are empty if the customer is new
		
        $data['restaurant_id']             = '';
        $data['restaurant_name']           = '';
        $data['restaurant_address']           = '';
        $data['restaurant_phone']    		= '';
		$data['restaurant_mobile']    		= '';
		$data['restaurantmanager_mobile']   = '';
        $data['restaurant_email']       	 = '';
        $data['image']       	 = 				'';
        $data['restaurant_latitude']          = '';
        $data['restaurant_langitude']      = '';
        $data['restaurant_branch']           = '';
       // $data['del_partner']           = '';
		$data['restaurant_manager']           = '';
        $data['enabled']        = '';
        $data['gst'] = '';
		$data['preparation_time'] ="";
		$data['servicetax'] = '';
		$data['commission'] = '';
		$data['penalty'] ='';
		$data['Reimb'] ='';
		$data['discount1'] ='';
		$data['discount2'] ='';
        $data['fromtime'] ='';
		$data['totime'] ='';
		$data['comment']= '';
		$data['days'] ='';
		$data['delivery_charge'] = '';
		$data['username']	= '';
		$data['firstname']	= '';
		$data['access']	 = '';
		$data['NextRenewalDate'] = '';
		$data['tags'] = '';
		
        //create the photos array for later use
        $data['photos']     = array();
        
        if ($id)
        {   
		
            $restaurant       = $this->Restaurant_model->get_restaurant($id);
			$this->admin_id		= $restaurant->restaurant_manager;
			$admin			= $this->auth->get_admin($restaurant->restaurant_manager);
            //if the category does not exist, redirect them to the category list with an error
            if (!$restaurant)
            {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder').'/restaurant');
            }
            
         
            //set values to db values
			
            $data['restaurant_id']             = $restaurant->restaurant_id;
            $data['restaurant_name']           = $restaurant->restaurant_name;
            $data['restaurant_address']           = $restaurant->restaurant_address;
            $data['restaurant_phone']   	 = $restaurant->restaurant_phone;
			$data['restaurant_mobile']   	 = $restaurant->restaurant_mobile;
			$data['restaurantmanager_mobile']   = $restaurant->restaurantmanager_mobile;
            $data['restaurant_email']        = $restaurant->restaurant_email;
            $data['restaurant_latitude']       = $restaurant->restaurant_latitude;
            $data['restaurant_langitude']      = $restaurant->restaurant_langitude;
            $data['image']          = $restaurant->image;
            $data['restaurant_branch']      = $restaurant->restaurant_branch;
           // $data['del_partner']           = $restaurant->del_partner;
            $data['restaurant_manager']           = $restaurant->restaurant_manager;
			$data['preparation_time'] = $restaurant->preparation_time;
            $data['enabled']        = $restaurant->enabled;
            $data['gst'] = $restaurant->GST;
			$data['servicetax'] = $restaurant->servicetax;
			$data['commission'] = $restaurant->commission;
			$data['penalty']	= $restaurant->penalty;
			$data['Reimb']	= $restaurant->reimb;
			$data['discount1']	= $restaurant->discount1;
			$data['discount2']	= $restaurant->discount2;
			$data['fromtime']	= $restaurant->fromtime;
			$data['totime']	= $restaurant->totime;
			$data['comment']= $restaurant->comment;
			$data['days']	= $restaurant->days;
			$data['delivery_charge'] = $restaurant->delivery_charge;
			$data['username']	= $admin->username;
			$data['firstname']	= $admin->firstname;
			$data['access']		= $admin->access;
			$data['NextRenewalDate'] = $admin->NextRenewalDate;
			$data['tags'] = $restaurant->tags;
			
			if(!$this->input->post('submit'))
			{
				$data['related_pitstops']	= $restaurant->related_pitstops;
			}
			if(!is_array($data['related_pitstops']))
			{
				$data['related_pitstops']	= array();
			}
            
        }
        
		
		
		//$this->form_validation->set_rules('username', 'lang:username', 'trim|required|max_length[128]|callback_check_username');
        $this->form_validation->set_rules('restaurant_name', 'lang:restaurant_name', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('restaurant_address', 'lang:restaurant_address', 'trim|required');
        $this->form_validation->set_rules('restaurant_mobile', 'lang:restaurant_mobile', 'trim|required|max_length[11]|callback_validate_phone_number');
        $this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
        // $this->form_validation->set_rules('gst','GST','required|alpha_numaric|max_length[15]');
		
		//if this is a new account require a password, or if they have entered either a password or a password confirmation
		if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id)
		{
			$this->form_validation->set_rules('password', 'lang:password', 'required|min_length[6]|sha1');
			$this->form_validation->set_rules('confirm', 'lang:confirm_password', 'required|matches[password]|sha1');
		}
		
        if($this->input->post('submit'))
		{
			$data['related_pitstops']	= $this->input->post('related_pitstops');
		}
        
        // validate the form
        if ($this->form_validation->run() == FALSE)
        {
            $this->view($this->config->item('admin_folder').'/restaurant_form', $data);
        }
        else
        {
            
            
            $uploaded   = $this->upload->do_upload('image');
            
            if ($id)
            {
                //delete the original file if another is uploaded
                if($uploaded)
                {
                    
                    if($data['image'] != '')
                    {
                        $file = array();
                        $file[] = 'uploads/images/full/'.$data['image'];
                        $file[] = 'uploads/images/medium/'.$data['image'];
                        $file[] = 'uploads/images/small/'.$data['image'];
                        $file[] = 'uploads/images/thumbnails/'.$data['image'];
                        
                        foreach($file as $f)
                        {
                            //delete the existing file if needed
                            if(file_exists($f))
                            {
                                unlink($f);
                            }
                        }
                    }
                }
                
            }
            
            if(!$uploaded)
            {
                $data['error']  = $this->upload->display_errors();
                if($_FILES['image']['error'] != 4)
                {
                    //$data['error']  .= $this->upload->display_errors();
                    $this->view($this->config->item('admin_folder').'/restaurant_form', $data);
                    return; //end script here if there is an error
                }
            }
            else
            {
                $image          = $this->upload->data();
                $save['image']  = $image['file_name'];
                
                $this->load->library('image_lib');
                
                //this is the larger image
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/full/'.$save['image'];
                $config['new_image']    = 'uploads/images/medium/'.$save['image'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 600;
                $config['height'] = 500;
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();

                //small image
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/medium/'.$save['image'];
                $config['new_image']    = 'uploads/images/small/'.$save['image'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 300;
                $config['height'] = 300;
                $this->image_lib->initialize($config); 
                $this->image_lib->resize();
                $this->image_lib->clear();

                //cropped thumbnail
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/images/small/'.$save['image'];
                $config['new_image']    = 'uploads/images/thumbnails/'.$save['image'];
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 30;
                $config['height'] = 30;
                $this->image_lib->initialize($config);  
                $this->image_lib->resize(); 
                $this->image_lib->clear();
            }
            $save1['username']	= $this->input->post('username');
			$save1['firstname']	=$this->input->post('firstname');
			$save1['access']		= 'Restaurant manager';
			$save1['id'] = $this->admin_id;
			$NextRenewalDate = $this->input->post('NextRenewalDate');
			$save1['NextRenewalDate'] = isset($NextRenewalDate) ? $NextRenewalDate : date('Y-m-d');
			if ($this->input->post('password') != '' || !$this->admin_id)
			{
				$save1['password']	= $this->input->post('password');
			}
			
			$restid = $this->auth->save($save1);
			
            $save['restaurant_id']         = $id;
            $save['restaurant_name']       = $this->input->post('restaurant_name');
            $save['restaurant_address']    = $this->input->post('restaurant_address');
            $save['restaurant_phone']      = $this->input->post('restaurant_phone');
			$save['restaurant_mobile']      = $this->input->post('restaurant_mobile');
			$save['restaurantmanager_mobile'] = $this->input->post('restaurantmanager_mobile');
            $save['restaurant_email']      = $this->input->post('restaurant_email');
            $save['restaurant_latitude']   = $this->input->post('restaurant_latitude');
            $save['restaurant_langitude']  = $this->input->post('restaurant_langitude');
            $save['restaurant_branch']     = $this->input->post('restaurant_branch');
            //$save['del_partner']           = $this->input->post('del_partner');
			$save['restaurant_manager']    = $restid;
			$save['servicetax']			   = $this->input->post('servicetax');
			$save['commission'] 		   = $this->input->post('commission');
			$save['penalty'] 			   = $this->input->post('penalty');
			$save['Reimb'] 			   = $this->input->post('Reimb');
			$save['discount1'] 			   = $this->input->post('discount1');
			$save['discount2'] 			   = $this->input->post('discount2');
            $save['enabled']      		   = $this->input->post('enabled');
            $save['gst']			   = $this->input->post('gst');
			$save['preparation_time']	   = $this->input->post('preparation_time');
			$save['fromtime'] 			   = $this->input->post('fromtime');
			$save['totime'] 			   = $this->input->post('totime');
			$save['comment'] 			   = $this->input->post('comment');
			$save['days'] 				   = serialize($this->input->post('days'));
			$save['delivery_charge'] = $this->input->post('delivery_charge');
			$save['tags'] = $this->input->post('tags');
			$save['createdAt '] = $date;
			if($this->input->post('related_pitstops'))
			{
				$related_pitstops = $this->input->post('related_pitstops');
			}
			else
			{
				$related_pitstops = array();
			}
		
			
            $restaurant_id    = $this->Restaurant_model->save($save,$related_pitstops);
            
            $this->session->set_flashdata('message', "Restaurant saved");
            
            //go back to the category list
            redirect($this->config->item('admin_folder').'/restaurant');
        }
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
	
	function RestaurantStatusChange($id=false,$enabled=0){
		$enabl = $this->input->post('enabled');
		
		$enabled = isset($enabled) ? $enabled : 0;
		$data['restaurant_id'] = false == $this->input->post('id') ? $id : $this->input->post('id');
		
		$data['enabled'] = isset($enabl) ? $enabl : $enabled;
		
		$data['deactivatefrom'] = date('Y-m-d',strtotime($this->input->post('FromDate')));
		$data['deactivateto'] = date('Y-m-d',strtotime($this->input->post('ToDate')));
		
		$this->Restaurant_model->RestaurantStatusChange($data);
		redirect('admin/restaurant', 'refresh');
		
	}
	
	function ImportRestaurants()
	{
			$target_file =  basename($_FILES["restaurantfile"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$uploadOk = 0;
			if($imageFileType == "csv"){
				$uploadOk = 1;
			}
			if ($uploadOk == 1) {
				
				if (move_uploaded_file($_FILES["restaurantfile"]["tmp_name"], "uploads/" . basename($_FILES["restaurantfile"]["name"]))) {
						$this->load->library('csvreader');
						$result =   $this->csvreader->parse_file("uploads/".$_FILES["restaurantfile"]["name"]);//path to csv file
						
						$data['restaurants'] =  $result;
						$this->Restaurant_model->InsertRestaurants($data);
						unlink("uploads/".$_FILES["restaurantfile"]["name"]); 
						redirect('admin/restaurant/index', 'refresh');
						
				}
			
			}
		
	}
	
	function check_username($str)
	{
		$email = $this->auth->check_username($str, $this->admin_id);
		if ($email)
		{
			$this->form_validation->set_message('check_username', lang('error_username_taken'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function validate_phone_number($value) {
		
		$value = trim($value);
		$match = '/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/';
		$replace = '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/';
		$return = '($1) $2-$3';
		if (preg_match($match, $value)) {
			return true;
		} else {
			$this->form_validation->set_message('validate_phone_number', 'Invalid Monile no.');
			return false;
		}
	}

	function check_restaurantname($str)
	{
		$name = $this->Restaurant_model->check_restaurantname($str);
		if ($name)
		{
			$this->form_validation->set_message('check_restaurantname', 'This Restaurantname is already in use');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	// public function getrestaurantlist()
	// {
	// 	$this->load->library('excel');
 //    //Create a new Object
 //    $objPHPExcel = new PHPExcel();
 //    // Set the active Excel worksheet to sheet 0
 //    $objPHPExcel->setActiveSheetIndex(0); 

 //    $heading=array('Restaurant name','Restaurant address','Restaurant phone','Restaurant mobile','Restaurant Manager Mobile No','Restaurant email','City','Enabled','GSTIN','From time','To time','Image','Restaurant latitude','Restaurant longitude','Restaurant manager name','Manager user name','Next Renewal Date','Commission(%)','Penalty(Rs)','Reimbursement of delivery charges(Rs)','Cutoff Preparation time(In mins)','Discount1','Discount2','Comment'); //set title in excel sheet
 //    $rowNumberH = 1; //set in which row title is to be printed
 //    $colH = 'A'; //set in which column title is to be printed
    
 //    $objPHPExcel->getActiveSheet()->getStyle($rowNumberH)->getFont()->setBold(true);
    
	// for($col = ord('A'); $col <= ord('N'); $col++){ //set column dimension 
	// 	 $objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
 //         $objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
	// }
 //    foreach($heading as $h){ 

 //        $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
 //        $colH++;    
 //    }


			
		
 //    $export_excel = $this->db->query("select a.*,b.firstname,b.username,b.password,b.NextRenewalDate from restaurant a,admin b where a.restaurant_manager=b.id ")->result_array();





 //    $rowCount = 2; // set the starting row from which the data should be printed
 //    foreach($export_excel as $excel)
 //    {  


 //     //$days = unserialize($excel['days']);

     

 //     // $days1 = Array (1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday', 7 => 'sunday' );
	//  //$days1[]=$days;
    
 
	

 //        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $excel['restaurant_name']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $excel['restaurant_address']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $excel['restaurant_phone']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $excel['restaurant_mobile']);
 //         $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $excel['restaurantmanager_mobile']);
 //         $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $excel['restaurant_email']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$excel['restaurant_branch']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$excel['enabled']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$excel['GST']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount,$excel['fromtime']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount,$excel['totime']);
 //        // $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount,unserialize($excel['days'])); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $excel['image']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $excel['restaurant_latitude']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount,$excel['restaurant_langitude']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount,$excel['firstname']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount,$excel['username']);
 //        // $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$excel['password']); 
 //        $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount,$excel['NextRenewalDate']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('R'.$rowCount,$excel['commission']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('S'.$rowCount,$excel['penalty']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('T'.$rowCount,$excel['reimb']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('U'.$rowCount,$excel['preparation_time']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('V'.$rowCount,$excel['discount1']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount,$excel['discount2']);
 //        $objPHPExcel->getActiveSheet()->SetCellValue('X'.$rowCount,$excel['comment']); 
 //        // $objPHPExcel->getActiveSheet()->SetCellValue('W'.$rowCount,$data); 
        
        
        
        
         
        
       
          
 //        $rowCount++; 
 //    } 

 //    // Instantiate a Writer 
 //    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');

 //    header('Content-Type: application/vnd.ms-excel');
 //    header('Content-Disposition: attachment;filename="restaurant.csv"');
 //    header('Cache-Control: max-age=0');

 //    $objWriter->save('php://output');
 //    //exit();

	// //$this->load->view($this->config->item('admin_folder').'/customer_subscriber_list',$data,true);
	// }


	public function getrestaurantlist(){ 
	 $delimiter = ",";
   // file name 
   $filename = 'restaurant.csv'; 
   header("Content-Description: File Transfer"); 
   header("Content-Disposition: attachment; filename=$filename"); 
   header("Content-Type: application/csv; ");
   
   // get data 
   $usersData = $this->Restaurant_model->getrestaurantlist();

   // file creation 
   $file = fopen('php://output', 'w');
 
   $header = array('Restaurant name','Restaurant address','Restaurant phone','Restaurant mobile','Restaurant Manager Mobile No','Restaurant email','City','Enabled','GSTIN','From time','To time','Image','Restaurant latitude','Restaurant longitude','Restaurant manager name','Manager user name','Next Renewal Date','Commission(%)','Penalty(Rs)','Reimbursement of delivery charges(Rs)','Cutoff Preparation time(In mins)','Discount1','Discount2','Comments'); 
   fputcsv($file, $header);
   foreach ($usersData as $key=>$line){ 
   	 $lineData = array($line['restaurant_name'], $line['restaurant_address'], $line['restaurant_phone'], $line['restaurant_mobile'], $line['restaurantmanager_mobile'],$line['restaurant_email'],$line['restaurant_branch'],$line['enabled'],$line['GST'],$line['fromtime'],$line['totime'],$line['image'],$line['restaurant_latitude'],$line['restaurant_langitude'],$line['firstname'],$line['username'],$line['NextRenewalDate'],$line['commission'],$line['penalty'],$line['reimb'],$line['preparation_time'],$line['discount1'],$line['discount2'],$line['comment']);
     //fputcsv($file,$line); 
     fputcsv($file, $lineData,$delimiter);
   }
   fclose($file); 
   exit; 
  }


	

	public function getmenulist($res_id)
	{
		//print_r($res_id);exit;
		$this->load->library('excel');
    //Create a new Object
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0); 

    $heading=array('Code','Menu','Description','Price','Type','itemPreparation_time','Enabled','Type','Name','Name','Weight','Price'); //set title in excel sheet
    $rowNumberH = 1; //set in which row title is to be printed
    $colH = 'A'; //set in which column title is to be printed
    
    $objPHPExcel->getActiveSheet()->getStyle($rowNumberH)->getFont()->setBold(true);
    
	for($col = ord('A'); $col <= ord('N'); $col++){ //set column dimension 
		 $objPHPExcel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         $objPHPExcel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
	}
    foreach($heading as $h){ 

        $objPHPExcel->getActiveSheet()->setCellValue($colH.$rowNumberH,$h);
        $colH++;    
    }


			
		
    $export_excel = $this->db->query("select * from restaurant_menu where restaurant_id=".$res_id." and `delete` = 0 order by menu_id DESC")->result_array();
   



// print_r( $export_excel);exit;

    $rowCount = 2; // set the starting row from which the data should be printed
    foreach($export_excel as $excel)
    {  

    	
    	if($excel['customisation'] != "" && strlen($excel['customisation']) > 5 ){
								$cust = unserialize($excel['customisation']);

								$data= array();
								$l=0;
								foreach($cust as $str){
									$data[$l]['type'] = $str['type'];
									$data[$l]['name'] = $str['name'];
									if(isset($str['values']) && count($str['values']) > 0){
										$m=0;

										foreach($str['values'] as $value){
											
											
											$data[$l]['values'][$m]['name'] = $value['name'];
											$data[$l]['values'][$m]['weight'] = $value['weight'];
											$data[$l]['values'][$m]['price'] = $value['price'];
										$m++;
        
         $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$excel['code']);
         $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$excel['menu']);
         $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$excel['description']);
         $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$excel['price']);
         $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$excel['type']);
         $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$excel['itemPreparation_time']);
         $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$excel['enabled']); 
         $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$str['type']); 
         $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $str['name']); 
         $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $value['name']); 
         $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $value['weight']);
         $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $value['price']);
         $rowCount++;
										}	

									}
								$l++;

								}
							}else{
								$data= false;
							}


 
	
         // $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,$excel['restaurant_id']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,$excel['code']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,$excel['menu']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,$excel['description']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,$excel['price']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,$excel['type']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,$excel['itemPreparation_time']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,$excel['enabled']); 
         // $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount,$str['type']); 
         // $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $str['name']); 
         // $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $value['name']); 
         // $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $value['weight']);
         // $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $value['price']);
         
        
        
        
        
         
        
       // }
          
        // $rowCount++; 

    } 

    // Instantiate a Writer 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="menu.csv"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
    //exit();

	

	//$this->load->view($this->config->item('admin_folder').'/customer_subscriber_list',true);
	}


}