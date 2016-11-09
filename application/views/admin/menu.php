<?php echo theme_css('bootstrap-table.css', true);?>
<?php echo theme_js('bootstrap-table.js', true);?>

<div class="btn-group pull-right">
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant'); ?>"><i class="icon-plus-sign"></i> Restaurants</a>
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/0/'.$res_id); ?>"><i class="icon-plus-sign"></i> Add new menu</a>
</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Id</th>
			<th data-field="name">menu</th>
			<th data-field="price">price</th>
			<th>Action</th>
		</tr>
	</thead>
	
	<?php echo (count($menus) < 1)?'<tr><td style="text-align:center;" colspan="2">No menu found</td></tr>':''?>
	<?php if($menus):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			foreach($menus as $menu)
			{?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				<td>
					<?=$menu->menu;?>
				</td>
				<td>
					<?=$menu->price; ?>
				</td>
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/'.$menu->menu_id.'/'.$res_id.''); ?>">Edit</a>
				&nbsp;<a href="<?php echo site_url($this->config->item('admin_folder').'/menus/delete/'.$menu->menu_id.'/'.$res_id.''); ?>">delete</a></td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>