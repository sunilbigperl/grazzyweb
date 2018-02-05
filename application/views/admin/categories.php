<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete_category');?>');
}
</script>

<div class="pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/categories/form'); ?>"><i class="icon-plus-sign"></i> <?php echo lang('add_new_category');?></a>
</div>

<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
    <thead>
		<tr>
		    <th>Category_id</th>
			<th data-field="name"><?php echo lang('name')?></th>
			<th><?php echo lang('enabled');?></th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php echo (count($categories) < 1)?'<tr><td style="text-align:center;" colspan="4">'.lang('no_categories').'</td></tr>':''?>
		<?php
		define('ADMIN_FOLDER', $this->config->item('admin_folder'));
		function list_categories($parent_id, $cats, $sub='') {
			
			foreach ($cats[$parent_id] as $cat):?>
			<tr>
				<td><?php echo  $sub.$cat->id; ?></td>
				<td><?php echo  $sub.$cat->name; ?></td>
				<td><?php echo ($cat->enabled == '1') ? lang('enabled') : lang('disabled'); ?> </td>
				<td>
					<div class="btn-group" style="float:center">

						<a class="btn btn-primary btn-xs" href="<?php echo  site_url(ADMIN_FOLDER.'/categories/form/'.$cat->id);?>"><i class="icon-pencil"></i> <?php echo lang('edit');?></a>

						<a class="btn btn-danger btn-xs" href="<?php echo  site_url(ADMIN_FOLDER.'/categories/delete/'.$cat->id);?>" onclick="return areyousure();"><i class="fa fa-trash"></i> <?php echo lang('delete');?></a>
						
					
					<?php if($cat->enabled == 0) { ?>
					<a class="btn btn-success btn-xs" href="<?php echo site_url(ADMIN_FOLDER.'/categories/ChangeStatus/'.$cat->id."/1"); ?>">activate</a>
					<?php } ?>
					<?php if($cat->enabled == 1) { ?>
					<a class="btn btn-danger btn-xs"  data-toggle="modal" data-target="#DeactivateMenu" onclick="$('#catid').val('<?=$cat->id;?>')" >Deactivate</a>
					
					<?php } ?>
					</div>
				</td>
			</tr>
			<?php
			if (isset($cats[$cat->id]) && sizeof($cats[$cat->id]) > 0)
			{
				$sub2 = str_replace('&rarr;&nbsp;', '&nbsp;', $sub);
					$sub2 .=  '&nbsp;&nbsp;&nbsp;&rarr;&nbsp;';
				list_categories($cat->id, $cats, $sub2);
			}
			endforeach;
		}
		
		if(isset($categories[0]))
		{
			list_categories(0, $categories);
		}
		
		?>
	</tbody>
</table>

<div class="modal fade" id="DeactivateMenu" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<form action="<?php echo site_url(ADMIN_FOLDER.'/categories/ChangeStatus'); ?>" method="post">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Deactivate category</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<input type="hidden" name="categoryid" id="catid">
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