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
		  <?php if (!empty($error)): ?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
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
			  <div>
				 <?php echo form_checkbox(array('name'=>'remember', 'value'=>'true'))?>
				<?php echo lang('stay_logged_in');?>
			  </div>
			  <br/>
              <div>
                 <input type="hidden" value="<?php echo $redirect; ?>" name="redirect"/>
				 <input type="hidden" value="submitted" name="submitted"/>
				<label> <input class="btn btn-primary" type="submit" style="text-align:center;"	 value="<?php echo lang('login');?>"/> </label>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
               
                <div class="clearfix"></div>
                <br />

                <div style="color: white;background: #e86051;font-family: sans-serif;padding: 10px;border-radius: 5px;">
                  <h1><i class=""></i>Welcome to Eatsapp!</h1>
                  <p>©2017 All Rights Reserved. Privacy and Terms</p>
                </div>
              </div>
            <?php echo  form_close(); ?>
          </section>
        </div>

        
      </div>
    </div>
</html>
