<?php

class Restaurant extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
        $this->load->model('Restaurant_model');
		$this->load->library('user_agent');
    }
    
    function index()
    {
		
        $data['restaurants'] = $this->Restaurant_model->get_restaurants();
		$name = isset($data['restaurants'][0]->restaurant_name) ? $data['restaurants'][0]->restaurant_name : '';
		$data['page_title'] = $name;
        $data['page_title1'] = 'Restaurants';
        $this->view($this->config->item('admin_folder').'/restaurant', $data);
		
    }
    
    
    function form($id = false)
    {
	
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
        
		$data['managers'] = $this->Restaurant_model->get_managers();
        $data['restaurants']     = $this->Restaurant_model->get_restaurants();
        $data['page_title']     = "Restaurant form";
        
        //default values are empty if the customer is new
		
        $data['restaurant_id']             = '';
        $data['restaurant_name']           = '';
        $data['restaurant_address']           = '';
        $data['restaurant_phone']    		= '';
        $data['restaurant_email']       	 = '';
        $data['image']       	 = 				'';
        $data['restaurant_latitude']          = '';
        $data['restaurant_langitude']      = '';
        $data['restaurant_branch']           = '';
		$data['restaurant_manager']           = '';
        $data['enabled']        = '';
		$data['preparation_time'] ="";
		$data['servicetax'] = '';
		$data['commission'] = '';
		$data['penalty'] ='';
        $data['fromtime'] ='';
		$data['totime'] ='';
		$data['days'] ='';
        //create the photos array for later use
        $data['photos']     = array();
        
        if ($id)
        {   
		
            $restaurant       = $this->Restaurant_model->get_restaurant($id);

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
            $data['restaurant_email']        = $restaurant->restaurant_email;
            $data['restaurant_latitude']       = $restaurant->restaurant_latitude;
            $data['restaurant_langitude']      = $restaurant->restaurant_langitude;
            $data['image']          = $restaurant->image;
            $data['restaurant_branch']      = $restaurant->restaurant_branch;
            $data['restaurant_manager']           = $restaurant->restaurant_manager;
			$data['preparation_time'] = $restaurant->preparation_time;
            $data['enabled']        = $restaurant->enabled;
			$data['servicetax'] = $restaurant->servicetax;
			$data['commission'] = $restaurant->commission;
			$data['penalty']	= $restaurant->penalty;
			$data['fromtime']	= $restaurant->fromtime;
			$data['totime']	= $restaurant->totime;
			$data['days']	= $restaurant->days;
			
			if(!$this->input->post('submit'))
			{
				$data['related_pitstops']	= $restaurant->related_pitstops;
			}
			if(!is_array($data['related_pitstops']))
			{
				$data['related_pitstops']	= array();
			}
            
        }
        
        $this->form_validation->set_rules('restaurant_name', 'lang:restaurant_name', 'trim|required|max_length[64]');
        
        $this->form_validation->set_rules('restaurant_address', 'lang:restaurant_address', 'trim');
       
        $this->form_validation->set_rules('image', 'lang:restaurant_image', 'trim');
       
        $this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
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
                    $data['error']  .= $this->upload->display_errors();
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
            
            $save['restaurant_id']         = $id;
            $save['restaurant_name']       = $this->input->post('restaurant_name');
            $save['restaurant_address']    = $this->input->post('restaurant_address');
            $save['restaurant_phone']      = $this->input->post('restaurant_phone');
            $save['restaurant_email']      = $this->input->post('restaurant_email');
            $save['restaurant_latitude']   = $this->input->post('restaurant_latitude');
            $save['restaurant_langitude']  = $this->input->post('restaurant_langitude');
            $save['restaurant_branch']     = $this->input->post('restaurant_branch');
			$save['restaurant_manager']    = $this->input->post('restaurant_manager');
			$save['servicetax']			   = $this->input->post('servicetax');
			$save['commission'] 		   = $this->input->post('commission');
			$save['penalty'] 			   = $this->input->post('penalty');
            $save['enabled']      		   = $this->input->post('enabled');
			$save['preparation_time']	   = $this->input->post('preparation_time');
			$save['fromtime'] 			   = $this->input->post('fromtime');
			$save['totime'] 			   = $this->input->post('totime');
			$save['days'] 				   = serialize($this->input->post('days'));
			
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
}