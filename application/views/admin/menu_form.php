<link href="http://localhost/gocart/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="http://localhost/gocart/assets/css/redactor.css" rel="stylesheet" />

<script type="text/javascript" src="http://localhost/gocart/assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="http://localhost/gocart/assets/js/redactor.min.js"></script>
<?php $GLOBALS['option_value_count'] = 0;?>
<style type="text/css">
	.container, .navbar-static-top .container, .navbar-fixed-top .container, .navbar-fixed-bottom .container{width:100%;}
	.sortable { list-style-type: none; margin: 0; padding: 0; width: 100%; }
	.sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; height: 18px; }
	.sortable li>span { position: absolute; margin-left: -1.3em; margin-top:.4em; }
	.form-control{height:35px;}
</style>


<script type="text/javascript">
//<![CDATA[



function remove_option(id)
{
	if(confirm('<?php echo lang('confirm_remove_option');?>'))
	{
		$('#option-'+id).remove();
	}
}

//]]>
</script>


<?php  echo form_open_multipart($this->config->item('admin_folder').'/menus/form/'.$menuid.'/'.$restaurant_id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">Menu information</a></li>
		<li><a href="#option_tab" data-toggle="tab">Customisation</a></li>
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

		
		<div class="tab-pane" id="option_tab">
				<div class="row">
					<div class="span8">
						<div class="pull-right" style="padding:0px 0px 10px 0px;">
							<select id="option_options" style="margin:0px;">
								<option value=""><?php echo lang('select_option_type')?></option>
								<option value="checklist">Customisation</option>
							<!--	<option value="radiolist"><?php echo lang('radiolist');?></option>
								<option value="droplist"><?php echo lang('droplist');?></option>
								<option value="textfield"><?php echo lang('textfield');?></option>
								<option value="textarea"><?php echo lang('textarea');?></option> -->
							</select>
							<input id="add_option" class="btn" type="button" value="<?php echo lang('add_option');?>" style="margin:0px;"/>
						</div>
					</div>
				</div>
				
				<script type="text/javascript">
				
				$( "#add_option" ).click(function(){
					if($('#option_options').val() != '')
					{
						add_option($('#option_options').val());
						$('#option_options').val('');
					}
				});
				
				function add_option(type)
				{
					//increase option_count by 1
					option_count++;
					
					<?php
					$value			= array(array('name'=>'', 'value'=>'', 'weight'=>'', 'price'=>'', 'limit'=>''));
					$js_textfield	= (object)array('name'=>'', 'type'=>'textfield', 'required'=>false, 'values'=>$value);
					$js_textarea	= (object)array('name'=>'', 'type'=>'textarea', 'required'=>false, 'values'=>$value);
					$js_radiolist	= (object)array('name'=>'', 'type'=>'radiolist', 'required'=>false, 'values'=>$value);
					$js_checklist	= (object)array('name'=>'', 'type'=>'checklist', 'required'=>false, 'values'=>$value);
					$js_droplist	= (object)array('name'=>'', 'type'=>'droplist', 'required'=>false, 'values'=>$value);
					?>
					if(type == 'textfield')
					{
						$('#options_container').append('<?php add_option($js_textfield, "'+option_count+'");?>');
					}
					else if(type == 'textarea')
					{
						$('#options_container').append('<?php add_option($js_textarea, "'+option_count+'");?>');
					}
					else if(type == 'radiolist')
					{
						$('#options_container').append('<?php add_option($js_radiolist, "'+option_count+'");?>');
					}
					else if(type == 'checklist')
					{
						$('#options_container').append('<?php add_option($js_checklist, "'+option_count+'");?>');
					}
					else if(type == 'droplist')
					{
						$('#options_container').append('<?php add_option($js_droplist, "'+option_count+'");?>');
					}
				}
				
				function add_option_value(option)
				{
					
					option_value_count++;
					<?php
					$js_po	= (object)array('type'=>'radiolist');
					$value	= (object)array('name'=>'', 'value'=>'', 'weight'=>'', 'price'=>'');
					?>
					$('#option-items-'+option).append('<?php add_option_value($js_po, "'+option+'", "'+option_value_count+'", $value);?>');
				}
				
				$(document).ready(function(){
					$('body').on('click', '.option_title', function(){
						$($(this).attr('href')).slideToggle();
						return false;
					});
					
					$('body').on('click', '.delete-option-value', function(){
						if(confirm('<?php echo lang('confirm_remove_value');?>'))
						{
							$(this).closest('.option-values-form').remove();
						}
					});
					
					
					
					$('#options_container').sortable({
						axis: "y",
						items:'tr',
						handle:'.handle',
						forceHelperSize: true,
						forcePlaceholderSize: true
					});
					
					$('.option-items').sortable({
						axis: "y",
						handle:'.handle',
						forceHelperSize: true,
						forcePlaceholderSize: true
					});
				});
				</script>
				<style type="text/css">
					.option-form {
						display:none;
						margin-top:10px;
					}
					.option-values-form
					{
						background-color:#fff;
						padding:6px 3px 6px 6px;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						border-radius: 3px;
						margin-bottom:5px;
						border:1px solid #ddd;
					}
					
					.option-values-form input {
						margin:0px;
					}
					.option-values-form a {
						margin-top:3px;
					}
				</style>
				<div class="row">
					<div class="span8">
						<table class="table table-striped"  id="options_container">
							<?php
							$counter	= 0;
							if(!empty($product_options))
							
							{
								foreach($product_options as $po)
								{
									$po	= (object)$po;
									if(empty($po->required)){$po->required = false;}

									add_option($po, $counter);
									$counter++;
								}
							}?>
								
						</table>
					</div>
				</div>
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
<?php
function add_option($po, $count)
{
	ob_start();
	?>
	<tr id="option-<?php echo $count;?>">
		<td>
			<a class="handle btn btn-mini"><i class="icon-align-justify"></i></a>
			<strong><a class="option_title" href="#option-form-<?php echo $count;?>"><?php echo $po->type;?> <?php echo (!empty($po->name))?' : '.$po->name:'';?></a></strong>
			<button type="button" class="btn btn-mini btn-danger pull-right" onclick="remove_option(<?php echo $count ?>);"><i class="icon-trash icon-white"></i></button>
			<input type="hidden" name="option[<?php echo $count;?>][type]" value="<?php echo $po->type;?>" />
			<div class="option-form" id="option-form-<?php echo $count;?>">
				<div class="row-fluid">
				
					<div class="span10">
						<input type="text" class="span10" placeholder="<?php echo lang('option_name');?>" name="option[<?php echo $count;?>][name]" value="<?php echo $po->name;?>"/>
					</div>
					
					<div class="span2" style="text-align:right;">
						<input class="checkbox" type="checkbox" name="option[<?php echo $count;?>][required]" value="1" <?php echo ($po->required)?'checked="checked"':'';?>/> <?php echo lang('required');?>
					</div>
				</div>
				<?php if($po->type!='textarea' && $po->type!='textfield'):?>
				<div class="row-fluid">
					<div class="span12">
						<a class="btn" onclick="add_option_value(<?php echo $count;?>);"><?php echo lang('add_item');?></a>
					</div>
				</div>
				<?php endif;?>
				<div style="margin-top:10px;">

					<div class="row-fluid">
						<?php if($po->type!='textarea' && $po->type!='textfield'):?>
						<div class="span1">&nbsp;</div>
						<?php endif;?>
						<div class="span3"><strong>&nbsp;&nbsp;<?php echo lang('name');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo lang('weight');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo lang('price');?></strong></div>
						<div class="span2"><strong>&nbsp;<?php echo ($po->type=='textfield')?lang('limit'):'';?></strong></div>
					</div>
					<div class="option-items" id="option-items-<?php echo $count;?>">
					<?php if($po->values):?>
						<?php
						foreach($po->values as $value)
						{
							$value = (object)$value;
							add_option_value($po, $count, $GLOBALS['option_value_count'], $value);
							$GLOBALS['option_value_count']++;
						}?>
					<?php endif;?>
					</div>
				</div>
			</div>
		</td>
	</tr>
	
	<?php
	$stuff = ob_get_contents();

	ob_end_clean();
	
	echo replace_newline($stuff);
}

function add_option_value($po, $count, $valcount, $value)
{
	ob_start();
	?>
	<div class="option-values-form">
		<div class="row-fluid">
			<?php if($po->type!='textarea' && $po->type!='textfield'):?><div class="span1"><a class="handle btn btn-mini" style="float:left;"><i class="icon-align-justify"></i></a></div><?php endif;?>
			<div class="span3"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][name]" value="<?php echo $value->name ?>" /></div>
		
			<div class="span2"><select class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][weight]" value="<?php echo $value->weight ?>" >
				<option value="Small">Small</option>
				<option value="Medium">Medium</option>
				<option value="Large">Large</option>
				<option value="Full">Full</option>
				<option value="Half">Half</option>
			</select></div>
			<div class="span2"><input type="text" class="span12" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][price]" value="<?php echo $value->price ?>" /></div>
			<div class="span2">
			<?php if($po->type=='textfield'):?><input class="span12" type="text" name="option[<?php echo $count;?>][values][<?php echo $valcount ?>][limit]" value="<?php echo $value->limit ?>" />
			<?php elseif($po->type!='textarea' && $po->type!='textfield'):?>
				<a class="delete-option-value btn btn-danger btn-mini pull-right"><i class="icon-trash icon-white"></i></a>
			<?php endif;?>
			</div>
		</div>
	</div>
	<?php
	$stuff = ob_get_contents();

	ob_end_clean();

	echo replace_newline($stuff);
}
//this makes it easy to use the same code for initial generation of the form as well as javascript additions
function replace_newline($string) {
  return trim((string)str_replace(array("\r", "\r\n", "\n", "\t"), ' ', $string));
}
?>
<script type="text/javascript">
//<![CDATA[
var option_count		= <?php echo $counter?>;
var option_value_count	= <?php echo $GLOBALS['option_value_count'];?>

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
					<input type="hidden" name="related_products[]" value="'.$id.'"/>
					'.$name.'</td>
				<td>
					<a class="btn btn-danger pull-right btn-mini" href="#" onclick="remove_related_product('.$id.'); return false;"><i class="icon-trash icon-white"></i> '.lang('remove').'</a>
				</td>
			</tr>
		';
}