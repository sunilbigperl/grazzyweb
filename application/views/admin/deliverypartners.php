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
			<td><?php echo isset($admin->firstname) ? $admin->firstname : ''; echo "<br/>"; echo  isset($admin->lastname) ? $admin->lastname : '';?></td>
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
					
					<a href="#" class="btn btn-danger  btn-xs" data-toggle="modal" data-target="#DeactivateMenu" onclick="$('#patid').val('<?=$admin->id;?>');">Deactivate</a>
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
<div class="modal fade" id="DeactivateMenu" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<form action="<?php echo site_url($this->config->item('admin_folder').'/deliverypartner/ChangeStatus'); ?>" method="post">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Deactivate Menu</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<input type="hidden" name="patid" id="patid">
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