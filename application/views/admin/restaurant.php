<script src="assets/js/bootstrap-table.js"></script>
<link href="assets/css/bootstrap-table.css">
<div class="btn-group pull-right">
	<?php if($this->auth->check_access('Admin')){ ?>
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form'); ?>"><i class="icon-plus-sign"></i> Add new restaurant</a>
	<?php } ?>
</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Id</th>
			<th data-field="name">Restaurant name</th>
			<th data-field="price">Email</th>
			<th>Action</th>
			<th></th>
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
				<?php if($this->auth->check_access('Admin')){ ?>
				<a href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/form/'.$restaurant->restaurant_id); ?>">Edit</a>
				&nbsp;<a href="<?php echo site_url($this->config->item('admin_folder').'/restaurant/delete/'.$restaurant->restaurant_id); ?>">delete</a>
				<?php } ?>
				</td>
			
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/menus/index/'.$restaurant->restaurant_id); ?>">Add menus</a></td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>