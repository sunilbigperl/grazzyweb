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
class Cron extends REST_Controller {

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

    public function RestCron(){
        date_default_timezone_set('Asia/Calcutta');
        $date = date('Y-m-d H:i:s');
        $currentDate = strtotime($date);
        $futureDate = $currentDate-(60*4);
        $formatDate = date("Y-m-d H:i:s", $futureDate);
        
        $sql = $this->db->query("select * from orders a, restaurant b where a.restaurant_id = b.restaurant_id and restaurant_manager_status = 0 and ordered_on < '".$formatDate."'");
        if($sql->num_rows() > 0){
            $result =  $sql->result_array();
            foreach($result as $row){
                $this->db->query("update orders set status='order cancelled' where id='".$row['id']."'");
                $restaurant_phone = $row['restaurant_phone'];
                $message = "<h4>Your order with order number ".$row['order_number']." is pending</h4>
                <h4>Please accept the order to avoid the penalty</h4>";
                //  $config = Array(
                //     'protocol' => 'smtp',
                //     'smtp_host' => 'ssl://smtp.gmail.com',
                //     'smtp_port' => 465,
                //     'smtp_user' => 'suggest.eatsapp@gmail.com',
                //     'smtp_pass' => 'devang123',
                //     'mailtype'  => 'html', 
                //     'charset'   => 'iso-8859-1',
                //     'crlf' => "\r\n",
                //     'newline' => "\r\n"
                // );
                // $this->load->library('email',$config);
                // $this->email->from('suggest@eatsapp.in', 'EatsApp');
                // $this->email->to($row['restaurant_email']);
                // $this->email->bcc('lvijetha90@gmail.com');
                // $this->email->subject('EatsApp: Restaurant order accept pending');
                // $this->email->message($message);
                //  $this->email->send(); 
            }
        }
    }
}