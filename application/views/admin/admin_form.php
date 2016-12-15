<?php echo form_open($this->config->item('admin_folder').'/admin/form/'.$id); ?>
	<div class="form-group">
		<label><?php echo lang('firstname');?></label>
		<?php
		$data	= array('name'=>'firstname', 'value'=>set_value('firstname', $firstname),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">	
		<label><?php echo lang('lastname');?></label>
		<?php
		$data	= array('name'=>'lastname', 'value'=>set_value('lastname', $lastname),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">	
		<label><?php echo lang('username');?></label>
		<?php
		$data	= array('name'=>'username', 'value'=>set_value('username', $username),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">		
		<label>Phone No</label>
		<?php
		$data	= array('name'=>'phone', 'value'=>set_value('phone', $phone),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">		
		<label><?php echo lang('email');?></label>
		<?php
		$data	= array('name'=>'email', 'value'=>set_value('email', $email),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">	
		<label><?php echo lang('access');?></label>
		<?php
		$options = array(	'Admin'		=> 'Admin',
							'Restaurant manager'	=> 'Restaurant manager',
							'Deliver manager' => 'Deliver manager'
		                );
		echo form_dropdown('access', $options, set_value('access', $access),'class="form-control" id="access"');
		?>
	</div>
	<div class="form-group">	
		<label><?php echo lang('password');?></label>
		<?php
		$data	= array('name'=>'password','class'=>'form-control');
		echo form_password($data);
		?>
	</div>
	<div class="form-group">	
		<label><?php echo lang('confirm_password');?></label>
		<?php
		$data	= array('name'=>'confirm','class'=>'form-control');
		echo form_password($data);
		?>
	</div>
	<div class="form-group">	
		<label for="enabled"><?php echo lang('enabled');?> </label>
		<?php echo form_dropdown('enabled', array(''=>'select','1' => lang('enabled'),'0' => lang('disabled')), set_value('enabled',$enabled),'class="form-control" id="enableddata"'); ?>
	</div>
	<div class="form-group" id="datetime" <?php if($FromDate != "0000-00-00"){ echo 'style="display:block;'; }else{ echo 'style="display:none;';}?>">
			<label><strong>Disabled From date</strong></label>
			<input type="date" name="FromDate" id="FromDate" value="<?=$FromDate;?>">
			<label><strong>Disabled To date</strong></label>
			<input type="date" name="ToDate" id="ToDate" value="<?=$ToDate;?>">
	</div>
	<div class="form-group">		
		<div class="form-actions">
			<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
		</div>
	</div>
</form>
<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
$(document).ready(function(){
	$("#enableddata").change(function(){
		if($(this).val() == "0"){
			if($("#access").val() == "Deliver manager"){
				$("#datetime").css('display','block');
			}
		}
	});
});
</script>
