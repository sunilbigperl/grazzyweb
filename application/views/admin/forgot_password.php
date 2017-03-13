<div class="row" style="margin-top:50px;">
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

	<div class="span6 offset3">
		<div class="page-header">
			<h1>Forgot password</h1>
		</div>
		<form action="<?php echo site_url('login/forgot_password'); ?>" method="post" class="form-horizontal">
				<fieldset>
				
					<div class="control-group">
						<label class="control-label" for="email"><?php echo lang('email');?></label>
						<div class="controls">
							<input type="text" name="email" class="span3"/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"></label>
						<div class="controls">
							<input type="hidden" value="submitted" name="submitted"/>
							<input type="submit" value="Resset password" name="submit" class="btn btn-primary"/>
						</div>
					</div>
				</fieldset>
		</form>
		<div style="text-align:center;">
			<a href="<?php echo site_url('login'); ?>">Return to login</a>
		</div>
	</div>
</div>