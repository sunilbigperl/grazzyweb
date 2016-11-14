<?php

class Pitstop extends Admin_Controller { 
    
    function __construct()
    {       
        parent::__construct();
        
        $this->auth->check_access('Admin', true);
        $this->lang->load('category');
		$this->lang->load('product');
        $this->load->model('Pitstop_model');
    }
    
    function index()
    {       
		$data['page_title'] = lang('categories');
		$data['page_title']	= lang('products');
        $data['pitstops'] = $this->Pitstop_model->get_pitstops_tiered(true);
		
        $this->view($this->config->item('admin_folder').'/pitstops', $data);
    }
   
    function form($id = false)
    {
        
        $config['upload_path']      = 'uploads/images/full';
        $config['allowed_types']    = 'gif|jpg|png';
        $config['max_size']         = $this->config->item('size_limit');
        $config['max_width']        = '1024';
        $config['max_height']       = '768';
        $config['encrypt_name']     = true;
		$data['related_restaurants']	= array();
        $this->load->library('upload', $config);
        
        
        $this->pitstop_id  = $id;
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        
        $data['pitstops']     = $this->Pitstop_model->get_pitstops_tiered();
        $data['page_title']     = lang('category_form');
        
        //default values are empty if the customer is new
		
        $data['pitstop_id']             = '';
        $data['pitstop_name']           = '';

        $data['latitude']           = '';
        $data['langitude']      = '';
		
        $data['enabled']        = '';
       
        
        if ($id)
        {   
		
            $pitstop       = $this->Pitstop_model->get_pitstop($id);

            //if the category does not exist, redirect them to the category list with an error
            if (!$pitstop)
            {
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect($this->config->item('admin_folder').'/pitstops');
            }
            
            
			
            $data['pitstop_id']             = $pitstop->pitstop_id;
            $data['pitstop_name']           = $pitstop->pitstop_name;
            $data['latitude']           = $pitstop->latitude;
            $data['langitude']    = $pitstop->langitude;
            $data['enabled']        = $pitstop->enabled;
			if(!$this->input->post('submit'))
			{
				
				$data['related_restaurants']	= $pitstop->related_restaurants;
			}
			if(!is_array($data['related_restaurants']))
			{
				$data['related_restaurants']	= array();
			}
            
        }
        
        $this->form_validation->set_rules('pitstop_name', 'lang:pitstop_name', 'trim|required|max_length[64]');
        $this->form_validation->set_rules('latitude', 'lang:latitude', 'trim');
        $this->form_validation->set_rules('langitude', 'lang:langitude', 'trim');
        $this->form_validation->set_rules('enabled', 'lang:enabled', 'trim|numeric');
        
        if($this->input->post('submit'))
		{
			$data['related_restaurants']	= $this->input->post('related_restaurants');
		}
        // validate the form
        if ($this->form_validation->run() == FALSE)
        {
            $this->view($this->config->item('admin_folder').'/pitstop_form', $data);
        }
        else
        {
            
            
            $save['pitstop_id']             = $id;
			
            $save['pitstop_name']           = $this->input->post('pitstop_name');
            $save['latitude']    = $this->input->post('latitude');
            $save['langitude']        = $this->input->post('langitude');
           
            $save['enabled']        = $this->input->post('enabled');
           
			if($this->input->post('related_restaurants'))
			{
				$related_restaurants = $this->input->post('related_restaurants');
			}
			else
			{
				$related_restaurants = array();
			}
			
            $pitstop_id    = $this->Pitstop_model->save($save,$related_restaurants);
       
            $this->session->set_flashdata('message', lang('message_category_saved'));
            
            //go back to the category list
            redirect($this->config->item('admin_folder').'/pitstop');
        }
    }

    function delete($id)
    {
        
        $category   = $this->Pitstop_model->get_category($id);
        //if the category does not exist, redirect them to the customer list with an error
        if ($category)
        {
            $this->load->model('Routes_model');
            
            $this->Routes_model->delete($category->route_id);
            $this->Pitstop_model->delete($id);
            
            $this->session->set_flashdata('message', lang('message_delete_category'));
            redirect($this->config->item('admin_folder').'/pitstops');
        }
        else
        {
            $this->session->set_flashdata('error', lang('error_not_found'));
        }
    }
	
	function restaurants_autocomplete()
	{
		$name	= trim($this->input->post('name'));
		$limit	= $this->input->post('limit');
		
		if(empty($name))
		{
			echo json_encode(array());
		}
		else
		{
			$results	= $this->Pitstop_model->restaurants_autocomplete($name, $limit);
			
			$return		= array();
			
			foreach($results as $r)
			{
				$return[$r->restaurant_id]	= $r->restaurant_name;
			}
			echo json_encode($return);
		}
		
	}
}