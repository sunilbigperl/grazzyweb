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
        $this->load->model('Roadrunner_model');
        $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
    }
    
    public function customercheck_post(){
         $data = [
            'firstname' => $this->post('firstname'),
            'phone' => $this->post('phone'),

            'did'   => $this->post('did')
        ];

        $message = $this->api_model->customercheck($data);
        
        $this->set_response($message, REST_Controller::HTTP_OK);
    }
    
    public function customer_get($id = NULL)
    {
        
        $id = isset($id) ? $id : "";
        $users = $this->api_model->getUsers();
        
        if ($id == NULL)
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
                ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
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
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
        }
    }
    
    public function SearchRest_post(){
        
        $data = [
            'name' => $this->input->post('name'),
            'area' => $this->input->post('area'),
            'latitude' => $this->input->post('latitude'),
            'langitude' => $this->input->post('langitude')
        ];
        $SearchRest =  $this->api_model->SearchRest($data);
        
        if($SearchRest){
            $this->set_response($SearchRest, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
         else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Restaurant could not be found '
            ], REST_Controller::HTTP_OK);
        }
    }
    
    public function Getcoordinates_get($id){
        $coordinates = $this->api_model->Getcoordinates($id);
        if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($coordinates))
        {
            
            
            $this->set_response($coordinates, REST_Controller::HTTP_OK); 
        //  print_r($restaurants);exit;  
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'coordinates  could not be found for the location'
            ], REST_Controller::HTTP_OK);
        }
        
    }
    public function HereList_get($id = null)
    {
        $id = isset($id) ? $id : "";
        $users = $this->api_model->getHereList();
    

        if ($id == NULL)
        {
            if ($users)
            {
                $this->response($users, REST_Controller::HTTP_OK); 
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No lists were found'
                ], REST_Controller::HTTP_OK); 
            }
        }

        $id = (int) $id;

        if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

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
                'message' => 'List could not be found'
            ], REST_Controller::HTTP_OK); // NOT_FOUND (404) being the HTTP response code
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
        //  print_r($restaurants);exit;  
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'restaurants  could not be found for the pitstop'
            ], REST_Controller::HTTP_OK);
        }
        
     // print_r($restaurants);exit;  
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
            ], REST_Controller::HTTP_OK);
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
            ], REST_Controller::HTTP_OK);
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
        

        $this->set_response(['response' => "success"], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
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
            ], REST_Controller::HTTP_OK);
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
            ], REST_Controller::HTTP_OK);
        }
    }
    
    public function deliveryboylocation_get(){
        
        $data = [
            'deliveryboy_id' => $this->get('deliveryboy_id'),
            'order_id' => $this->get('order_id'),
        ];
        
        $deliveryboylocation =  $this->api_model->deliveryboylocation($data);
        //print_r($deliveryboylocation); exit;
    
        if ($deliveryboylocation)
        {
            $this->set_response($deliveryboylocation, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'deliveryboy location  could not be found for the customer'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function deliveryboylocation_post(){
        
        $data = [
            'deliveryboy_id' => $this->post('deliveryboy_id'),
            'order_id' => $this->post('order_id'),
        ];
        
        $deliveryboylocation =  $this->api_model->deliveryboylocation($data);
        //print_r($deliveryboylocation); exit;
    
        if ($deliveryboylocation)
        {
            $this->set_response($deliveryboylocation, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'deliveryboy location  could not be found for the customer'
            ], REST_Controller::HTTP_OK);
        }
    }
    
    public function customerlocation_get($id){
        $customerlocation =  $this->api_model->customerlocation($id);
        
        if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($customerlocation))
        {
            $this->set_response($customerlocation, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Location not found'
            ], REST_Controller::HTTP_OK);
        }
    }
    
    public function pitstopsuser_post(){
        $data = [
            'southwest_lat' => $this->post('southwest_lat'),
            'southwest_lng' => $this->post('southwest_lng'),
            'northeast_lat' => $this->post('northeast_lat'),
            'northeast_lng' => $this->post('northeast_lng'),
        ];
        $pitstopsuser =  $this->api_model->pitstopsuser1($data);
        if (!empty($pitstopsuser))
        {
            $this->set_response($pitstopsuser, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'pitstop list  could not be found'
            ], REST_Controller::HTTP_OK);
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
            ], REST_Controller::HTTP_OK);
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
            ], REST_Controller::HTTP_OK);
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
        
    /*  $field_data = array('address1' => $data['address1'],'address2' => $data['address2'],'city_state' => $data['city_state'],
        'location0' => $data['location0'],'location1' => $data['location1'],'location2' => $data['location2'],'zip' => $data['zip']);
        $data = array('id'=>$data['id'],'Entry_name'=>$data['company'],'customer_id'=>$data['customer_id'],'field_data'=>$field_data);*/
        
        
        $field_data=array('address1'=>$this->post('address1'),'address2'=>$this->post('address2'),'city_state'=>$this->post('city_state'),
               'location0'=>$this->post('location0'),'location1'=>$this->post('location1'),'location2'=>$this->post('location2'),
               'zip'=>$this->post('zip'));
        $data=array('id'=>$this->post('id'),'Entry_name'=>$this->post('company'),'customer_id'=>$this->post('customer_id'),'field_data'=>$field_data);  
        
        
        $saveAddress = $this->api_model->saveAddress($data);
        //print_r($data);exit;
        if($saveAddress > 1){
             $this->set_response([
                'status' => "success",
            ], REST_Controller::HTTP_OK);
        }else{
             $this->set_response([
                'status' => FALSE,
                'message' => 'Error inserting details'
            ], REST_Controller::HTTP_OK);
        }
    }
    
     public function suggestRestaurant_post(){
//       $params = json_decode(file_get_contents('php://input'), TRUE);
// $data=array('restaurant_name'=>$params['restaurant_name'],'restaurant_address'=>$params['restaurant_address'],
//          'restaurant_phone'=>$params['restaurant_phone'],'restaurant_email'=>$params['restaurant_email'],'customer'=>$this->post('user_id'));

$data=array('restaurant_name'=>$this->post('restaurant_name'),'restaurant_address'=>$this->post('location'),
          'restaurant_phone'=>$this->post('phone_number'),'restaurant_email'=>$this->post('email'),'customer'=>$this->post('user_id'));
        $result=$this->api_model->restaurantSuggest($data);
        
//$result=$this->api_model->restaurantSuggest($data);

if (isset($result)){
        $message=[
        'Status'=> 'Success',
           ];
            $this->set_response($message, REST_Controller::HTTP_CREATED);  
              
           }else{
           $this->set_response([
            
         'status'=>FALSE,
          'message'=>'Customers Address Information Could not be found'
          ],REST_Controller::HTTP_OK);
            
                      
           }
          
       } 
         
        // $data=array('restaurant_name'=>$this->post('restaurant_name'),'restaurant_address'=>$this->post('location'),
        //   'restaurant_phone'=>$this->post('phone_number'),'restaurant_email'=>$this->post('email'),'customer'=>$this->post('user_id'));
        //$result=$this->api_model->restaurantSuggest($data);
        

        // if (isset($result)){
        //    $message=[
        //    'Status'=> 'Success',
        //    ];
        //    $this->set_response($message, REST_Controller::HTTP_CREATED);  
              
        //   }else{
        //    $this->set_response([
            
        //  'status'=>FALSE,
        //  'message'=>'Customers Address Information Could not be found'
        //  ],REST_Controller::HTTP_OK);
            
                      
        //   }
          
     //  } 

    public function suggestPitstop_post(){
         
        $data=array('restaurant_address'=>$this->post('location'),'customer'=>$this->post('user_id'),'restaurant_latitude'=>$this->post('restaurant_latitude'),
        'restaurant_langitude'=>$this->post('restaurant_langitude'));
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
            ],REST_Controller::HTTP_OK);
                
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
      
      public function OrderProfilepicture_post(){
         $data=array('id'=>$this->post('order_id')); 
         $result=$this->api_model->orderProfile($data);
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

      public function OrderProfilepicture_get(){
         $data=array('id'=>$this->get('order_id')); 
         $result=$this->api_model->orderProfile($data);
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
         
         $data=array('id'=>$this->post('user_id'),'profile_image'=>$this->post('profile_image'));
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
            ],REST_Controller::HTTP_OK);
                
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
            $this->response(NULL, REST_Controller::HTTP_OK);    
            
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
             $this->response($message, REST_Controller::HTTP_OK);   
        }
    }
    
    public function UpdateUser_post(){
        
        $data=array('firstname'=>$this->post('firstname'),'id'=>$this->post('id'));
        $result=$this->api_model->UpdateUser($data);
        if(isset($result[0])){
            $message=[
            'response'=>'Success'
            ]; 
            $this->set_response($message, REST_Controller::HTTP_OK); 
        }else{
            $message=[
            'Status'=>'Error'
            ];
             $this->response($message, REST_Controller::HTTP_OK);   
        }
    }
    
    /*api number 19*/
    public function insertOrder_post(){
        
        $data = json_decode(file_get_contents("php://input"),true);
        $result=$this->api_model->orderInsert($data);
        
        if(isset($result)){
             $message=[
             'Status'=>'Success',
             'Passcode'=>$result['data'],
             'order_id' => $result['order_id'],
             'order_number' => $result['order_number']
             ]; 
             $this->response($message, REST_Controller::HTTP_CREATED);  
        }else{
                      
                $message=[
                'Status'=>'Error'
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);   
                      
          }
    }
    /*api number 23*/
    public function orderEmail_post(){
        
       $data=array('customer_id'=>$this->post('user_id'),'id'=>$this->input->post('order_ids'));
        $result=$this->api_model->userOrderEmail($data);

//print_r($result);exit;
        // $message="
        //  <h6>Order_id: ".$data['order_id']."</h6>
        
        // <h6>cost: ".$data['cost']."</h6>";
        //print_r($result);exit;
        if(isset($result)){
            $message=[
            'Status'=>'Success',
            //'data'=>$result
            
            ]; 
            $this->set_response($message, REST_Controller::HTTP_OK); 
            
        //     $this->load->library('email');
        //     //$msg=$result;
        //     $config = Array(
        //     'protocol' => 'smtp',
        //     'smtp_host' => 'ssl://smtp.googlemail.com',
        //     'smtp_port' => 465,
        //     'smtp_user' => 'suggest.eatsapp@gmail.com',
        //     'smtp_pass' => 'devang123',
        //     'mailtype'  => 'html', 
        //     'charset'   => 'iso-8859-1',
        //     'crlf' => "\r\n",
        //     'newline' => "\r\n"
        // );
        //  $this->email->initialize($config);
        // $this->email->from('order@eatsapp.in', 'Grazzy');
        // $this->email->to('gkamatagi@gmail.com');
        // //$this->email->cc('laxman.bigperl@gmail.com');
        // //$this->email->bcc('them@their-example.com');

        // $this->email->subject('Order Details From Grazzy ');
        // $this->email->message($message);

        // $this->email->send();
        }else{
            $message=[
            'Status'=>'Error'
            ];
             $this->response($message, REST_Controller::HTTP_OK);   
        }   
        
    }






  /*api number 25*/
  public function addressDelete_post(){
      
      $data=array('id'=>$this->post('customers_address_bank_id'));
      $result=$this->api_model->delete_customer($data);
      if(!empty ($result)){
      $message=[
      'Status'=>'Success'
      
      ];$this->set_response($message,REST_Controller::HTTP_OK);

      }else{
          
          $message=[
          'Status'=>'Error'
        
          ]; $this->response($message, REST_Controller::HTTP_OK);   

      }

    
    }
    
    public function delboylogin_post(){
        $data['firstname'] = $this->input->post('firstname');
        $data['phone'] = $this->input->post('phone');
        $data['did'] = $this->input->post('did');
        $result = $this->api_model->delboycheck($data);
        
        $this->set_response($result, REST_Controller::HTTP_OK);
    }
    
    public function delboyTorestfeedback_post(){
        $data = json_decode(file_get_contents("php://input"),true);
        $result = $this->api_model->delboyTorestfeedback($data);
        $message=['response'=>$result];
        $this->set_response($message, REST_Controller::HTTP_OK);
    }
    
    public function adddelboylocation_post()
    {
        $data = [
            'deliveryboy_id' => $this->post('deliveryboy_id'),
            'latitude' => $this->post('latitude'),
            'langitude' => $this->post('langitude'),
        ];
       $userslocation =  $this->api_model->adddelboylocation($data);
        

        $this->set_response($userslocation, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
    
    public function changeorderstatus_post()
    {
        $data = [
            'id' => $this->post('id'),
            'status' => $this->post('status'),
            'distance' => $this->post('distance'),
        ];
       $status =  $this->api_model->changeorderstatus($data);
        $message=['response'=>$status];

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
    
    public function delboyOrders_get($id)
    { 
        $delboyOrders = $this->api_model->delboyOrders($id);
        
        
        if ($id <= 0)
        {
            $this->response(NULL, REST_Controller::HTTP_BAD_REQUEST); 
        }

       
        if (!empty($delboyOrders))
        {
            $this->set_response($delboyOrders, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Orders could not be found'
            ], REST_Controller::HTTP_OK);
        }
    }
    
    /*api number 26*/
    public function getFeedback_post(){
        $data=array('customer_id'=>$this->post('customer_id'));
        $result=$this->api_model->feedbackGet($data);
             if(!empty ($result)){
      $message=[
      'Status'=>'Success',
      'data'=>$result['data']
      
      ];$this->set_response($message,REST_Controller::HTTP_OK);

      }else{
          
          $message=[
          'Status'=>'Error'
        
          ]; $this->response($message, REST_Controller::HTTP_OK);   

      }
    
    }
    
    /*api number 27*/
    public function updateFeedback_post(){
        
        $data=array('id'=>$this->post('id'),'user_feedback'=>$this->post('user_feedback'));
        $result=$this->api_model->feedbackUpdate($data);
         if(!empty ($result)){
      $message=[
      'Status'=>'Success'
      
      ];$this->set_response($message,REST_Controller::HTTP_OK);

      }else{
          
          $message=[
          'Status'=>'Error'
        
          ]; $this->response($message, REST_Controller::HTTP_OK);   

      }

        
        
        
        
    }
    /*api number 28*/
    public function deleteFeedabck_post(){
        
    $data=array('id'=>$this->post('id'));
    $result=$this->api_model->feedbackdelete($data);
    
         if(!empty ($result)){
      $message=[
      'Status'=>'Success'
      
      ];$this->set_response($message,REST_Controller::HTTP_OK);

      }else{
          
          $message=[
          'Status'=>'Error'
        
          ]; $this->response($message, REST_Controller::HTTP_OK);   

      }
        
        
        
    }
    
    public function GetNotifications_get(){
        $notifications = $this->api_model->getnotifications();
    
        if (!empty($notifications))
        {
            $this->set_response($notifications, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Orders could not be found'
            ], REST_Controller::HTTP_OK);
        }
    }
    
    public function GetOrderStatus_get($id){
        $OrderStatus = $this->api_model->GetOrderStatus($id);
    
        if (!empty($OrderStatus))
        {
            $this->set_response($OrderStatus, REST_Controller::HTTP_OK); 
        }
        else
        {
            $this->set_response([
                'status' => FALSE,
                'message' => 'Orders could not be found'
            ], REST_Controller::HTTP_OK);
        }
    }
}
