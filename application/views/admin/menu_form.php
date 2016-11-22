<?php  echo form_open_multipart($this->config->item('admin_folder').'/menus/form/'.$menuid.'/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">Menu information</a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Categories</a></li>
		<li><a href="#seo_tab" data-toggle="tab">Image</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset>
				
				<label for="name"><?php echo lang('name');?></label>
				<?php
				$data	= array('name'=>'menu', 'value'=>set_value('menu', $menu), 'class'=>'span12');
				echo form_input($data);
				?>
				<label for="type">Type</label>
				<select name="type">
					<option value="">Select veg/nonveg</option>
					<option value="veg" <?php if($type == "veg"){echo "selected";}?>>veg</option>
					<option value="non veg" <?php if($type == "non veg"){echo "selected";}?>>non veg</option>
				</select>
				<label for="description">Price</label>
				<?php
				$data	= array('name'=>'price', 'value'=>set_value('price', $price), 'class'=>'span12');
				echo form_input($data);
				?>

				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled)); ?>
			</fieldset>
		</div>

		<div class="tab-pane" id="attributes_tab">
			
			<fieldset>
				
				<div class="row">
					<div class="span8">
						<?php if(isset($categories[0])):?>
							<label><strong><?php echo lang('select_a_category');?></strong></label>
							<table class="table table-striped">
							    <thead>
									<tr>
										<th colspan="2"><?php echo lang('name')?></th>
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
		
		<div class="tab-pane" id="seo_tab">
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

<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>

<script type="text/javascript">
$('form').submit(function() {
	$('.btn').attr('disabled', true).addClass('disabled');
});
</script>