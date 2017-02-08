
<script>
	$(document).ready(function(){
		$('#eventsTable').on('all.bs.table', function (e, name, args) {
			console.log("tets");
			$(".selected").each(function () {
				console.log($(this))
			});
		});
	});
</script>
<div class="btn-group pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form'); ?>"><i class="icon-plus-sign"></i> Add new pitstop</a>
</div>
<br/>
<div style="display:block;clear:both;margin-top:40px">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/pitstop/ImportPitstops'); ?>" method="post" enctype="multipart/form-data">
		<div class="form-group">
				<input type="file" name="pitstopfile" style="display:inline;">
				<input type="submit" name="submit" value="Upload" class="btn btn-xs btn-primary">
		</div>
	</form>
	<a href="../../pitstops.csv" style="text-decoration:underline">(Download the pitstop import format)</a>
</div>
<!--<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<form action="<?php echo site_url($this->config->item('admin_folder').'/pitstop/Deleteall'); ?>" method="post">
<input type="submit" name="submit" value="delete all" class="btn btn-xs btn-primary">
<table class="table table-bordered" data-toggle="table"
		 data-search="true" id="eventsTable" data-sort-order="desc" data-show-refresh="true">		 
	<thead>
		<tr>
			<th data-field="state" data-checkbox="true"></th>
			<th data-field="id">Sl.No</th>
			<th data-field="name">Pitstop name</th>
			<th data-field="price">Latitude</th>
			<th>Longitude</th>
			<th>Action</th>
			
		</tr>
	</thead>
	
	<?php echo (count($pitstops) < 1)?	'<tr><td style="text-align:center;" colspan="2">No pitstops found</td></tr>'	:	''; ?>
	<?php if($pitstops):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			foreach($pitstops as $pitstop){
		?>
			<tr class="gc_row">
				<td></td>
				<td><?=$i;?></td>
				<td>
					<?=$pitstop->pitstop_name;?>
				</td>
				<td>
					<?=$pitstop->latitude; ?>
				</td>
				<td><?=$pitstop->langitude;?></td>
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form/'.$pitstop->pitstop_id); ?>" class="btn btn-info btn-xs">Edit</a>
				&nbsp;<a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/delete/'.$pitstop->pitstop_id); ?>" class="btn btn-danger btn-xs">delete</a></td>
			</tr>
		<?php
			$i++;
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>
</form>