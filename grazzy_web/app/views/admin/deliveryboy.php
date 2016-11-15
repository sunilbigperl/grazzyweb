<?php echo theme_css('bootstrap-table.css', true);?>
<?php echo theme_js('bootstrap-table.js', true);?>
<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete');?>');
}
</script>
<div class="btn-group pull-right">
	<a class="btn" href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/form'); ?>"><i class="icon-plus-sign"></i> Add new delivery boy</a>
</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="number">id</th>
			<th data-field="type">Name</th>
			<th data-field="operator">Phone</th>
			<th>Action</th>
		</tr>
	</thead>
	
	<?php echo (count($pages) < 1)?'<tr><td style="text-align:center;" colspan="2">No Delivery boys found</td></tr>':''?>
	<?php if($pages):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
		
			foreach($pages as $page)
			{?>
			<tr class="gc_row">
				<td>
					<?=$page->id;?>
				</td>
				<td>
					<?php echo $page->name; ?>
				</td>
				<td><?=$page->phone;?></td>
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/delete/'.$page->id.''); ?>">delete</a>&nbsp;&nbsp;
				<a href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/form/'.$page->id.''); ?>">Edit</a></td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>