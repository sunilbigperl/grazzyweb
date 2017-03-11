<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete');?>');
}
</script>

<div style="text-align:right;" class="pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/form'); ?>"><i class="icon-plus-sign"></i> Add new delivery partner</a>
</div>

<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc" >-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc" >		 
	<thead>
		<tr>
			<th>Delivery Partner.</th>
			<th><?php echo lang('email');?></th>
			
			<th>Phone No</th>
			<th>Action</th>
			<th></tH>
		</tr>
	</thead>
	<tbody>
<?php foreach ($admins as $admin):?>
		<tr>
			<td><?php echo $admin->firstname; ?></td>
			<td><a href="mailto:<?php echo $admin->email;?>"><?php echo $admin->email; ?></a></td>
			
			<td><?php echo $admin->phone; ?></td>
			<td>
				<div class="btn-group" style="float:right;">
					<a class="btn btn-primary btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/form/'.$admin->id);?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>	
					<?php
					$current_admin	= $this->session->userdata('admin');
					$margin			= 30;
					if ($current_admin['id'] != $admin->id): ?>
					<a class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/delete/'.$admin->id); ?>" onclick="return areyousure();"><i class="fa fa-trash"></i> <?php echo lang('delete');?></a>
					<?php endif; ?>
					<?php if($admin->enabled == 0) { ?>
					<a class="btn btn-success btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/ChangeStatus/'.$admin->id."/1"); ?>">activate</a>
					<?php } ?>
					<?php if($admin->enabled == 1) { ?>
					<a class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/ChangeStatus/'.$admin->id."/0"); ?>">Deactivate</a>
					
					<?php } ?>
				</div>
			</td>
			<td>
			<a href="#"  data-toggle="modal" data-target="#ratingdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/Deliverypartner/ShowReviewDetails/'.$admin->id.'');?>');">Reviews</a>
			<a href="<?php echo site_url($this->config->item('admin_folder').'/message/delmessage/'.$admin->id.'');?>" class="btn btn-info btn-xs">Messages</a>
			<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/previousordersdelpartner/'.$admin->id.'');?>" class="btn btn-info btn-xs">Previous orders</a>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>


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