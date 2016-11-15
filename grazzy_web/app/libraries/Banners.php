<?php
	
class Banners {
	
	var $CI;
	
	function __construct()
	{
		$this->CI =& get_instance();
		
		$this->CI->load->model('banner_model');
	}
	
	function show_collection($banner_collection_id, $quantity=5, $theme='default')
	{
		$data['id']			= $banner_collection_id;
		$data['banners']	= $this->CI->banner_model->banner_collection_banners($banner_collection_id, true, $quantity);
		$this->CI->load->view('banners/'.$theme, $data);
	}
	
	function show_NewProducts($quantity=5, $theme='default'){
		$data['banners']	= $this->CI->banner_model->banner_collection_NewProducts(true, $quantity);
		$data['Header'] = "New Products";
		$this->CI->load->view('banners/'.$theme, $data);
	}
	
	function show_PopularProducts($quantity=5, $theme='default'){
		$data['banners']	= $this->CI->banner_model->banner_collection_PopularProducts(true, $quantity);
		$data['Header'] = "PLANS RECOMMENDED BY PEPKRAFT";
		$this->CI->load->view('banners/'.$theme, $data);
	}
	
	function show_FilterProducts($theme='default',$category='11'){
		$data['banners']	= $this->CI->banner_model->banner_collection_FilterProducts($theme,$category);
		$categoryDetails 	= $this->CI->banner_model->GetCategoryDeatails($category);
		$data['category'] = $categoryDetails['category'];
		$data['subcategory'] = $categoryDetails['subcategory'];
		$data['company'] = $categoryDetails['company'];
		$data['category_id'] = $category;
		$this->CI->load->view('banners/'.$theme, $data);
	}
}