<?php
Class Deliveryboy_model extends CI_Model
{

    var $CI;

    function __construct()
    {
        parent::__construct();

        $this->CI =& get_instance();
        $this->CI->load->database(); 
        $this->CI->load->helper('url');
    }
    
    function get_lists()
    {
        $result = $this->db->get('delivery_boy');
        return $result->result();
    }
    
	function get_deliveryboys($parent)
	{
		
		$this->db->order_by('id', 'ASC');
		$this->db->where('id', $parent);
		$this->db->where('enabled', 1);
		$result = $this->db->get('delivery_boy')->result();
		//echo $this->db->last_query(); exit;
		$return	= array();
		foreach($result as $page)
		{

			$return[$page->id]				= $page;
		}
		
		return $return;
	}
	
	
   
    
    function save($delivery_boy)
    {
        if ($delivery_boy['id'])
        {
            $this->db->where('id', $delivery_boy['id']);
            $this->db->update('delivery_boy', $delivery_boy);
            return $delivery_boy['id'];
        }
        else
        {
            $this->db->insert('delivery_boy', $delivery_boy);
            return $this->db->insert_id();
        }
    }
    
    function deactivate($id)
    {
        $customer   = array('id'=>$id, 'active'=>0);
        $this->save_customer($customer);
    }
    
    function delete($id)
    {
        /*
        deleting a customer will remove all their orders from the system
        this will alter any report numbers that reflect total sales
        deleting a customer is not recommended, deactivation is preferred
        */
        
        //this deletes the customers record
        $this->db->where('id', $id);
        $this->db->delete('customers');
        
        // Delete Address records
        $this->db->where('customer_id', $id);
        $this->db->delete('customers_address_bank');
        
        //get all the orders the customer has made and delete the items from them
        $this->db->select('id');
        $result = $this->db->get_where('orders', array('customer_id'=>$id));
        $result = $result->result();
        foreach ($result as $order)
        {
            $this->db->where('order_id', $order->id);
            $this->db->delete('order_items');
        }
        
        //delete the orders after the items have already been deleted
        $this->db->where('customer_id', $id);
        $this->db->delete('orders');
    }
    
    function check_email($str, $id=false)
    {
        $this->db->select('email');
        $this->db->from('customers');
        $this->db->where('email', $str);
        if ($id)
        {
            $this->db->where('id !=', $id);
        }
        $count = $this->db->count_all_results();
        
        if ($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    /*
    these functions handle logging in and out
    */
    function logout()
    {
        $this->CI->session->unset_userdata('customer');
        //force expire the cookie
        $this->generateCookie('[]', time()-3600);
        $this->go_cart->destroy(false);
    }

    private function generateCookie($data, $expire)
    {
        setcookie('GoCartCustomer', $data, $expire, '/', $_SERVER['HTTP_HOST']);
    }
    
    function login($email, $password, $remember=false, $fblogin=false)
    {
        $this->db->select('*');
        $this->db->where('email', $email);
        $this->db->where('active', 1);
		if($fblogin == true){
			$this->db->where('fb_login',  1);
		}else{
			$this->db->where('password',  sha1($password));
		}
        $this->db->limit(1);
        $result = $this->db->get('customers');
        $customer   = $result->row_array();
		
	/*	  function login($email, $password, $remember=false)
    {
        $this->db->select('*');
        $this->db->where('email', $email);
        $this->db->where('active', 1);
        $this->db->where('password',  sha1($password));
        $this->db->limit(1);
        $result = $this->db->get('customers');
        $customer   = $result->row_array(); */
        
        if ($customer)
        {
            
            // Retrieve customer addresses
            $this->db->where(array('customer_id'=>$customer['id'], 'id'=>$customer['default_billing_address']));
            $address = $this->db->get('customers_address_bank')->row_array();
            if($address)
            {
                $fields = unserialize($address['field_data']);
                $customer['bill_address'] = $fields;
                $customer['bill_address']['id'] = $address['id']; // save the addres id for future reference
            }
            
            $this->db->where(array('customer_id'=>$customer['id'], 'id'=>$customer['default_shipping_address']));
            $address = $this->db->get('customers_address_bank')->row_array();
            if($address)
            {
                $fields = unserialize($address['field_data']);
                $customer['ship_address'] = $fields;
                $customer['ship_address']['id'] = $address['id'];
            } else {
                 $customer['ship_to_bill_address'] = 'true';
            }
            
            
            // Set up any group discount 
            if($customer['group_id']!=0) 
            {
                $group = $this->get_group($customer['group_id']);
                if($group) // group might not exist
                {
                    $customer['group'] = $group;
                }
            }
            
            if($remember)
            {
                $loginCred = json_encode(array('email'=>$email, 'password'=>$password));
                $loginCred = base64_encode($this->aes256Encrypt($loginCred));
                //remember the user for 6 months
                $this->generateCookie($loginCred, strtotime('+6 months'));
            }
            
            // put our customer in the cart
            $this->go_cart->save_customer($customer);

        
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function is_logged_in($redirect = false, $default_redirect = 'secure/login/')
    {
        
        //$redirect allows us to choose where a customer will get redirected to after they login
        //$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.
        
        $customer = $this->go_cart->customer();
        if (!isset($customer['id']))
        {
            //check the cookie
            if(isset($_COOKIE['GoCartCustomer']))
            {
                //the cookie is there, lets log the customer back in.
                $info = $this->aes256Decrypt(base64_decode($_COOKIE['GoCartCustomer']));
                $cred = json_decode($info, true);

                if(is_array($cred))
                {
                    if( $this->login($cred['email'], $cred['password']) )
                    {
                        return $this->is_logged_in($redirect, $default_redirect);
                    }
                }
            }

            //this tells gocart where to go once logged in
            if ($redirect)
            {
                $this->session->set_flashdata('redirect', $redirect);
            }
            
            if ($default_redirect)
            {   
                redirect($default_redirect);
            }
            
            return false;
        }
        else
        {
            return true;
        }
    }
    
    function reset_password($email)
    {
        $this->load->library('encrypt');
        $customer = $this->get_customer_by_email($email);
        if ($customer)
        {
            $this->load->helper('string');
            $this->load->library('email');
            
            $new_password       = random_string('alnum', 8);
            $customer['password']   = sha1($new_password);
            $this->save($customer);
            
            $this->email->from($this->config->item('email'), $this->config->item('site_name'));
            $this->email->to($email);
            $this->email->subject($this->config->item('site_name').': Password Reset');
            $this->email->message('Your password has been reset to <strong>'. $new_password .'</strong>.');
            $this->email->send();
            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function get_customer_by_email($email)
    {
        $result = $this->db->get_where('customers', array('email'=>$email));
        return $result->row_array();
    }

    private function aes256Encrypt($data)
    {
        $key = config_item('encryption_key');
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
    }
    private function aes256Decrypt($data)
    {
        $key = config_item('encryption_key');
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
        $padding = ord($data[strlen($data) - 1]); 
        return substr($data, 0, -$padding); 
    }

    // Customer groups functions
    function get_groups()
    {
        return $this->db->get('customer_groups')->result();     
    }
    
    function get_group($id)
    {
        return $this->db->where('id', $id)->get('customer_groups')->row();      
    }
    
    function delete_group($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('customer_groups');
    }
    
    function save_group($data)
    {
        if(!empty($data['id'])) 
        {
            $this->db->where('id', $data['id'])->update('customer_groups', $data);
            return $data['id'];
        } else {
            $this->db->insert('customer_groups', $data);
            return $this->db->insert_id();
        }
    }
}