<?php echo form_open($this->config->item('admin_folder').'/deliveryboy/form/'.$id); ?>
	<div class="form-group">	
		<label>Name</label>
		<?php
		$data	= array('name'=>'name', 'value'=>set_value('name', $name),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">		
		<label>Address</label>
		<textarea name="address" class="form-control"><?=$address?></textarea>
	</div>
	<div class="form-group">	
		<label>Phone</label>
		<?php
		$data	= array('name'=>'phone', 'value'=>set_value('phone', $phone),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">	
		<label>Email</label>
		<?php
		$data	= array('name'=>'email', 'value'=>set_value('email', $email),'class'=>'form-control');
		echo form_input($data);
		?>
	</div>
	<div class="form-group">	
		<label for="enabled"><?php echo lang('enabled');?> </label>
        <?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled),'class="form-control"'); ?>
	</div>			
		<div class="form-actions">
			<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
		</div>
	
</form>