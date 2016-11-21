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
	
	public function restaurantuser_post(){
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
	
	public function restaurantforloctaion_delete($data)
    {
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
	
}
