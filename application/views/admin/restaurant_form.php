<?php echo form_open_multipart($this->config->item('admin_folder').'/restaurant/form/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Image</a></li>
		<li><a href="#pitstop_tab" data-toggle="tab">Pitstop</a></li>
		<li><a href="#location_tab" data-toggle="tab">Location</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset>
				<label for="name">Restaurant name</label>
				<?php
				$data	= array('name'=>'restaurant_name', 'value'=>set_value('restaurant_name', $restaurant_name), 'class'=>'span6');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant address</label>
				<?php
				$data	= array('name'=>'restaurant_address', 'class'=>'', 'value'=>set_value('restaurant_address', $restaurant_address));
				echo form_textarea($data);
				?>
				
				<label for="restaurant_address">Restaurant phone</label>
				<?php
				$data	= array('name'=>'restaurant_phone', 'value'=>set_value('restaurant_phone', $restaurant_phone), 'class'=>'span6');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant email</label>
				<?php
				$data	= array('name'=>'restaurant_email', 'value'=>set_value('restaurant_email', $restaurant_email), 'class'=>'span6');
				echo form_input($data);
				?>
				
				<label for="restaurant_address">Restaurant branch</label>
				<?php
				$data	= array('name'=>'restaurant_branch', 'value'=>set_value('restaurant_branch', $restaurant_branch), 'class'=>'span6');
				echo form_input($data);
				?>
				
				<label for="restaurant_manager">Restaurant manager</label>
				<select name="restaurant_manager" class="form-control span6">
					<option value="">Select Restaurant manager</option>
					<?php foreach($managers as $manager){
						if($restaurant_manager == $manager->id){$select="selected";}else{$select="";}?>
						<option value="<?=$manager->id?>" <?=$select;?>><?=$manager->username;?></select>
					<?php } ?>
				</select>
				
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>
				
				<label for="preparation_time">Preparation time(In mins)</label>
        		<?php
				$data	= array('name'=>'preparation_time', 'value'=>set_value('preparation_time', $preparation_time), 'class'=>'span6');
				echo form_input($data);
				?>
				
				<label for="commission">Commission(%)</label>
				<?php
				$data	= array('name'=>'commission', 'value'=>set_value('commission', $commission), 'class'=>'span6');
				echo form_input($data);
				?>
				<label for="penalty">Penalty(%)</label>
				<?php
				$data	= array('name'=>'penalty', 'value'=>set_value('penalty', $penalty), 'class'=>'span6');
				echo form_input($data);
				?>
				<label for="servicetax">Service tax(%)</label>
				<?php
				$data	= array('name'=>'servicetax', 'value'=>set_value('servicetax', $servicetax), 'class'=>'span6');
				echo form_input($data);
				?>
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
		
		<div class="tab-pane" id="pitstop_tab">
				<div class="row">
					<div class="span8">
						<label><strong>Select pitstops</strong></label>
					</div>
				</div>
				<div class="row">
					<div class="span4" style="text-align:center">
						<div class="row">
							<div class="span2">
								<input class="span2" type="text" id="product_search" />
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
							<div class="span2">
								<select class="span4" id="product_list" size="10" style="margin:0px;"></select>
							</div>
						</div>
						<div class="row">
							<div class="span2" style="margin-top:8px;">
								<a href="#" onclick="add_related_product();return false;" class="btn" title="Add Related Product">Add Pitstop</a>
							</div>
						</div>
					</div>
					<div class="span4">
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
			
				<label for="restaurant_address">Restaurant latitude</label>
				<input type="text" class="form-control" value="<?=$restaurant_latitude;?>" name="restaurant_latitude" id="lat">
				
				
				<label for="restaurant_address">Restaurant langitude</label>
				<input type="text" class="form-control" value="<?=$restaurant_langitude;?>" name="restaurant_langitude" id="lng">
				
			<div id="map_canvas" style="width:500px;height:500px;" class="col-sm-8"></div>	
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
     zoom: 6,
     center: myLatlng,
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
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="icon-trash icon-white"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}