<?php echo form_open($this->config->item('admin_folder').'/deliveryboy/form/'.$id); ?>
	
		<label>Name</label>
		<?php
		$data	= array('name'=>'name', 'value'=>set_value('name', $name));
		echo form_input($data);
		?>
		
		<label>Address</label>
		<textarea name="address" class="form-control"><?=$address?></textarea>
		
		<label>Phone</label>
		<?php
		$data	= array('name'=>'phone', 'value'=>set_value('phone', $phone));
		echo form_input($data);
		?>
		
		<label>Email</label>
		<?php
		$data	= array('name'=>'email', 'value'=>set_value('email', $email));
		echo form_input($data);
		?>
		
		<label for="enabled"><?php echo lang('enabled');?> </label>
        <?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>
				
		<div class="form-actions">
			<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
		</div>
	
</form>