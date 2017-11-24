<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.js');?>"></script>
<?php echo form_open_multipart($this->config->item('admin_folder').'/here/form/'.$id); ?>

<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab"><?php echo lang('description');?></a></li>
		
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			
			<fieldset style="padding:10px">
			<div class="form-group">	
				<label for="name">Here Location name</label>
				<?php
				$data	= array('name'=>'name', 'value'=>set_value('name', $name), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">	
				<label for="pitstop_address">Coordinates</label>
				<?php
				$data	= array('name'=>'coordinates', 'value'=>set_value('coordinates', $coordinates), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<div class="form-group">	
				<label for="pitstop_address">City</label>
				<?php
				$data	= array('name'=>'city', 'value'=>set_value('city', $city), 'class'=>'form-control');
				echo form_input($data);
				?>
			</div>
			<!-- <div class="form-group">	
				<label for="pitstop_address">Delivery Point langitude</label>
				<?php
				$data	= array('name'=>'langitude', 'value'=>set_value('langitude', $langitude), 'class'=>'form-control',  'id'=>"lng");
				echo form_input($data);
				?>
			</div> -->
			


          <!-- <div class="form-group">
				<label for="restaurant_address">Delivery Point City</label>
				<select name="city" class="form-control" required>

					<option value="">Select city</option> 

					<?php foreach($getpitstop as $class):?>
						
                     <option value="<?php echo $class['city'];?>" <?php if($class['city']==$city){echo "selected";}?>><?php echo $class['city'];?> 
                     </option>
                      <?php endforeach;?> 
                      </select>
                    
			     </div>  -->

			   
			<div class="form-group">	
				<label for="enabled"><?php echo lang('enabled');?> </label>
        		<?php echo form_dropdown('enabled', array('0' => lang('disabled'), '1' => lang('enabled')), set_value('enabled',$enabled), 'class=form-control'); ?>
			</div>
			
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
<script type="text/javascript">


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
