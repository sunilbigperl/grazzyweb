<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Eatsapp...</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('vendors/css/bootstrap.css');?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('vendors/css/font-awesome.min.css');?>"  rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('vendors/css/nprogress.css');?>" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url('vendors/css/bootstrap-progressbar-3.3.4.min.css');?>" rel="stylesheet">
	
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url('vendors/css/daterangepicker.css');?>"  rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('vendors/css/custom.min.css');?>" rel="stylesheet">

	 <link href="<?php echo base_url('vendors/css/bootstrap-table.css ');?>" rel="stylesheet">
 </head>
	 <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
	  <div class="container">
	
		<?php
		//lets have the flashdata overright "$message" if it exists
		if($this->session->flashdata('message'))
		{
			$message    = $this->session->flashdata('message');
		}
		
		if($this->session->flashdata('error'))
		{
			$error  = $this->session->flashdata('error');
		}
		
		if(function_exists('validation_errors') && validation_errors() != '')
		{
			$error  = validation_errors();
		}
		?>
		
		<div id="js_error_container" class="alert alert-error" style="display:none;"> 
			<p id="js_error"></p>
		</div>
		
		<div id="js_note_container" class="alert alert-note" style="display:none;">
			
		</div>
		
		<?php if (!empty($message)): ?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($error)): ?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
</div>   

        <div class="animate form login_form">
          <section class="login_content" id="loginwhite">
		  <!-- <?php if (!empty($error)): ?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $error; ?>
			</div>
		<?php endif; ?> -->
            <?php echo form_open($this->config->item('admin_folder').'/login') ?>
              <img src="<?php echo base_url('uploads/images/logo1.png');?>" height="150" width="150" alt="logo">
              <div>
              <!--    <label for="username"><?php echo lang('username');?></label> -->
				  <input id="form-control1" type="text" name="username" placeholder="Username" class="form-control" autocomplete="off">
              </div>
              <div>
			<!--	<label for="password"><?php echo lang('password');?></label>-->
				<?php echo form_password(array('name'=>'password', 'class'=>'form-control','id'=>'form-control1','placeholder'=>'Password')); ?>
              </div>
			  
			  <br/>
              <div >
                 <input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
				 <input type="hidden" value="submitted" name="submitted"/>
				<label> <input class="btn btn-primary" type="submit" style="margin-left: 0em;"	 value="<?php echo lang('login');?>"/> </label>
              </div>

              <div class="clearfix"></div>
			 <div >
				<a href="<?php echo site_url('login/forgot_password'); ?>" style="text-align:center;margin-left:10px;">Forgot Password</a>
			</div>
              <div class="separator">
               
                <div class="clearfix"></div>
                <br />

                <div style="color: white;background: red;font-family: sans-serif;padding: 10px;border-radius: 5px;">
                  <p style="font-family:Century Gothic;font-size:25px;"><i class=""></i>Welcome to eatsapp</p>
                  <p>© 2017 All Rights Reserved.</p>
                </div>
              </div>
            <?php echo  form_close(); ?>
          </section>
        </div>

        
      </div>
    </div>
</html>
