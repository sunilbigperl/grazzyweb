<?php echo form_open($this->config->item('admin_folder').'/customers/form/'.$id); ?>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('company');?></label>
			<?php
			$data	= array('name'=>'company', 'value'=>set_value('company', $company), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('firstname');?></label>
			<?php
			$data	= array('name'=>'firstname', 'value'=>set_value('firstname', $firstname), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
		<div class="form-group">
			<label><?php echo lang('lastname');?></label>
			<?php
			$data	= array('name'=>'lastname', 'value'=>set_value('lastname', $lastname), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('email');?></label>
			<?php
			$data	= array('name'=>'email', 'value'=>set_value('email', $email), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
		<div class="form-group">
			<label><?php echo lang('phone');?></label>
			<?php
			$data	= array('name'=>'phone', 'value'=>set_value('phone', $phone), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('password');?></label>
			<?php
			$data	= array('name'=>'password', 'class'=>'form-control');
			echo form_password($data); ?>
		</div>
		<div class="form-group">
			<label><?php echo lang('confirm');?></label>
			<?php
			$data	= array('name'=>'confirm', 'class'=>'form-control');
			echo form_password($data); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group">
			<label>DOB</label>
			<?php
			$data	= array('name'=>'dob', 'class'=>'form-control','value'=>set_value('dob', $dob),);
			echo form_input($data); ?>
		</div>
		<div class="form-group">
			<label>Gender</label>
			<select name="gender" class="form-control">
				<option value=""></option>
				<option value="Male" <?php if($gender == "Male"){ echo "selected"; } ?>>Male</option>
				<option value="Female" <?php if($gender == "Female"){ echo "selected"; } ?>>Female</option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label class="checkbox">
			<?php $data	= array('name'=>'email_subscribe', 'value'=>1, 'checked'=>(bool)$email_subscribe,'class'=>'form-control');
			echo form_checkbox($data).' '.lang('email_subscribed'); ?>
			</label>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label class="checkbox">
				<?php
				$data	= array('name'=>'active', 'value'=>1, 'checked'=>$active,'class'=>'form-control');
				echo form_checkbox($data).' '.lang('active'); ?>
			</label>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('group');?></label>
			<?php echo form_dropdown('group_id', $group_list, set_value('group_id',$group_id), 'class=form-control'); ?>
		</div>
	</div>

	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
	</div>
</form>