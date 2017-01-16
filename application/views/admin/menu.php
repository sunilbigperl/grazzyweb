<?php echo theme_css('bootstrap-table.css', true);?>
<?php echo theme_js('bootstrap-table.js', true);?>

<div class="btn-group pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/0/'.$res_id); ?>"><i class="icon-plus-sign"></i> Add new menu</a>
  	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant'); ?>"><i class="icon-plus-sign"></i> Back</a> 

  <!--  <button class="btn btn-primary" onclick="history.go(-1);">Back </button>-->

	</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Id</th>
			<th data-field="name">menu</th>
			<th data-field="price">price</th>
			<th data-field="time">Item preparation time(In mins)</th>
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
				<td>
					<?=$menu->itemPreparation_time; ?>
				</td>
				<td>
				<a class="btn btn-info btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/'.$menu->menu_id.'/'.$res_id.''); ?>">Edit</a>
				&nbsp;<a class="btn btn-danger btn-xs" href="#" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/menus/delete/'.$menu->menu_id.'/'.$res_id.''); ?>'; }">delete</a>
					&nbsp;&nbsp;<?php if($menu->enabled == 1){ ?> 
						<a href="#" class="btn btn-danger  btn-xs" data-toggle="modal" data-target="#DeactivateMenu" onclick="$('#restid').val('<?=$res_id;?>');$('#menuid').val('<?=$menu->menu_id;?>')">Deactivate</a>
					<?php }else{ ?>
						<a class="btn btn-success btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/menus/MenuStatusChange/'.$menu->menu_id.'/'.$res_id.''); ?>" >Activate</a>
					<?php } ?>
				</td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>
<div class="modal fade" id="DeactivateMenu" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<form action="<?php echo site_url($this->config->item('admin_folder').'/menus/MenuStatusChange'); ?>" method="post">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Deactivate Menu</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<input type="hidden" name="restid" id="restid">
			<input type="hidden" name="menuid" id="menuid">
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