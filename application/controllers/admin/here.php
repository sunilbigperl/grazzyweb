<?php

class Here extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        //$this->auth->check_access('Admin', true);
        $this->lang->load('category');
		$this->lang->load('product');
        $this->load->model('Here_model');
        $this->load->model('Pitstop_model');
    }

    function index()
    {       
		$data['page_title'] = 'here';
        $data['pitstops'] = $this->Here_model->get_here_tiered(true);
		
        $this->view($this->config->item('admin_folder').'/here', $data);
    }

    function form($id = false)
    {
        
        $config['upload_path']      = 'uploads/images/full';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = $this->config->item('size_limit');
        $config['max_width']        = '1024';
        $config['max_height']       = '768';
        $config['encrypt_name']     = true;
		$this->load->library('upload', $config);
        
        
        $this->id  = $id;
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $data['getpitstop'] = $this->Here_model->get_class();
        //$data['pitstops']     = $this->Pitstop_model->get_pitstops_tiered();
        $data['page_title']     = lang('category_form');
        
        //default values are empty if the customer is new
		
        $data['id']             = '';
        $data['name']           = '';

        $data['coordinates']           = '';
        // $data['langitude']      = '';
		// $data['address']      = '';
		$data['city']      = '';
        $data['enabled']        = '';
       
        
        if ($id)
        {   
            //$pitstop1 = $this->Pitstop_model->getpitstop($id);
		
            $pitstop       = $this->Here_model->get_here($id);
            //print_r($pitstop);exit;         

            //if the category does not exist, redirect them to the category list with an error
            if (!$pitstop)
            {
                //$this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder').'/here');
            }
            
            
			
            $data['id']             = $pitstop->id;
            $data['name']           = $pitstop->name;
            $data['coordinates']           = $pitstop->coordinates;
            // $data['langitude']    = $pitstop->langitude;
			// $data['address']    = $pitstop->address;
			$data['city']    = $pitstop->city;
            //print_r($data['city']);exit;
            $data['enabled']        = $pitstop->enabled;
			// if(!$this->input->post('submit'))
			// {
			// 	$data['related_restaurants']	= $pitstop->related_restaurants;
			// }
			// if(!is_array($data['related_restaurants']))
			// {
			// 	$data['related_restaurants']	= array();
			// }
            
        }
        
        $this->form_validation->set_rules('name', 'lang:name', 'trim|required|max_length[64]');
        // $this->form_validation->set_rules('latitude', 'lang:latitude', 'trim');
        // $this->form_validation->set_rules('langitude', 'lang:langitude', 'trim');
        $this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
        
  //       if($this->input->post('submit'))
		// {
		// 	$data['related_restaurants']	= $this->input->post('related_restaurants');
		// }
        // validate the form
        if ($this->form_validation->run() == FALSE)
        {
            $this->view($this->config->item('admin_folder').'/here_form', $data);
        }
        else
        {
            
            
            $save['id']             = $id;
			
            $save['name']           = $this->input->post('name');
            $save['coordinates']    = 	$this->input->post('coordinates');
            // $save['langitude']        = $this->input->post('langitude');
            // $save['address']        = $this->input->post('address');
		    $save['city']        = $this->input->post('city');
            $save['enabled']        = $this->input->post('enabled');
		
			
			
            $pitstop_id    = $this->Here_model->save($save);
       
            $this->session->set_flashdata('message', 'Here location saved');
            
            //go back to the category list
            redirect($this->config->item('admin_folder').'/here');
        }
    }

    function Importhere()
	{
			$target_file =  basename($_FILES["pitstopfile"]["name"]);
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			$uploadOk = 0;
			if($imageFileType == "csv"){
				$uploadOk = 1;
			}
			if ($uploadOk == 1) {
				
				if (move_uploaded_file($_FILES["pitstopfile"]["tmp_name"], "uploads/" . basename($_FILES["pitstopfile"]["name"]))) {
						$this->load->library('csvreader');
						$result =   $this->csvreader->parse_file("uploads/".$_FILES["pitstopfile"]["name"]);//path to csv file
						
						$data['here'] =  $result;
						$this->Here_model->Inserthere($data);
						unlink("uploads/".$_FILES["pitstopfile"]["name"]); 
						redirect('admin/here/index', 'refresh');
						
				}
			
			}
		
	}

	function delete($id)
    {
        
        $pitstop   = $this->Here_model->get_here($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($pitstop)
        {
            $this->Here_model->delete($id);
            
            $this->session->set_flashdata('message', "The Here location has been deleted.");
            redirect($this->config->item('admin_folder').'/here');
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }

    public function ChangeStatus($id=false,$status=false){
		$enabled = $this->input->post('enabled');
		$data['id'] = false == $this->input->post('pitid') ? $id : $this->input->post('pitid');
		$data['enabled'] = isset($enabled) ? $enabled : 1;
		$data['deactivatefrom'] = date('Y-m-d',strtotime($this->input->post('FromDate')));
		$data['deactivateto'] = date('Y-m-d',strtotime($this->input->post('ToDate')));
		$result = $this->Here_model->ChangeStatus($data);
		if($result){
			redirect("admin/here");
		}
	}

	function Deleteall(){
		// foreach($_POST['DeleteOptions'] as $key=>$DeleteOption){
			$sql = $this->db->query('delete from here');
			//$sql = $this->db->query('update pitstops set delete=1 where pitstop_id="'.$key.'"');
		//}
		redirect("admin/here");
	}

   }