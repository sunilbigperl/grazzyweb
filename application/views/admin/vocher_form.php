
<?php echo form_open_multipart($this->config->item('admin_folder').'/admin/addvocher/'.$id); ?>

<div class="tabbable">

	<div class="tab-content">
		
		<fieldset style="padding:10px">
			<div class="form-group">	
					<label for="coupon_code">Coupon Name</label>
					<input type="text" name="coupon_code" value="<?=$coupon_code?>" class="form-control" required>
					
			</div>
			<div class="form-group">	
					<label for="used">Number of used</label>
					<input type="text" name="used" value="<?=$used?>" class="form-control" required>
					
			</div>
			<div class="form-group">	
					<label for="cost">Cost</label>
					<input type="text" name="cost" value="<?=$cost?>" class="form-control" required>
					
			</div>
			
			</fieldset>
		

		
</div>

</div>
<div class="form-actions">
	<button type="submit" class="btn btn-primary"><?php echo lang('save');?></button>
</div>
</form>


<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
			
			<th>Coupon Name</th>
			<th>Number of used</th>
			<th>Cost</th>
			<th>Actions</th>
	   </tr>
	</thead>
	
	<?php echo (count($orders) < 1)?	'<tr><td style="text-align:center;" colspan="2">No new orders found</td></tr>'	:	''; ?>
	<?php if($orders):?>
	<tbody>
		
		<?php
		foreach($orders as $order){?>
			<tr class="gc_row">
				<!-- <td><?=$i;?></td> -->
				
			    <td>
				<?php echo $order->coupon_code; ?>
				</td>
				<td>
				<?php echo $order->used; ?>
				</td>
				<td>
				<?php echo $order->cost; ?>
				</td>
				<td>
				<div class="btn-group" style="float:left;">
					<a class="btn btn-danger btn-xs" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/admin/deletecoupon/'.$order->id); ?>'; }">delete</a>	
					
					<a class="btn btn-info btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/admin/addvocher/'.$order->id); ?>">Edit</a></br>
					
				</div>
			</td>
				
				
				
			</tr>
			<!-- <?php
			$i++;
			}
		
		?> -->
	</tbody>
	<?php endif;?>
</table>