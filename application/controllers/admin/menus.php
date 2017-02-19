<?php

class Menus extends Admin_Controller {	
	
	private $use_inventory = false;
	
	function __construct()
	{		
		parent::__construct();
        
		//$this->auth->check_access('Admin', true);
		
		$this->load->model(array('Menu_model'));
		$this->load->model(array('Option_model'));
		$this->load->helper('form');
		$this->lang->load('product');
	}

	function index($res_id){
		$data['res_id'] = $res_id;
		$data['menus'] = $this->Menu_model->GetMenus($res_id);
		$this->view($this->config->item('admin_folder').'/menu', $data);
	}
	
	function form($menu_id=false,$res_id){
		$data['menuid']  = $menuid = isset($menu_id) ? $menu_id : 0;
		
		$config['upload_path']      = 'uploads/images/full';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = $this->config->item('size_limit');
        $config['max_width']        = '1024';
        $config['max_height']       = '768';
        $config['encrypt_name']     = true;
        $this->load->library('upload', $config);
        
        
      
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        $data['categories']     = $this->Category_model->get_categories_tiered();
		$data['restaurant_id'] = $res_id;
		$data['menu_id']         = '';
		$data['menu']		= '';
        $data['price']      = '';
        $data['enabled']     = '';
		$data['image']          = '';
		$data['type']          = '';
		$data['size']	= '';
		$data['itemPreparation_time'] = '';
		$data['code'] = '';
		$data['description'] = '';
		$data['photos']     = array();
		$data['product_categories']	= array();
		$data['customisation'] = "";
		
		if($menu_id){
			$menus       = $this->Menu_model->GetMenu($menu_id);
			$data['product_options']	= $this->Option_model->get_product_options($menu_id);
            //if the category does not exist, redirect them to the category list with an error
            if (!$menus)
            {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder').'/menus');
            } 
			$data['restaurant_id'] = $menus->restaurant_id;
			$data['menu']		= $menus->menu;
			$data['price']      = $menus->price;
			$data['enabled']       = $menus->enabled;
			$data['image']          = $menus->image;
			$data['type']          = $menus->type;
			$data['size']	= $menus->size;
			$data['code'] = $menus->code;
			$data['description'] = $menus->description;
			$data['itemPreparation_time'] = $menus->itemPreparation_time;
			$data['menus'] = $this->Menu_model->GetMenu($menu_id);
			$data['menu_id'] =$menu_id;
			$data['customisation'] = $menus->customisation;
			if(!$this->input->post('submit'))
			{
				
				$data['product_categories']	= array();
				foreach($menus->categories as $product_category)
				{
					$data['product_categories'][] = $product_category->id;
				}
				
			}
		}
		
		if(!is_array($data['product_categories']))
		{
			$data['product_categories']	= array();
		}
		
        $this->form_validation->set_rules('menu', 'lang:menu', 'trim|required');
		$this->form_validation->set_rules('type', 'lang:type', 'trim|required');
        $this->form_validation->set_rules('price', 'lang:price', 'trim');
		$this->form_validation->set_rules('image', 'lang:image', 'trim');
		 
		if($this->input->post('submit'))
		{
			$data['product_categories']	= $this->input->post('categories');
		}
		
		
		if ($this->form_validation->run() == FALSE)
        {
          
			$this->view($this->config->item('admin_folder').'/menu_form', $data);
        }
        else
        {
			$uploaded   = $this->upload->do_upload('image');
            
            if ($menu_id)
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
                    $this->view($this->config->item('admin_folder').'/menu_form', $data);
                    return; //end script here if there is an error
                }
            } else
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
			$save['restaurant_id'] = $res_id;
			$save['menu_id']         = $menu_id;
			$save['menu'] 		=  $this->input->post('menu');
			$save['type'] 		=  $this->input->post('type');
			$save['size']	= $this->input->post('size');
            $save['price']      = $this->input->post('price');
			$save['code']		 = $this->input->post('code');
			$save['description']		 = $this->input->post('description');
			$save['itemPreparation_time']		 = $this->input->post('itemPreparation_time');
            $save['enabled']     = $this->input->post('enabled');
			$save['customisation'] = serialize($this->input->post('option'));
			//print_r($save); exit;
			//save categories
			$categories			= $this->input->post('categories');
			if(!$categories)
			{
				$categories	= array();
			}
			
			
			$category_id    = $this->Menu_model->save($save,$categories);
			redirect($this->config->item('admin_folder').'/menus/index/'.$res_id);
		}
	}
	
	function delete($id,$res_id)
    {
        
        $menu   = $this->Menu_model->GetMenu($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($menu)
        {
            
            $this->Menu_model->delete($id,$res_id);
            
            $this->session->set_flashdata('message', lang('message_delete_category'));
            redirect($this->config->item('admin_folder').'/menus/index/'.$res_id);
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }
	
	function MenuStatusChange($menuid=false,$restid=false){
		$enabled = $this->input->post('enabled');
		$data['restaurant_id'] = false == $this->input->post('restid') ? $restid : $this->input->post('restid');
		$data['menu_id'] = false == $this->input->post('menuid') ? $menuid : $this->input->post('menuid');
		$data['enabled'] = isset($enabled) ? $enabled : 1;
		$data['deactivatefrom'] = date('Y-m-d',strtotime($this->input->post('FromDate')));
		$data['deactivateto'] = date('Y-m-d',strtotime($this->input->post('ToDate')));
		
		$this->Menu_model->MenuStatusChange($data);
		redirect('admin/menus/index/'.$data['restaurant_id'], 'refresh');
		
	}
	
	function ImportMenu($id)
	{
			$target_file =  basename($_FILES["menufile"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$uploadOk = 0;
			if($imageFileType == "csv"){
				$uploadOk = 1;
			}
			if ($uploadOk == 1) {
				
				if (move_uploaded_file($_FILES["menufile"]["tmp_name"], "uploads/" . basename($_FILES["menufile"]["name"]))) {
						$this->load->library('csvreader');
						$result =   $this->csvreader->parse_file("uploads/".$_FILES["menufile"]["name"]);//path to csv file

						$data['menus'] =  $result;
						$this->Menu_model->InsertMenus($data,$id);
						unlink("uploads/".$_FILES["menufile"]["name"]); 
						redirect('admin/menus/index/'.$id, 'refresh');
						
				}
			
			}
		
	}
	
}
