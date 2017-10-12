
<?php echo form_open($this->config->item('admin_folder').'/customers/SaveCharges'); ?>

	<div class="row">
		<div class="form-group">
			<label>GST</label>
			<?php
			$data	= array('name'=>'servicetax', 'value'=>set_value('servicetax', $servicetax), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label>Convenience charge(To Show in Customer App)</label>
			<?php
			$data	= array('name'=>'deliverycharge', 'value'=>set_value('deliverycharge', $deliverycharge), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
		
	</div>

	<div class="row">
		<div class="form-group">
			<label>Minimum order value</label>
			<?php
			$data	= array('name'=>'minordervalue', 'value'=>set_value('minordervalue', $minordervalue), 'class'=>'form-control');
			echo form_input($data); ?>
		</div>
		
	</div>
	

	
	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
	</div>
</form>