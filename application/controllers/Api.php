<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->database();
		$this->load->model('api_model');
        
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
	
	public function customercheck_post(){
		 $data = [
            'firstname' => $this->post('firstname'),
            'phone' => $this->post('phone')
        ];

		$message = $this->api_model->customercheck($data);
		
        $this->set_response($message, REST_Controller::HTTP_OK);
	}
	
    public function customer_get($id)
    {
		$id = isset($id) ? $id : "";
		$users = $this->api_model->getUsers();
	

        if ($id === NULL)
        {
            // Check if the users data store contains users (in case the database result returns NULL)
            if ($users)
            {
                // Set the response and exit
                $this->response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No users were found'
                ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }

        // Find and return a single record for a particular user.

        $id = (int) $id;

        // Validate the id.
        if ($id <= 0)
        {
            // Invalid id, set the response and exit.
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // Get the user from the array, using the id as key for retreival.
        // Usually a model is to be used for this.

        $user = NULL;

        if (!empty($users))
        {
            foreach ($users as $key => $value)
            {
                if (isset($value['id']) && $value['id'] == $id)
                {
                    $user = $value;
					
                }
            }
        }

        if (!empty($user))
        {
            $this->set_response($user, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User could not be found'
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }
	
	 public function restaurants_get($id)
    {
		
		$restaurants = $this->api_model->getRestaurants($id);
		if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($restaurants))
        {
            $this->set_response($restaurants, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'restaurants  could not be found for the pitstop'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
		
        
    }
	
	 public function customeraddress_get($id)
    { 
      
		$address = $this->api_model->getAddress($id);
		
        if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($address))
        {
            $this->set_response($address, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'User address could not be found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
	
	public function menus_get($id){
		$menus =  $this->api_model->getMenus($id);
		
		if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($menus))
        {
            $this->set_response($menus, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'menus  could not be found for the restaurant'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}
	
    public function adduserslocation_post()
    {
		$data = [
            'customer_id' => $this->post('customer_id'),
            'latitude' => $this->post('latitude'),
            'langitude' => $this->post('langitude'),
        ];
       $userslocation =  $this->api_model->adduserslocation($data);
        

        $this->set_response($userslocation, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
	
	public function updateprofile_post()
    {
		$d = explode("/",$this->post('dob'));
		$dob = $d[2]."-".$d[1]."-".$d[0];
		
		$data = [
			'id' => $this->post('id'),
            'firstname' => $this->post('firstname'),
            'email' => $this->post('email'),
            'dob' => $dob,
			'gender' => $this->post('gender'),
        ];
        $updateprofile =  $this->api_model->updateprofile($data);
        
		if($updateprofile){
			$status = array('status'=>true);
		}
        $this->set_response($status, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
	
	
	public function orderlist_get($id){
		$orderlist =  $this->api_model->orderlist($id);
		
		if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($orderlist))
        {
            $this->set_response($orderlist, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'order list  could not be found for the customer'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}
	
	public function orderlistnotshipped_get($id){
		$orderlist =  $this->api_model->orderlistnotshipped($id);
		
		if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($orderlist))
        {
            $this->set_response($orderlist, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'order list  could not be found for the customer'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}
	
	public function deliveryboylocation_get($id){
		$deliveryboylocation =  $this->api_model->deliveryboylocation($id);
		
		if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($deliveryboylocation))
        {
            $this->set_response($deliveryboylocation, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'order list  could not be found for the customer'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}
	
	public function pitstopsuser_post(){
		$data = [
			'southwest_lat' => $this->post('southwest_lat'),
            'southwest_lng' => $this->post('southwest_lng'),
            'northeast_lat' => $this->post('northeast_lat'),
			'northeast_lng' => $this->post('northeast_lng'),
        ];
		$pitstopsuser =  $this->api_model->pitstopsuser($data);
		if (!empty($pitstopsuser))
        {
            $this->set_response($pitstopsuser, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'pitstop list  could not be found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

	}
	
	public function restaurantNearbyUser_post(){
		$data = [
			'latitude' => $this->post('latitude'),
            'langitude' => $this->post('langitude'),
        ];
		$restaurantuser =  $this->api_model->restaurantuser($data);
		if (!empty($restaurantuser))
        {
            $this->set_response($restaurantuser, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'restaurant list  could not be found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

	}
	
    public function users_delete()
    {
        $id = (int) $this->get('id');

        // Validate the id.
        if ($id <= 0)
        {
            // Set the response and exit
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        // $this->some_model->delete_something($id);
        $message = [
            'id' => $id,
            'message' => 'Deleted the resource'
        ];

        $this->set_response($message, REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
    }
	
	public function restaurantforloctaion_post()
    {
		$data=array('restaurant_latitude'=>$this->input->post('latitude'),'restaurant_langitude'=>$this->input->post('langitude'));
		$restaurants = $this->api_model->restaurantforloctaion($data);
		if (!empty($restaurants))
        {
            $this->set_response($restaurants, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'restaurants  could not be found for the location'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
	}
	
	public function saveAddress_post(){
		$data = json_decode(file_get_contents("php://input"),true);
		
		/* if($this->input->post('id') != ""){ $id = $this->input->post('id'); }else{ $id ='';}
		if($this->input->post('address1') != ""){ $address1 = $this->input->post('address1'); }else{ $address1 ='';}
		if($this->input->post('address2') != ""){ $address2 = $this->input->post('address2'); }else{ $address2 ='';}
		if($this->input->post('city_state') != ""){ $city_state = $this->input->post('city_state'); }else{ $city_state ='';}
		if($this->input->post('location0') != ""){ $location0 = $this->input->post('location0'); }else{ $location0 ='';}
		if($this->input->post('location1') != ""){ $location1 = $this->input->post('location1'); }else{ $location1 ='';}
		if($this->input->post('location2') != ""){ $location2 = $this->input->post('location2'); }else{ $location2 ='';}
		if($this->input->post('zip') != ""){ $zip = $this->input->post('zip'); }else{ $zip ='';} */
		
		$field_data = array('address1' => $data['address1'],'address2' => $data['address2'],'city_state' => $data['city_state'],
		'location0' => $data['location0'],'location1' => $data['location1'],'location2' => $data['location2'],'zip' => $data['zip']);
		$data = array('id'=>$data['id'],'Entry_name'=>$data['company'],'customer_id'=>$data['customer_id'],'field_data'=>$field_data);
		$saveAddress = $this->api_model->saveAddress($data);
		if($saveAddress > 1){
			 $this->set_response([
                'status' => "success",
            ], REST_Controller::HTTP_OK);
		}else{
			 $this->set_response([
                'status' => FALSE,
                'message' => 'Error inserting details'
            ], REST_Controller::HTTP_NOT_FOUND);
		}
	}
	
	 public function suggestRestaurant_post(){
		  
		$data=array('restaurant_name'=>$this->post('restaurant_name'),'restaurant_address'=>$this->post('location'),
		  'restaurant_phone'=>$this->post('phone number'),'restaurant_email'=>$this->post('email'));
		$result=$this->api_model->restaurantSuggest($data);
		
		if (isset($result)){
			  $message=[
			  'Status'=> 'Success',
			  ];
			  $this->set_response($message, REST_Controller::HTTP_CREATED);  
			  
		  }else{
		   $this->set_response([
			
			'status'=>FALSE,
			'message'=>'Customers Address Information Could not be found'
			],REST_Controller::HTTP_NOT_FOUND);
			
				  	  
		  }
		  
	  }  

	public function suggestPitstop_post(){
		 
		$data=array('restaurant_address'=>$this->post('location'));
        $result=$this->api_model->pitstopSuggest($data);
        if(isset($result)){
			
		$message=[
		  'Status'=>'Success',
		];	
		  $this->set_response($message, REST_Controller::HTTP_CREATED);  	
			
		}else{
			 $this->set_response([
			
			'status'=>FALSE,
			'message'=>'Pit stop Information Could not be found'
			],REST_Controller::HTTP_NOT_FOUND);
				
		}	 
	 }	  
	 
	  public function displayProfilepicture_post(){
		 $data=array('id'=>$this->post('user_id')); 
		 $result=$this->api_model->displayProfile($data);
		 if(!empty($result)){
		$message=[
			'Status'=>'Success',
			'url'=>$result['data']
			
			];
			 $this->set_response($message, REST_Controller::HTTP_OK); // 
			 
		}else{
			$message=[
			'url'=>'No picture'
			];
			$this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 			
		}
	  }
	  
	  public function updateProfilepicture_post(){
		 
		 $data=array('id'=>$this->post('user_id'),'profile_image'=>$this->post('image'));
		 $result=$this->api_model->profilePictureUpdate($data);
		 if(!empty($result)){
			$message=[
			'Status'=>'Success',
			];	
			$this->set_response($message, REST_Controller::HTTP_OK);  	
			
		}else{
			 $this->set_response([
			
			'status'=>FALSE,
			'message'=>'Sorry profile picture not updated'
			],REST_Controller::HTTP_NOT_FOUND);
				
		}	
	 }
	/*api number 20*/	
	public function checkCouponcode_post(){
		
		$data=array('coupon_code'=>$this->post('coupon_code'));
		$result=$this->api_model->validateCoupon($data);
		if(!empty ($result)){
			
			 $this->set_response($result, REST_Controller::HTTP_OK); 
			
		}else{
			$message=[
			'Status'=>'Coupon code could not be found'
			];
			$this->response(NULL, REST_Controller::HTTP_NOT_FOUND); 	
			
		}
	}
	
	/* api number 21*/
	public function insertFeedback_post(){
		
        $data=array('customer_id'=>$this->post('user_id'),'user_feedback'=>$this->post('feedback'));
		$result=$this->api_model->addFeedback($data);
		if(isset($result[0])){
			$message=[
			'Status'=>'Success'
			]; 
			$this->set_response($message, REST_Controller::HTTP_OK); 
		}else{
			$message=[
			'Status'=>'Error'
			];
			 $this->response($message, REST_Controller::HTTP_NOT_FOUND); 	
		}
	}
	
	/*api number 19*/
	public function insertOrder_post(){
		
		$data = json_decode(file_get_contents("php://input"),true);
		$result=$this->api_model->orderInsert($data);
		
     	if(isset($result)){
			 $message=[
			 'Status'=>'Success',
			 'Passcode'=>$result['data']
			 ]; 
			 $this->response($message, REST_Controller::HTTP_CREATED); 	
		}else{
					  
				$message=[
				'Status'=>'Error'
				];
				$this->response($message, REST_Controller::HTTP_BAD_REQUEST); 	
					  
		  }
    }	
}
