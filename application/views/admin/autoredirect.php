<?php if($this->auth->check_access('Restaurant manager')) : ?>

<?php 
$userdata = $this->session->userdata('admin');
 $sql = $this->db->query("select b.enabled,a.enabled as b_enabled from admin a,restaurant b where  a.username='".$userdata['username']."' and  b.restaurant_manager=a.id ");

 $results= $sql->result_array();
 // print_r( $results[0]['enabled']);
 //print_r( $results[0]['b_enabled']);exit;

 //if($results[0]['enabled']==1){

 	if($results[0]['b_enabled']==1)
 	{

 		 $redirect = $this->config->item('admin_folder').'/orders/dashboard';

 		
 	}else
 	{
 		 $this->session->sess_expiration = '30'; //~ 30 seconds
        $this->session->sess_expire_on_close = 'false';
         $this->auth->logout();
		//$this->session->set_flashdata('error', 'Your renewal date expired');
		$this->session->set_flashdata('error','It appears that your access to the Portal has been restricted by the Administrator. Please write to contact@eatsapp.in or contact on +919820076457');
		redirect($this->config->item('admin_folder').'/login');
 	}

  
 //}









?>




<script>
       setTimeout(function(){
           location.reload();
           
       },33000); 
    </script>

<?php endif; ?> 
