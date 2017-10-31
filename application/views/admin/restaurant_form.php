<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>

<?php echo form_open_multipart($this->config->item('admin_folder').'/restaurant/form/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		<li><a href="#timings_tab" data-toggle="tab">Timings</a></li>
		<?php if($this->auth->check_access('Admin')) {?>
		<li><a href="#attributes_tab" data-toggle="tab">Image</a></li>
		<?php } ?> 
		<li><a href="#location_tab" data-toggle="tab">Location</a></li>
		<li><a href="#preparation_tab" data-toggle="tab">Restaurant Manager</a></li>
		<li><a href="#charge_tab" data-toggle="tab">Charges</a></li>
		<?php if($this->auth->check_access('Admin')) {?>
		<li><a href="#pt_tab" data-toggle="tab">Preparation Time</a></li>
		<?php } ?>
	</ul>
	

	<div class="tab-content">
		
		<div class="tab-pane active" id="description_tab">
			<fieldset style="padding:10px;">
				<div class="form-group">	
					<label for="name">Restaurant name</label>
					<input type="text" name="restaurant_name" value="<?=$restaurant_name?>" class="form-control" required>
					
				</div>
				<div class="form-group">	
					<label for="restaurant_address">Restaurant address</label>
					<?php
					$data	= array('name'=>'restaurant_address', 'class'=>'', 'value'=>set_value('restaurant_address', $restaurant_address));
					echo form_textarea($data);
					?>
				</div>
				<div class="form-group">
					<label for="restaurant_address">Restaurant phone</label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">+91</span>
						
						<input type="number" name="restaurant_phone" value="<?=$restaurant_phone?>" class="form-control"  pattern="/(7|8|9)\d{9}/" required>
					</div>
				</div>
				
				<div class="form-group">	
					<label for="restaurant_address">Restaurant mobile</label>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">+91</span>
						
					<input type="number" name="restaurant_mobile" value="<?=$restaurant_mobile?>" class="form-control"  pattern="/(7|8|9)\d{9}/" required>
					</div>
				</div>
				<div class="form-group">	
					<label for="restaurantmanager_mobile">Restaurant Manager mobile number</label>
					<div class="input-group">
						
						<input type="number" name="restaurantmanager_mobile" value="<?=$restaurantmanager_mobile?>" class="form-control"  pattern="/(7|8|9)\d{9}/" required>
					</div>
				</div>
				<div class="form-group">
					<label for="restaurant_address">Restaurant email</label>
					<?php
					$data	= array('name'=>'restaurant_email', 'value'=>set_value('restaurant_email', $restaurant_email), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<!-- <div class="form-group">
					<label for="restaurant_address">City</label>
					
					<select name="restaurant_branch" class="form-control" required>
						<option value="">Select City</option>
						<?php foreach($getcity as $city):?>
				    <option value="<?php echo $city->city;?>"><?php echo $city->city;?> 
                     </option>
                      <?php endforeach;?>
					</select> 
</div>-->


<div class="form-group">
				<label for="restaurant_address">City</label>
				<select name="city" class="form-control" required>

					<!-- <option value="">Select city</option> -->

					<?php foreach($getcity as $class):?>
						
                     <option value="<?php echo $class['city'];?>" <?php if($class['city']==$restaurant_branch){echo "selected";}?>><?php echo $class['city'];?> 
                     </option>
                      <?php endforeach;?> 
                      </select>
                    
			     </div> 




				<?php if($this->auth->check_access('Admin')) {?>
				<div class="form-group">	
					<label for="enabled"><?php echo lang('enabled');?> </label>
					<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled),'class="form-control"'); ?>
				</div>
				<?php } ?>

				<div class="form-group">	
					<label for="gst">GSTIN</label>
					<input type="text" name="gst" value="<?=$gst?>" class="form-control" maxlength='15'  required>
					
				</div>
			</fieldset>
		</div>
		
		<div class="tab-pane" id="timings_tab">
			<fieldset style="padding: 10px;">
			
				<div class="form-group">
					<label for="fromtime">From time (as per the availability of the delivery partner)</label>
					<input type="time" name="fromtime" class="form-control" value="<?=$fromtime;?>">
				</div>
				<div class="form-group">
					<label for="fromtime">To time (as per the availability of the delivery partner)</label>
					<input type="time" name="totime" class="form-control"  value="<?=$totime;?>">
				</div>
                 <?php if($this->auth->check_access('Admin')) {?>
                <div class="form-group">	
					<label for="comment">Comment</label>
					<?php
					$data= array('name'=>'comment', 'class'=>'','rows' => '5','cols'=> '10', 
						'style' => 'width:50%','value'=>set_value('comment', $comment));
					echo form_textarea($data);
					?>
				</div>
                 <?php } ?>
				<div class="form-group">
					<label for="days">Days</label>
					<br/><?php $dayss = unserialize($days);  ?>
					<input type="checkbox" name="days[]" value="all" class="days" onchange="checkAll(this)" <?php if(is_array($dayss) && in_array('all',$dayss)){ echo "checked=checked"; } ?>> All<br>
					<input type="checkbox" name="days[]" value="sunday" class="days" <?php if(is_array($dayss) && in_array('sunday',$dayss)){ echo "checked=checked"; } ?>> Sunday<br>
					<input type="checkbox" name="days[]" value="monday" class="days" <?php if(is_array($dayss) && in_array('monday',$dayss)){ echo "checked=checked"; } ?>> Monday<br>
					<input type="checkbox" name="days[]" value="tuesday" class="days" <?php if(is_array($dayss) && in_array('tuesday',$dayss)){ echo "checked=checked"; } ?>> Tuesday<br>
					<input type="checkbox" name="days[]" value="wednesday" class="days" <?php if(is_array($dayss) && in_array('wednesday',$dayss)){ echo "checked=checked"; } ?>> Wednesday<br>
					<input type="checkbox" name="days[]" value="thursday" class="days" <?php if(is_array($dayss) && in_array('thursday',$dayss)){ echo "checked=checked"; } ?>> Thursday<br>
					<input type="checkbox" name="days[]" value="friday" class="days" <?php if(is_array($dayss) && in_array('friday',$dayss)){ echo "checked=checked"; } ?>> Friday<br>
					<input type="checkbox" name="days[]" value="saturday" class="days" <?php if(is_array($dayss) && in_array('saturday',$dayss)){ echo "checked=checked"; } ?>> Saturday<br>
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
			</fieldset>
		</div>
		
		<div class="tab-pane" id="attributes_tab">
			
			<fieldset style="padding: 10px;">
				<div class="form-group">
					<label for="image"><?php echo lang('image');?> </label>
					<div class="input-append">
						<?php echo form_upload(array('name'=>'image','class'=>'form-control'));?><span class="add-on"><?php echo lang('max_file_size');?> <?php echo  $this->config->item('size_limit')/1024; ?>kb</span>
					</div>
				</div>
				<div class="form-group">		
					<?php if($restaurant_id && $image != ''):?>
					
					<div style="text-align:center; padding:5px; border:1px solid #ddd;"><img src="<?php echo base_url('uploads/images/small/'.$image);?>" alt="current"/><br/><?php echo lang('current_file');?></div>
					
					<?php endif;?>
				</div>	
			</fieldset>
			
		</div>
		
		<div class="tab-pane" id="pitstop_tab" style="padding: 10px;">
				<div class="row">
					<div class="col-sm-8">
						<label><strong>Select pitstops</strong></label>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4" style="text-align:center">
						<div class="row">
							<div class="form-group">
								<input class="form-control" type="text" id="product_search" />
								<script type="text/javascript">
								$('#product_search').keyup(function(){
									$('#product_list').html('');
									run_product_query();
								});
						
								function run_product_query()
								{
									$.post("<?php echo site_url($this->config->item('admin_folder').'/restaurant/pitstops_autocomplete/');?>", { name: $('#product_search').val(), limit:10},
										function(data) {
									
											$('#product_list').html('');
									
											$.each(data, function(index, value){
									
												if($('#related_product_'+index).length == 0)
												{
													$('#product_list').append('<option id="product_item_'+index+'" value="'+index+'">'+value+'</option>');
												}
											});
									
									}, 'json');
								}
								</script>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<select class="form-control" id="product_list" size="10" style="margin:0px;"></select>
							</div>
						</div>
						<div class="row">
							<div class="form-group" style="margin-top:8px;">
								<a href="#" onclick="add_related_product();return false;" class="btn btn-primary" title="Add Related Product">Add Pitstop</a>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<table class="table table-striped" style="margin-top:10px;">
							<tbody id="product_items_container">
							<?php 
							foreach($related_pitstops as $rel)
							{
								echo related_items($rel->pitstop_id, $rel->pitstop_name);
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
		</div>
	
		<div class="tab-pane" id="location_tab">
			<fieldset style="padding: 10px;">
				<label for="restaurant_address">Restaurant latitude</label>
				<input type="text" class="form-control" value="<?=$restaurant_latitude;?>" name="restaurant_latitude" id="lat">
				
				
				<label for="restaurant_address">Restaurant longitude</label>
				<input type="text" class="form-control" value="<?=$restaurant_langitude;?>" name="restaurant_langitude" id="lng">
				
				<!-- <div id="map_canvas" style="width:500px;height:500px;" class="col-sm-8"></div> -->	
			</fieldset>
		</div>
	
		<div class="tab-pane" id="charge_tab">
			<fieldset style="padding: 10px;">
			
				 <?php if($this->auth->check_access('Restaurant manager')){ ?>
				<div class="form-group " >	
					<label for="commission " >Commission (%)</label>	
					<?php
					$data	= array('name'=>'commission', 'value'=>set_value('commission', $commission), 'class'=>'form-control', 'readonly'=>'readonly');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="penalty " >Penalty (%)</label>
					<?php
					$data	= array('name'=>'penalty', 'value'=>set_value('penalty', $penalty), 'class'=>'form-control','readonly'=>'readonly');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="penalty " >Reimbursement of delivery charges</label>
					<?php
					$data	= array('name'=>'delivery_charge', 'value'=>set_value('delivery_charge', $delivery_charge), 'class'=>'form-control','readonly'=>'readonly');
					echo form_input($data);
					?>
				</div>
				<div class="form-group" style="display:none;">
					<label for="servicetax">Service tax(%)</label>
					<?php
					$data	= array('name'=>'servicetax', 'value'=>set_value('servicetax', $servicetax), 'class'=>'form-control','readonly'=>'readonly');
					echo form_input($data);
					?>
				</div> 
				<?php } else {?>
				<div class="form-group " >	
					<label for="commission " >Commission(%)</label>	
					<?php
					$data	= array('name'=>'commission', 'value'=>set_value('commission', $commission), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="penalty " >Penalty(Rs)</label>
					<?php
					$data	= array('name'=>'penalty', 'value'=>set_value('penalty', $penalty), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="Reimb " >Reimbursement of delivery charges(Rs)</label>
					<?php
					$data	= array('name'=>'Reimb', 'value'=>set_value('Reimb', $Reimb), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="discount1 " >Discount(%)</label>
					<?php
					$data	= array('name'=>'discount1', 'value'=>set_value('discount1', $discount1), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">
					<label for="discount2 " >Discount(Rs)</label>
					<?php
					$data	= array('name'=>'discount2', 'value'=>set_value('discount2', $discount2), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group" style="display:none;">
					<label for="penalty " >Delivery Charges</label>
					<?php
					$data	= array('name'=>'delivery_charge', 'value'=>set_value('delivery_charge', $delivery_charge), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group" style="display:none;">
					<label for="servicetax">Service tax(%)</label>
					<?php
					$data	= array('name'=>'servicetax', 'value'=>set_value('servicetax', $servicetax), 'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<?php } ?>
				
			</fieldset>
		</div>
		
		<div class="tab-pane" id="preparation_tab">
			<fieldset style="padding:10px;">
				<div class="form-group">	
					<label>Restaurant manager name</label>
					<?php
					$data	= array('name'=>'firstname', 'value'=>set_value('firstname', $firstname),'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">	
					<label>Manager user name</label>
					<?php
					$data	= array('name'=>'username', 'value'=>set_value('username', $username),'class'=>'form-control');
					echo form_input($data);
					?>
				</div>
				<div class="form-group">	
					<input type="hidden" name="access" value="">
					
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
					<label>Next Renewal Date</label>
					<input type="date" name="NextRenewalDate" class="form-control" value="<?=$NextRenewalDate;?>">
				</div>
	
			</fieldset>
		</div>
		 <?php if($this->auth->check_access('Admin')) {?>
		<div class="tab-pane" id="pt_tab">
			<fieldset style="padding:10px;">
				<!-- <div class="form-group">	
					<label for="preparation_time">Cutoff Preparation time(In mins)</label>
					<select class="form-control" name="preparation_time">
					<option value="1" <?php if($preparation_time == 1){echo "selected=selected"; }?>>1</option>
					<option value="2" <?php if($preparation_time == 2){echo "selected=selected"; }?>>2</option>
					<option value="5" <?php if($preparation_time == 5){echo "selected=selected"; }?>>5</option>
					<option value="10" <?php if($preparation_time == 10){echo "selected=selected"; }?>>10</option>
					<option value="12" <?php if($preparation_time == 12){echo "selected=selected"; }?>>12</option>
					<option value="15" <?php if($preparation_time == 15){echo "selected=selected"; }?>>15</option>
					<option value="18" <?php if($preparation_time == 18){echo "selected=selected"; }?>>18</option>
					<option value="20" <?php if($preparation_time == 20){echo "selected=selected"; }?>>20</option>
					<option value="25" <?php if($preparation_time == 25){echo "selected=selected"; }?>>25</option>
					<option value="30" <?php if($preparation_time == 30){echo "selected=selected"; }?>>30</option>
				</select>
				
				</div> -->
				<div class="form-group">	
					<label for="preparation_time">Cutoff Preparation time(In mins)</label>
					<input type="number" name="preparation_time" value="<?=$preparation_time?>" class="form-control" required>
					
				</div>
				<?php } ?>
			</fieldset>
		</div>
	</div>

</div>

<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<?php  
$lat='';
$lon='';
if(isset($restaurant_latitude) && $restaurant_latitude !='' ) {
	$lat=$restaurant_latitude;
}
else {
	 $lat=54.95869420484606;
}
if(isset($restaurant_langitude) && $restaurant_langitude!='' ) {
	$lon=$restaurant_langitude;
}
else {
	$lon=-2.7575678906250687;
}

?>
<script type="text/javascript">
 var map;
jQuery(document).ready(function() {
  var myLatlng = new google.maps.LatLng(<?php echo $lat;  ?>,<?php echo $lon; ?>);

  var myOptions = {
     zoom: 10,
     center: myLatlng,
	 center: new google.maps.LatLng(19.0760, 72.8777),
     mapTypeId: google.maps.MapTypeId.ROADMAP
   }
  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions); 

  var marker = new google.maps.Marker({
  draggable: true,
  position: myLatlng, 
  map: map,
  title: "Your location"
  });

google.maps.event.addListener(marker, 'dragend', function (event) {

 document.getElementById("lat").value = this.getPosition().lat();
    document.getElementById("lng").value = this.getPosition().lng();

 });

});
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKUsjUabpe8-dBedcqnKchPAVfsNqFnlE"></script>
<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
</script>
<script type="text/javascript">

function add_related_product()
{
	//if the related product is not already a related product, add it
	if($('#related_product_'+$('#product_list').val()).length == 0 && $('#product_list').val() != null)
	{
		<?php $new_item	 = str_replace(array("\n", "\t", "\r"),'',related_items("'+$('#product_list').val()+'", "'+$('#product_item_'+$('#product_list').val()).html()+'"));?>
		var related_product = '<?php echo $new_item;?>';
		$('#product_items_container').append(related_product);
		run_product_query();
	}
	else
	{
		if($('#product_list').val() == null)
		{
			alert('<?php echo lang('alert_select_product');?>');
		}
		else
		{
			alert('<?php echo lang('alert_product_related');?>');
		}
	}
}

function remove_related_product(id)
{
	if(confirm('<?php echo lang('confirm_remove_related');?>'))
	{
		$('#related_product_'+id).remove();
		run_product_query();
	}
}

function photos_sortable()
{
	$('#gc_photos').sortable({	
		handle : '.gc_thumbnail',
		items: '.gc_photo',
		axis: 'y',
		scroll: true
	});
}
//]]>
</script>
<?php
function related_items($id, $name) {
	return '
			<tr id="related_product_'.$id.'">
				<td>
					<input type="hidden" name="related_pitstops[]" value="'.$id.'"/>
					'.$name.'</td>
				<td>
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="fa fa-trash"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}