<?php
if(isset($entry_name)){ $entry_name	= array('name'=>'entry_name','class'=>'form-control', 'value'=> set_value('entry_name',$entry_name)); }else{ $entry_name	= "";}
if(isset($company)){ $f_company	= array('name'=>'company','class'=>'form-control', 'value'=> set_value('company',$company)); }else{ $f_company	= "";}
if(isset($address1)){ $f_address1	= array('name'=>'address1', 'class'=>'form-control','value'=>set_value('address1',$address1)); }else{ $f_address1 = ""; }
if(isset($address2)) { $f_address2	= array('name'=>'address2', 'class'=>'form-control','value'=> set_value('address2',$address2)); } else{ $f_address2	= ""; }
if(isset($firstname)){ $f_first	= array('name'=>'firstname', 'class'=>'form-control','value'=> set_value('firstname',$firstname)); }else{ $f_first = ""; }
if(isset($lastname)){	$f_last		= array('name'=>'lastname', 'class'=>'form-control','value'=> set_value('lastname',$lastname)); }else{ $f_last = ""; }
if(isset($email)){ $f_email	= array('name'=>'email', 'class'=>'form-control','value'=>set_value('email',$email)); }else{ $f_email = ""; }
if(isset($phone)){ $f_phone	= array('name'=>'phone', 'class'=>'form-control','value'=> set_value('phone',$phone)); }else{ $f_phone = ""; }
if(isset($city)){ $f_city		= array('name'=>'city','class'=>'form-control', 'value'=>set_value('city',$city)); }else{ $f_city = ""; }
if(isset($zip)){ $f_zip		= array('maxlength'=>'10', 'class'=>'form-control', 'name'=>'zip', 'value'=> set_value('zip',$zip)); }else{ $f_zip = ""; }
?>
<?php echo form_open($this->config->item('admin_folder').'/customers/address_form/'.$customer_id.'/'.$id);?>

	<div class="row">
		<div class="form-group">
			<label>Entry Name</label>
			<?php echo form_input($entry_name);?>
		</div>
	</div>
	
	<div class="row">
		<div class="form-group">
			<label>Office</label>
			<?php echo form_input($f_company);?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('firstname');?></label>
			<?php echo form_input($f_first);?>
		</div>
		<div class="form-group">
			<label><?php echo lang('lastname');?></label>
			<?php echo form_input($f_last);?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('email');?></label>
			<?php echo form_input($f_email);?>
		</div>
		<div class="form-group">
			<label><?php echo lang('phone');?></label>
			<?php echo form_input($f_phone);?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('country');?></label>
			<?php echo form_dropdown('country_id', $countries_menu, set_value('country_id', $country_id), 'id="f_country_id" class="form-control"');?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<label><?php echo lang('address');?></label>
			<?php echo form_input($f_address1);?>
		</div>
	</div>

	<div class="row">
		<div class="form-group">
			<?php echo form_input($f_address2);?>
		</div>
	</div>

	<div class="row">
		<div class="span2">
			<label><?php echo lang('city');?></label>
			<?php echo form_input($f_city);?>
		</div>
	
		<div class="span1">
			<label><?php echo lang('zip');?></label>
			<?php echo form_input($f_zip);?>
		</div>
	</div>

	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
	</div>
	<script type="text/javascript">
	$(document).ready(function(){
		$('#f_country_id').change(function(){
			$.post('<?php echo site_url(config_item('admin_folder').'/locations/get_zone_menu');?>',{id:$('#f_country_id').val()}, function(data) {
			  $('#f_zone_id').html(data);
			});
	
		});
	});
	</script>
</form>