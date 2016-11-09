<?php echo form_open_multipart($this->config->item('admin_folder').'/restaurant/form/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Image</a></li>
		
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset>
				<label for="name">Restaurant name</label>
				<?php
				$data	= array('name'=>'restaurant_name', 'value'=>set_value('restaurant_name', $restaurant_name), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant address</label>
				<?php
				$data	= array('name'=>'restaurant_address', 'class'=>'redactor', 'value'=>set_value('restaurant_address', $restaurant_address));
				echo form_textarea($data);
				?>
				
				<label for="restaurant_address">Restaurant phone</label>
				<?php
				$data	= array('name'=>'restaurant_phone', 'value'=>set_value('restaurant_phone', $restaurant_phone), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant email</label>
				<?php
				$data	= array('name'=>'restaurant_email', 'value'=>set_value('restaurant_email', $restaurant_email), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant latitude</label>
				<?php
				$data	= array('name'=>'restaurant_latitude', 'value'=>set_value('restaurant_latitude', $restaurant_latitude), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant langitude</label>
				<?php
				$data	= array('name'=>'restaurant_langitude', 'value'=>set_value('restaurant_langitude', $restaurant_langitude), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant branch</label>
				<?php
				$data	= array('name'=>'restaurant_branch', 'value'=>set_value('restaurant_branch', $restaurant_branch), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>
			</fieldset>
		</div>

		<div class="tab-pane" id="attributes_tab">
			
			<fieldset>
		

				<label for="image"><?php echo lang('image');?> </label>
				<div class="input-append">
					<?php echo form_upload(array('name'=>'image'));?><span class="add-on"><?php echo lang('max_file_size');?> <?php echo  $this->config->item('size_limit')/1024; ?>kb</span>
				</div>
					
				<?php if($restaurant_id && $image != ''):?>
				
				<div style="text-align:center; padding:5px; border:1px solid #ddd;"><img src="<?php echo base_url('uploads/images/small/'.$image);?>" alt="current"/><br/><?php echo lang('current_file');?></div>
				
				<?php endif;?>
				
			</fieldset>
			
		</div>
		
	
	</div>

</div>

<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
</script>