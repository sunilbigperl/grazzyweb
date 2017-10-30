<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>
<?php echo form_open_multipart($this->config->item('admin_folder').'/pitstop/form/'.$pitstop_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Restaurants</a></li>
		
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset style="padding:10px">
			<div class="form-group">	
				<label for="name">Delivery Point name</label>
				<?php
				$data	= array('name'=>'pitstop_name', 'value'=>set_value('pitstop_name', $pitstop_name), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">	
				<label for="pitstop_address">Delivery Point latitude</label>
				<?php
				$data	= array('name'=>'latitude', 'value'=>set_value('latitude', $latitude), 'class'=>'form-control', 'id'=>"lat");
				echo form_input($data);
				?>
			</div>
			<div class="form-group">	
				<label for="pitstop_address">Delivery Point langitude</label>
				<?php
				$data	= array('name'=>'langitude', 'value'=>set_value('langitude', $langitude), 'class'=>'form-control',  'id'=>"lng");
				echo form_input($data);
				?>
			</div>
			<!-- <div class="form-group">
				<label for="pitstop_address">Address</label>
				<textarea name="address" required ><?=$address;?></textarea>
			</div> -->
			<!-- <div class="form-group">
				<label for="restaurant_address">Pitstop City</label>
				
				<select name="city" class="form-control" required>
					<option value="">Select city</option>
					<option value="Mumbai" <?php if($city == "Mumbai"){ echo "selected"; } ?>>Mumbai</option>
					<option value="Bangalore" <?php if($city == "Bangalore"){ echo "selected"; } ?>>Bangalore</option>
					<option value="Delhi" <?php if($city == "Delhi"){ echo "selected"; } ?>>Delhi</option>
					<option value="NCR" <?php if($city == "NCR"){ echo "selected"; } ?>>NCR</option>
					<option value="Chennai" <?php if($city == "Chennai"){ echo "selected"; } ?>>Chennai</option>
					<option value="Hyderabad" <?php if($city == "Hyderabad"){ echo "selected"; } ?>>Hyderabad</option>
					<option value="Ahmedabad" <?php if($city == "Ahmedabad"){ echo "selected"; } ?>>Ahmedabad</option>
					<option value="Pune" <?php if($city == "Pune"){ echo "selected"; } ?>>Pune</option>
					<option value="Kolkata" <?php if($city == "Kolkata"){ echo "selected"; } ?>>Kolkata</option>
					<option value="Surat" <?php if($city == "Surat"){ echo "selected"; } ?>>Surat</option>
					<option value="Baroda" <?php if($city == "Baroda"){ echo "selected"; } ?>>Baroda</option>
				</select>
			
			</div> -->


          <div class="form-group">
				<label for="restaurant_address">Delivery Point City</label>
				<select name="city" class="form-control" required>

					<!-- <option value="">Select city</option> -->

					<?php foreach($getpitstop as $class):?>
						
                     <option value="<?php echo $class['city'];?>" <?php if($class['city']==$city){echo "selected";}?>><?php echo $class['city'];?> 
                     </option>
                      <?php endforeach;?> 
                      </select>
                    
			     </div> 

			   
			<div class="form-group">	
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled), 'class=form-control'); ?>
			</div>
			<div class="form-group">
				<div id="map_canvas" style="width:500px;height:500px;" class="col-sm-8"></div>	
<?php  
$lat='';
$lon='';
if(isset($latitude) && $latitude !='' ) {
	$lat=$latitude;
}
else {
	 $lat=54.95869420484606;
}
if(isset($langitude) && $langitude !='' ) {
	$lon=$langitude;
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
     zoom: 4,
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
			</div>
			</fieldset>
		</div>

		<div class="tab-pane" id="attributes_tab"  style="padding:10px">
				<div class="row">
					<div class="col-sm-8">
						<label><strong>Select Restaurants</strong></label>
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
									$.post("<?php echo site_url($this->config->item('admin_folder').'/pitstop/restaurants_autocomplete/');?>", { name: $('#product_search').val(), limit:10},
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
								<a href="#" onclick="add_related_product();return false;" class="btn btn-primary" title="Add Related Product">Add Restaurants</a>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<table class="table table-striped" style="margin-top:10px;">
							<tbody id="product_items_container">
							<?php
							foreach($related_restaurants as $rel)
							{
								echo related_items($rel->restaurants_id, $rel->restaurant_name);
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
			
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
					<input type="hidden" name="related_restaurants[]" value="'.$id.'"/>
					'.$name.'</td>
				<td>
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="fa fa-trash"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}