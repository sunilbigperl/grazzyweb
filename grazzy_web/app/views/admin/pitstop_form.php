<?php echo form_open_multipart($this->config->item('admin_folder').'/pitstop/form/'.$pitstop_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Restaurants</a></li>
		
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset>
				<label for="name">pitstop name</label>
				<?php
				$data	= array('name'=>'pitstop_name', 'value'=>set_value('pitstop_name', $pitstop_name), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="pitstop_address">pitstop latitude</label>
				<?php
				$data	= array('name'=>'latitude', 'value'=>set_value('latitude', $latitude), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="pitstop_address">pitstop langitude</label>
				<?php
				$data	= array('name'=>'langitude', 'value'=>set_value('langitude', $langitude), 'class'=>'span12');
				echo form_input($data);
				?>
				
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>
			</fieldset>
		</div>

		<div class="tab-pane" id="attributes_tab">
				<div class="row">
					<div class="span8">
						<label><strong>Select Restaurants</strong></label>
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
							<div class="span2">
								<select class="span4" id="product_list" size="10" style="margin:0px;"></select>
							</div>
						</div>
						<div class="row">
							<div class="span2" style="margin-top:8px;">
								<a href="#" onclick="add_related_product();return false;" class="btn" title="Add Related Product">Add Restaurants</a>
							</div>
						</div>
					</div>
					<div class="span4">
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
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="icon-trash icon-white"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}