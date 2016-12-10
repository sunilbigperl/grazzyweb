<?php echo theme_css('bootstrap-table.css', true);?>
<?php echo theme_js('bootstrap-table.js', true);?>

<div class="btn-group pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form'); ?>"><i class="icon-plus-sign"></i> Add new pitstop</a>
</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Id</th>
			<th data-field="name">Pitstop name</th>
			<th data-field="price">Latitude/Longitude</th>
			<th>Action</th>
			
		</tr>
	</thead>
	
	<?php echo (count($pitstops) < 1)?	'<tr><td style="text-align:center;" colspan="2">No pitstops found</td></tr>'	:	''; ?>
	<?php if($pitstops):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			foreach($pitstops as $pitstop)
			{?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				<td>
					<?=$pitstop->pitstop_name;?>
				</td>
				<td>
					<?=$pitstop->latitude."/".$pitstop->langitude; ?>
				</td>
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form/'.$pitstop->pitstop_id); ?>">Edit</a>
				&nbsp;<a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/delete/'.$pitstop->pitstop_id); ?>">delete</a></td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>