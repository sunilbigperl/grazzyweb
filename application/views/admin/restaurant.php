<div class="btn-group pull-left">
	<?php if($this->auth->check_access('Admin')){ ?>
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form'); ?>"><i class="icon-plus-sign"></i> Add new restaurant</a>
	<?php } ?>
</div>
<br/>
<?php if($this->auth->check_access('Admin')){ ?>
<div style="display:block;clear:both;margin-top:40px">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/restaurant/ImportRestaurants'); ?>" method="post" enctype="multipart/form-data">
		<div class="form-group">
				<input type="file" name="restaurantfile" style="display:inline;">
				<input type="submit" name="submit" value="Upload" class="btn btn-xs btn-primary">
		</div>
	</form>
	<a href="../../restaurant.csv" style="text-decoration:underline">(Download the Restaurant import format)</a>


</div>
<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/getrestaurantlist');?>" style="margin-top:20px"><i class="icon-download"></i>Export Restaurant</a>
<?php } ?>

<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true"  <?php if($this->auth->check_access('Admin')) { ?>  data-search="true" <?php } ?> id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Sl.No</th>
			<th data-field="name">Restaurant name</th>
			<th data-field="price">Contact Details</th>
			<th>Action</th>
			<th>Import menus<br/><a href="../../restaurant_menu.csv">(Download the Menu format)</a></th>
			<th>Reviews</th>
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
					<?=$restaurant->restaurant_name;?></br></br>
					
			        <?php $res_manager = $this->Restaurant_model->get_managers($restaurant->restaurant_id); echo isset($res_manager[0]->firstname) ? $res_manager[0]->firstname : ''; ?>
				</td>
				<td>
				   Cell:<a href="" style="color: #2f2fd0;text-decoration:underline;" > <?=$restaurant->restaurant_phone; ?></a></br></br>
					Email:<a href="" style="color: #2f2fd0;text-decoration:underline;" ><?=$restaurant->restaurant_email; ?></a>
					
				</td>
				<td>
					<span class="btn-group">
					<a class="btn btn-info btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form/'.$restaurant->restaurant_id); ?>">Edit</a></br>
					                <?php if($this->auth->check_access('Admin')) {?>
				<a class="btn btn-danger btn-xs" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/restaurant/delete/'.$restaurant->restaurant_id); ?>'; }">delete</a></br>
				
									<?php } ?>
					<a class="btn btn-xs btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/menus/index/'.$restaurant->restaurant_id); ?>">Menu</a></br>
				<?php if($this->auth->check_access('Admin')) {?>
					<?php if($restaurant->enabled == 1){ ?> 
						<a class="btn btn-xs btn-danger" style="color: white;" data-toggle="modal" data-target="#DeactivateRest" onclick="$('#restid').val('<?=$restaurant->restaurant_id;?>')">Deactivate</a></br>

					<?php }else{ ?>
						
						<a class="btn btn-xs btn-success" style="color:white;" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/RestaurantStatusChange/'.$restaurant->restaurant_id."/1"); ?>" >Activate</a>
					<?php } ?>
                     <?php } ?>
					</span>
				</td>
				<td>
					<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/menus/ImportMenu/'.$restaurant->restaurant_id); ?>" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<div class="form-group">
								<input type="file" name="menufile" >
								<input type="submit" name="submit" value="Upload" class="btn btn-xs btn-default">
							</div>
						</div>
					</form>
				</td>
				<td>
					<span class="btn-group">
					<?php if($this->auth->check_access('Admin')){ ?>
						<a class="btn btn-primary btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/orders/GetRestPreviousOrders/'.$restaurant->restaurant_id); ?>">previous orders/sales</a></br>
					<?php } ?>
						<a href="#" data-toggle="modal" data-target="#ratingdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/ShowReviewDetails/'.$restaurant->restaurant_id.'');?>');">Reviews/Ratings</a></br>
						<?php if($this->auth->check_access('Admin')){ ?>
						<a class="btn btn-primary btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/message/index/'.$restaurant->restaurant_id);?>" >Messages</a></br>
						<?php } ?>
					</span>
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
			<input type="date" name="ToDate" id="ToDate"><br/><br/>
			<label><strong>Deactivate Permanently</strong></label>
			<input type="checkbox"  name="enabled" value="2">
			
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
<script type="text/javascript">
    var datefield=document.createElement("input")
    datefield.setAttribute("type", "date")
    if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
        document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n') 
    }
</script>
 
<script>
if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
    jQuery(function($){ //on document.ready
        $('#FromDate').datepicker();
        $('#ToDate').datepicker();
    })
}
</script> 
<?php $this->load->view('admin/marque'); ?>