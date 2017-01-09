<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Panel</title>

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
        <div class="animate form login_form">
          <section class="login_content" id="loginwhite">
            <?php echo form_open($this->config->item('admin_folder').'/login') ?>
              <h1>Login Form</h1>
              <div>
                  <label for="username"><?php echo lang('username');?></label>
				  <?php echo form_input(array('name'=>'username', 'class'=>'form-control','placeholder'=>'Username')); ?>
              </div>
              <div>
				<label for="password"><?php echo lang('password');?></label>
				<?php echo form_password(array('name'=>'password', 'class'=>'form-control','placeholder'=>'Password')); ?>
              </div>
			  <div>
				 <?php echo form_checkbox(array('name'=>'remember', 'value'=>'true'))?>
				<?php echo lang('stay_logged_in');?>
			  </div>
			  <br/>
              <div>
                 <input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
				 <input type="hidden" value="submitted" name="submitted"/>
				<label> <input class="btn btn-default" type="submit" style="text-align:center;"	 value="<?php echo lang('login');?>"/> </label>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
               
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class=""></i> Admin panel!</h1>
                  <p>©2016 All Rights Reserved. Privacy and Terms</p>
                </div>
              </div>
            <?php echo  form_close(); ?>
          </section>
        </div>

        
      </div>
    </div>
</html>
