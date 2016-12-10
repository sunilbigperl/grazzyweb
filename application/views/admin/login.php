<?php include('header.php'); ?>
 <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
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
				<label> <input class="btn btn-primary" type="submit" style="text-align:center;"	 value="<?php echo lang('login');?>"/> </label>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
               
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                  <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                </div>
              </div>
            <?php echo  form_close(); ?>
          </section>
        </div>

        
      </div>
    </div>
