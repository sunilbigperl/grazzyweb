<?php  echo form_open_multipart($this->config->item('admin_folder').'/menus/form/'.$menuid.'/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">Menu information</a></li>
		
		<li><a href="#seo_tab" data-toggle="tab" style="display:none;">Image</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset style="padding:20px;">
			<div class="col-sm-6">
			<div class="form-group">		
				<label for="name"><?php echo lang('name');?></label>
				<?php
				$data	= array('name'=>'menu', 'value'=>set_value('menu', $menu), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">
				<label for="name">Menu code</label>
				<?php
				$data	= array('name'=>'code', 'value'=>set_value('code', $code), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">	
				<label for="type">Type</label>
				<select name="type" class="form-control">
					<option value="">Select veg/nonveg</option>
					<option value="veg" <?php if($type == "veg"){echo "selected";}?>>veg</option>
					<option value="non veg" <?php if($type == "non veg"){echo "selected";}?>>non veg</option>
				</select>
			</div>
			<div  class="form-group" style="display:none;">
				<label for="size">Size</label>
				<select name="size" class="form-control">
					<option value="">Select size</option>
					<option value="Small" <?php if($size == "Small"){echo "selected";}?>>Small</option>
					<option value="Medium" <?php if($size == "Medium"){echo "selected";}?>>Medium</option>
					<option value="Large" <?php if($size == "Large"){echo "selected";}?>>Large</option>
				</select>
			</div>
			<div class="form-group">
				<label for="price">Price</label>
				<?php
				$data	= array('name'=>'price', 'value'=>set_value('price', $price), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">
				<label for="name">Item preparation time(In mins)</label>
				<select class="form-control" name="itemPreparation_time">
					<option value="1" <?php if($itemPreparation_time == 1){echo "selected=selected"; }?>>1</option>
					<option value="2" <?php if($itemPreparation_time == 2){echo "selected=selected"; }?>>2</option>
					<option value="5" <?php if($itemPreparation_time == 5){echo "selected=selected"; }?>>5</option>
					<option value="10" <?php if($itemPreparation_time == 10){echo "selected=selected"; }?>>10</option>
					<option value="12" <?php if($itemPreparation_time == 12){echo "selected=selected"; }?>>12</option>
					<option value="15" <?php if($itemPreparation_time == 15){echo "selected=selected"; }?>>15</option>
					<option value="18" <?php if($itemPreparation_time == 18){echo "selected=selected"; }?>>18</option>
					<option value="20" <?php if($itemPreparation_time == 20){echo "selected=selected"; }?>>20</option>
					<option value="25" <?php if($itemPreparation_time == 25){echo "selected=selected"; }?>>25</option>
					<option value="30" <?php if($itemPreparation_time == 30){echo "selected=selected"; }?>>30</option>
				</select>
				
			</div>
			<div class="form-group">
				<label for="description">Description</label>
				<textarea name="description" class="form-control"><?=$description;?></textarea>
			</div>
			<div class="form-group">	
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled),'class=form-control'); ?>
			</div>
			</div>
			<div class="col-sm-6">
				<div class="col-sm-8">
						<?php if(isset($categories[0])):?>
							<label><strong><?php echo lang('select_a_category');?></strong></label>
							<table class="table table-striped">
							    <thead>
									<tr>
										<th colspan="2">Categories</th>
									</tr>
								</thead>
								
							<?php
							function list_categories($parent_id, $cats, $sub='', $product_categories) { ?>
								
							<?php	foreach ($cats[$parent_id] as $cat):?>
								<tr>
									<td><?php echo  $sub.$cat->name; ?></td>
									<td>
										<input type="checkbox" name="categories[]"  id="Operator<?php  echo $cat->id; ?>" value="<?php echo $cat->id;?>" <?php echo(in_array($cat->id, $product_categories))?'checked="checked"':'';?>/>
									</td>
								</tr>
								<?php
								if (isset($cats[$cat->id]) && sizeof($cats[$cat->id]) > 0)
								{
									$sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
										$sub2 .=  '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
									list_categories($cat->id, $cats, $sub2, $product_categories);
								}
								endforeach;
							}
							
						
							list_categories(0, $categories, '', $product_categories);
						
							?>
							
						</table>
					<?php else:?>
						<div class="alert"><?php echo lang('no_available_categories');?></div>
					<?php endif;?>
					</div>
				
			</div>
			</fieldset>
		</div>

		
		
		<div class="tab-pane" id="seo_tab" >
			<fieldset>
				<label for="image"><?php echo lang('image');?> </label>
				<div class="input-append">
					<?php echo form_upload(array('name'=>'image'));?><span class="add-on"><?php echo lang('max_file_size');?> <?php echo  $this->config->item('size_limit')/1024; ?>kb</span>
				</div>
					
				<?php if($menu_id && $image != ''):?>
				
				<div style="text-align:center; padding:5px; border:1px solid #ddd;"><img src="<?php echo base_url('uploads/images/small/'.$image);?>" alt="current"/><br/><?php echo lang('current_file');?></div>
				
				<?php endif;?>
				
			</fieldset>
		</div>
	</div>

</div>

<div class="form-actions pull-right">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
</script>