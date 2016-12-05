<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<div class="btn-group pull-left">
	<?php if($this->auth->check_access('Admin')){ ?>
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form'); ?>"><i class="icon-plus-sign"></i> Add new restaurant</a>
	<?php } ?>
</div>

<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Id</th>
			<th data-field="name">Restaurant name</th>
			<th data-field="price">Email</th>
			<th>Action</th>
			<th></th>
			<th>Import menus<a href="../../restaurant_menu.csv"> Download the format</a></th>
			<th>Previous orders/ Sales</th>
		</tr>
	</thead>
	
	<?php echo (count($restaurants) < 1)?	'<tr><td style="text-align:center;" colspan="2">No restaurant found</td></tr>'	:	''; ?>
	<?php if($restaurants):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			foreach($restaurants as $restaurant)
			{?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				<td>
					<?=$restaurant->restaurant_name;?>
				</td>
				<td>
					<?=$restaurant->restaurant_email; ?>
				</td>
				<td>
				<a href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form/'.$restaurant->restaurant_id); ?>">Edit</a>
				&nbsp;<a href="#" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/restaurant/delete/'.$restaurant->restaurant_id); ?>'; }">delete</a>
				
				</td>
			
				<td>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/menus/index/'.$restaurant->restaurant_id); ?>">Add menus</a>&nbsp;&nbsp;&nbsp;
					<?php if($restaurant->enabled == 1){ ?> 
						<a href="#" data-toggle="modal" data-target="#DeactivateRest" onclick="$('#restid').val('<?=$restaurant->restaurant_id;?>')">Deactivate restaurant</a>
					<?php }else{ ?>
						<a href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/RestaurantStatusChange/'.$restaurant->restaurant_id); ?>" >Activate restaurant</a>
					<?php } ?>
				</td>
				<td>
					<form action="<?php echo site_url($this->config->item('admin_folder').'/menus/ImportMenu/'.$restaurant->restaurant_id); ?>" method="post" enctype="multipart/form-data">
						<input type="file" name="menufile"><input type="submit" name="submit" value="submit">
					</form>
				</td>
				<td>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/GetRestPreviousOrders/'.$restaurant->restaurant_id); ?>">previous orders/sales</a>
					<a href="#" data-toggle="modal" data-target="#ratingdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/ShowReviewDetails/'.$restaurant->restaurant_id.'');?>');">Reviews/Ratings</a>
					
				</td>
			</tr>
			<?php
			$i++;
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>
<div class="modal fade" id="DeactivateRest" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<form action="<?php echo site_url($this->config->item('admin_folder').'/restaurant/RestaurantStatusChange'); ?>" method="post">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Deactivate Restaurant</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<input type="hidden" name="id" id="restid" value="">
			<label><strong>From date</strong></label>
			<input type="date" name="FromDate" id="FromDate">
			<label><strong>To date</strong></label>
			<input type="date" name="ToDate" id="ToDate">
			<input type="hidden" name="enabled" value="0">
		  </div>
		</div>
		<div class="modal-footer">
		  <input type="submit" name="submit" value="submit" class="btn btn-primary">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</form>
  </div>
  
</div>
</div>
<div id="ratingdetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" id="modaldetails">
     
    </div>

  </div>
</div>

<script>
	function showdetails(url,data){
		$.ajax({
			url:url,
			method:"post",
			datatype:'json',
			data:{data:data},
			success:function(data){
				$("#modaldetails").html(data);
			}
		});
	}
</script>  