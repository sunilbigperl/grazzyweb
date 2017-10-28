<script type="text/javascript" src="https://datatables.net/release-datatables/media/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.0/js/buttons.html5.min.js"></script>
<style>
   a.dt-button.buttons-csv.buttons-html5 { background-color: rgb(51, 122, 183);
    color: white;
    padding: 4px;
    float: left;
    margin-right: 20px;
	}
</style>
<?php if($this->auth->check_access('Admin')) {?>
<script>
$(document).ready(function() {
var oTable = $('#table-pagination').DataTable( {
        dom: 'Blfrtip',
        buttons: [
       {
           extend: 'csv',
		   text: 'Export menu',
           footer: false,
		   exportOptions: {
                    columns: [ 0, 1, 2, 3, 4,5,6,7,8]
            },
       }        
    ]  
    } );

} );
</script>
<?php } ?>
<div class="btn-group pull-left" style="margin-bottom:20px;">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/0/'.$res_id); ?>"><i class="icon-plus-sign"></i> Add New Menu Item</a>
  	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/restaurant'); ?>"><i class="icon-plus-sign"></i> Back</a> 

	</div>

<table class="table table-striped table-bordered"  id="table-pagination" >
	<thead>
		<tr>
			<!-- <th data-field="id">Id</th> -->
			<th data-field="id">Code</th>
			<th data-field="name">Menu</th>
			<th data-field="name">Description</th>
			<th data-field="price">Price</th>
			<th data-field="price">Type</th>
			<!-- <th data-field="price">size</th> -->
			<th data-field="time">Item Preparation Time</th>
			<th>Enabled</th>
			<th>Category</th>
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
				<td><?=$menu->menu_id;?></td>
				<td>
					<?=$menu->code;?>
				</td> 
				<td>
					<?=$menu->menu;?>
				</td>
				<td>
					<?=$menu->description;?>
				</td> 
				<td>
					<?=$menu->price; ?>
				</td>
				<td>
					<?=$menu->type; ?>
				</td>
				<!-- <td>
					<?=$menu->size; ?>
				</td> -->
				
				<td>
					<?=$menu->itemPreparation_time; ?>
				</td>
				<td>
					<?=$menu->enabled; ?>
				</td> 
				<td><?php $cats = $this->Menu_model->get_menu_categories($menu->menu_id);
				$category = "";
				if(count($cats) > 0){
					foreach($cats as $cat){
						$category.= $cat->name.", ";
					}
				}
				echo $category;
				?>
				
					<?php if(count($cats) == 0){ echo '<img src="../../../../assets/img/warning.jpg" width="30px" height="30px" title="Please Select a Category">'; echo "Please Select a Category"; }?>
				</td>
				<td>
				<a class="btn btn-info btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/menus/form/'.$menu->menu_id.'/'.$res_id.''); ?>">Edit</a>
				&nbsp;<a class="btn btn-warning btn-xs" href="#" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/menus/delete/'.$menu->menu_id.'/'.$res_id.''); ?>'; }">Delete</a>
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
<script>
$("#export").click(function(){
  $("table").tableToCSV();
});
</script>