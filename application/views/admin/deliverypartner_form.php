<?php 	if($this->auth->check_access('Admin') == 1){ $readonly=""; $disabled="";}else{$readonly = "readonly=readonly"; $disabled="disabled=disabled";}?>

<?php echo form_open($this->config->item('admin_folder').'/deliverypartner/form/'.$id); ?>
<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">BASIC DETAILS</a></li>
		
		<li><a href="#timings_tab" data-toggle="tab">TIMINGS</a></li>
		<li><a href="#charge_tab" data-toggle="tab">CHARGE DETAILS</a></li>
		
	</ul>	
	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			<fieldset style="padding:10px;">
				<div class="form-group">		
					<label>Delivery Partner</label>
					<?php
					$data	= array('name'=>'firstname', 'value'=>set_value('firstname', $firstname),'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">		
					<label>Delivery Manager Name</label>
					<?php
					$data	= array('name'=>'lastname', 'value'=>set_value('lastname', $lastname),'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">	

					<label>Username</label>

					<?php if($this->auth->check_access('Deliver manager')) { ?>
					<?php
					$data	= array('name'=>'username', 'value'=>set_value('username', $username),'class'=>'form-control', 'readonly'=>'readonly');
					echo form_input($data);
					?>
					<?php }else { ?>
					<?php
					$data	= array('name'=>'username', 'value'=>set_value('username', $username),'class'=>'form-control');
					echo form_input($data);
					?>
					<?php } ?>
				</div>
				<div class="form-group">		
					<label>Phone Number</label>
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
					<label>Next Renewal Date</label>
					<input type="date" name="NextRenewalDate" class="form-control" value="<?=$NextRenewalDate;?>">
				</div>
				<div class="form-group">	
					<input type="hidden" name="access" value="Deliver manager">
					
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
					<label for="gst">GSTIN</label>
					<input type="text" name="gst" value="<?=$gst?>" class="form-control" maxlength='15' >
					
				</div>
			</fieldset>
		</div>
		<div class="tab-pane" id="timings_tab">
			<fieldset style="padding: 10px;">
				<!--<div class="form-group " >	
					<label for="commission " >Commission(%)</label>	
					<?php
					$data	= array('name'=>'commission', 'value'=>set_value('commission', $commission), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>-->
				<!-- <div class="form-group">
					<label for="penalty " >Penalty(Rs/-)</label>
					<input type="text" name="penalty" value="<?=$penalty?>" class="form-control" <?=$readonly;?>>
				
				</div> -->
				<div class="form-group" style="display:none;">
					<label for="servicetax">Service tax(%)</label>
					<input type="text" name="servicetax" value="<?=$servicetax?>" class="form-control" <?=$readonly;?>>
					
				</div>
				
				<div class="form-group">
					<label for="fromtime">From time</label>
					
					<input type="time" name="fromtime" class="form-control" value="<?=$fromtime;?>" <?=$readonly;?>>
				</div>
				<div class="form-group">
					<label for="fromtime">To time</label>
					<input type="time" name="totime" class="form-control"  value="<?=$totime;?>" <?=$readonly;?>>
				</div>
				<div class="form-group">
					<label for="days">Days</label>
					<br/><?php $dayss = unserialize($days);  ?>
					<input type="checkbox" name="days[]" value="all" class="days" <?=$disabled;?> onchange="checkAll(this)" <?php if(is_array($dayss) && in_array('all',$dayss)){ echo "checked=checked"; } ?>> All<br>
					<input type="checkbox" name="days[]" value="sunday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('sunday',$dayss)){ echo "checked=checked"; } ?>> Sunday<br>
					<input type="checkbox" name="days[]" value="monday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('monday',$dayss)){ echo "checked=checked"; } ?>> Monday<br>
					<input type="checkbox" name="days[]" value="tuesday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('tuesday',$dayss)){ echo "checked=checked"; } ?>> Tuesday<br>
					<input type="checkbox" name="days[]" value="wednesday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('wednesday',$dayss)){ echo "checked=checked"; } ?>> Wednesday<br>
					<input type="checkbox" name="days[]" value="thursday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('thursday',$dayss)){ echo "checked=checked"; } ?>> Thursday<br>
					<input type="checkbox" name="days[]" value="friday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('friday',$dayss)){ echo "checked=checked"; } ?>> Friday<br>
					<input type="checkbox" name="days[]" value="saturday" class="days" <?=$disabled;?> <?php if(is_array($dayss) && in_array('saturday',$dayss)){ echo "checked=checked"; } ?>> Saturday<br>
				</div>
				<script>
					function checkAll(ele) {
						 var checkboxes = document.getElementsByTagName('input');
						 if (ele.checked) {
							 for (var i = 0; i < checkboxes.length; i++) {
								 if (checkboxes[i].type == 'checkbox') {
									 checkboxes[i].checked = true;
								 }
							 }
						 } else {
							 for (var i = 0; i < checkboxes.length; i++) {
								 if (checkboxes[i].type == 'checkbox') {
									 checkboxes[i].checked = false;
								 }
							 }
						 }
					 }
				
				</script>
								<?php if(!$this->auth->check_access('Deliver manager')){  ?>
				<div class="form-group">	
					<label for="enabled"><?php echo lang('enabled');?> </label>
					<?php echo form_dropdown('enabled', array(''=>'select','1' => lang('enabled'),'0' => lang('disabled')), set_value('enabled',$enabled),'class="form-control" id="enableddata"'); ?>
				</div>
				<div class="form-group" id="datetime" style="<?php if($enabled == 1){ echo 'display:none'; }?>">
						<label><strong>Disabled From date</strong></label>
						<input type="date" name="FromDate" id="FromDate" value="<?=$FromDate;?>" <?=$readonly;?>>
						<label><strong>Disabled To date</strong></label>
						<input type="date" name="ToDate" id="ToDate" value="<?=$ToDate;?>" <?=$readonly;?>>
				</div>
				<?php } ?>
				
				
			</fieldset>
		</div>
		<div class="tab-pane" id="charge_tab">
		<div class="">
				
					 <form method="post" name="ExternalContactValueForm">
					  <table class="table table-bordered" style="background:white;">
					  <div class="form-group">
					<label for="penalty " >Penalty (Rupees)</label>
					<input type="text" name="penalty" value="<?=$penalty?>" class="form-control" <?=$readonly;?>>
				
				</div>
				<?php if($this->auth->check_access('Admin')){  ?>
			<div class="col-sm-1 pull-right">
				<input type="button" class="btn btn-primary" name="AddNewRow" id="AddNewRow"  value="Add new" <?=$disabled;?>>
				<?php } ?>
			</div>

						<thead>

							<tr>
								<th>Sr No</th>
								<th>From Km</th>
								<th>To Km</th>
								<th>Charge (Rupees)</th>
								
							</tr>
						</thead>
						<tbody id="ValueList">
						<?php if(isset($ListValues) && $ListValues != ""){ ?>
							<?php $i=1; foreach($ListValues['data'] as $value){ ?>
								<tr>
									<td><?=$i;?><input type="hidden" name="values[<?=$i?>][delpartner_id]" value="<?=$id?>" <?=$readonly;?>></td>
									<td><input type="text" class="form-control" name="values[<?=$i?>][fromKm]" value="<?=$value->fromKm;?>" <?=$readonly;?>></td>
									<td><input type="text" class="form-control" name="values[<?=$i?>][toKm]" value="<?=$value->toKm;?>" <?=$readonly;?>></td>
									<td><input type="text" class="form-control" name="values[<?=$i?>][rate]" value="<?=$value->rate;?>" <?=$readonly;?>></td>
								</tr>
						<?php $i++; } }?>
						</tbody>
						
					</table>
					</form>
				
			</div>
		
		</div>
		<div class="form-group">		
			<div class="form-actions">
				<input class="btn btn-primary" type="submit" value="<?php echo lang('save');?>"/>
			</div>
		</div>
	</div>
</form>
<?php $this->load->view('admin/delpartnermarque'); ?>
<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
$(document).ready(function(){
	$("#enableddata").change(function(){
		if($(this).val() == "0"){
			$("#datetime").css('display','block');
		}
	});

	$('#AddNewRow').click(function(){ 
		var count = $('#ValueList').children('tr').length +1;
		
		$('#ValueList').append('<tr><td>'+(count)+'</td><td><input type="text" class="form-control" name="values['+count+'][fromKm]" value=""></td><td><input type="text" class="form-control" name="values['+count+'][toKm]" value=""></td><td><input type="text" class="form-control" name="values['+count+'][rate]" value=""></td></tr>');
	});

});
</script>
