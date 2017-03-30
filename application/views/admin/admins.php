<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete');?>');
}
</script>

<div style="text-align:right;">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/customers/charges'); ?>"><i class="icon-plus-sign"></i> Add charges</a>
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/admin/form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_admin');?></a>
</div>

<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
			<th><?php echo lang('firstname');?></th>
			<th><?php echo lang('lastname');?></th>
			<th><?php echo lang('email');?></th>
			<th><?php echo lang('username');?></th>
			<th>Phone No</th>
			<!--<th>Next Renewal date</th>
			<th><?php echo lang('access');?></th>-->
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($admins as $admin):?>
		<tr>
			<td><?php echo $admin->firstname; ?></td>
			<td><?php echo $admin->lastname; ?></td>
			<td><a href="mailto:<?php echo $admin->email;?>"><?php echo $admin->email; ?></a></td>
			<td><?php echo $admin->username; ?></td>
			<td><?php echo $admin->phone; ?></td>
			<!--<td><?php echo $admin->NextRenewalDate; ?></td>
			<td><?php echo $admin->access; ?></td>-->
			<td>
				<div class="btn-group" style="float:right;">
					<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/admin/form/'.$admin->id);?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>	
					<?php
					$current_admin	= $this->session->userdata('admin');
					$margin			= 30;
					if ($current_admin['id'] != $admin->id): ?>
					<a class="btn btn-danger" href="<?php echo site_url($this->config->item('admin_folder').'/admin/delete/'.$admin->id); ?>" onclick="return areyousure();"><i class="fa fa-trash"></i> <?php echo lang('delete');?></a>
					<?php endif; ?>
				</div>
			</td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>