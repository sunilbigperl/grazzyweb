<?php if($this->auth->check_access('Restaurant manager')) : ?>
<?php $orders = $this->Order_model->get_neworders(); 
// print_r($orders[0]->enabled);exit;
if($orders[0]->enabled==0)
{
   $this->session->sess_expiration = '30'; //~ 30 seconds
   $this->session->sess_expire_on_close = 'false';
   $this->auth->logout();

	//when someone logs out, automatically redirect them to the login page.
	$this->session->set_flashdata('message', lang('message_logged_out'));
	redirect($this->config->item('admin_folder').'/login');
}
?>

<script>
       setTimeout(function(){
           location.reload();
           
       },33000); 
    </script>

<?php endif; ?> 