<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete_customer');?>');
}
</script>
<div class="btn-group pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/customers/get_subscriber_list');?>"><i class="icon-download"></i> <?php echo lang('subscriber_download');?></a>
	&nbsp;<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/customers/form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_customer');?></a>
</div>

<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc" data-page-length="100">
	<thead>
		<tr>
			
			<?php
			if($by=='ASC')
			{
				$by='DESC';
			}
			else
			{
				$by='ASC';
			}
			?>
			
		<!--	<th data-field="id"><a href="<?php echo site_url($this->config->item('admin_folder').'/customers/index/lastname/');?>/<?php echo ($field == 'lastname')?$by:'';?>"><?php echo lang('lastname');?>
				<?php if($field == 'lastname'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?> </a></th> -->
			
			<th data-field="firstname"><a href="<?php echo site_url($this->config->item('admin_folder').'/customers/index/firstname/');?>/<?php echo ($field == 'firstname')?$by:'';?>"><?php echo lang('firstname');?>
				<?php if($field == 'firstname'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?></a></th>
			
			<th data-field="email"><a href="<?php echo site_url($this->config->item('admin_folder').'/customers/index/email/');?>/<?php echo ($field == 'email')?$by:'';?>"><?php echo lang('email');?>
				<?php if($field == 'email'){ echo ($by == 'ASC')?'<i class="icon-chevron-up"></i>':'<i class="icon-chevron-down"></i>';} ?></a></th>
			<th><?php echo lang('active');?></th>
			<th>Phone No</th>
			<th>Actions</th>
			<th>Reviews & reatings</th>
		</tr>
	</thead>
	
	<tbody>
		<?php
		$page_links	= $this->pagination->create_links();
		
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
		<?php echo (count($customers) < 1)?'<tr><td style="text-align:center;" colspan="5">'.lang('no_customers').'</td></tr>':''?>
<?php foreach ($customers as $customer):?>
		<tr>
			<?php /*<td style="width:16px;"><?php echo  $customer->id; ?></td>*/?>
		<!--	<td><?php echo  $customer->lastname; ?></td>-->
			<td class="gc_cell_left"><a href="#" data-toggle="modal" style="color: #2f2fd0;text-decoration:underline;" data-target="#ratingdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/customers/ShowCustomerDetails/'.$customer->id.'');?>');"><?php echo  $customer->firstname; ?></a></td>
			<td><a href="mailto:<?php echo  $customer->email;?>"><?php echo  $customer->email; ?></a></td>
			<td>
				<?php if($customer->active == 1)
				{
					echo 'Active';
				}
				elseif($customer->active == 2)
				{
					echo 'Deactivated 3 months';
				}else{
					
					echo 'Deactive';
				}
				?>
			</td>
			<td><?php echo $customer->phone; ?></td>
			<td>
				<div >
					<?php if($customer->active == 2 || $customer->active == 0) { ?>
					<a class="btn btn-success btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/ChangeStatus/'.$customer->id."/1"); ?>">activate</a>
					<?php } ?>
					<?php if($customer->active == 1) { ?>
					<a class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/ChangeStatus/'.$customer->id."/0"); ?>">Deactivate</a>
					&nbsp;<a class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/ChangeStatus/'.$customer->id."/2"); ?>">Deactivate 3 months</a>
					<?php } ?>
				</div>
				<!--<div class="btn-group" style="float:right">
					<a class="btn btn-info btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/form/'.$customer->id); ?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>
					
					<a class="btn btn-primary btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/addresses/'.$customer->id); ?>"><i class="icon-envelope"></i> <?php echo lang('addresses');?></a>
					
					<a class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/customers/delete/'.$customer->id); ?>" onclick="return areyousure();"><i class="fa fa-trash"></i> <?php echo lang('delete');?></a>
				</div>-->
			</td>
			<td>
				<a href="#" style="color: #2f2fd0;text-decoration:underline;" data-toggle="modal" data-target="#ratingdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/customers/ShowReviewDetails/'.$customer->id.'');?>');">Reviews</a>
			</td>
		</tr>
<?php endforeach;
		if($page_links != ''):?>
		<tr><td colspan="5" style="text-align:center"><?php echo $page_links;?></td></tr>
		<?php endif;?>
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