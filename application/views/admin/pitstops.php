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
<script>
$(document).ready(function() {
var oTable = $('#table-pagination').DataTable( {
        dom: 'Blfrtip',
		
        buttons: [
       {
           extend: 'csv',
		   text: 'Export pitstops',
		    filename: 'pitstops',
           footer: false,
		   exportOptions: {
                    columns: [ 0, 2, 3, 4,5]
            },
			aoColumns: [{ "sTitle": "name" }],
			"columnDefs": [{
                "targets": [ 1 ],
                "visible": false,
                "searchable": false
            }],	 
			"aaSorting": [[ 2, "desc" ]]
       },
		
    ],
		
    });
oTable.column( 1 ).visible( false );

} );
</script>
<script>
	$(document).ready(function(){
		
		$('#table-pagination').on('uncheck-all.bs.table', function (e) {
			$(".DeleteOption").each(function () {
				$(this).prop("checked",false);
			});
		});
		$('#table-pagination').on('check-all.bs.table', function (e) {
			$(".selected").each(function () {
				var id = "DeleteOption"+$(this).context.cells[1].innerText;
				$("#"+id).prop("checked",true);
			});
		});
		$('#table-pagination').on('uncheck.bs.table', function (e,row) {
			$("#DeleteOption"+row.id).prop("checked",false);
		});
		$('#table-pagination').on('check.bs.table', function (e,row) {
			$("#DeleteOption"+row.id).prop("checked",true);
		});
	});
</script>
<div class="btn-group pull-right">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form'); ?>"><i class="icon-plus-sign"></i> Add new pitstop</a>
</div>
<br/>
<div style="display:block;clear:both;margin-bottom:40px;">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/pitstop/ImportPitstops'); ?>" method="post" enctype="multipart/form-data">
		<div class="form-group">
				<input type="file" name="pitstopfile" style="display:inline;">
				<input type="submit" name="submit" value="Upload" class="btn btn-xs btn-primary">
		</div>
	</form>
	<a href="../../pitstops.csv" style="text-decoration:underline">(Download the pitstop import format)</a>
</div>

<form action="<?php echo site_url($this->config->item('admin_folder').'/pitstop/Deleteall'); ?>" method="post">
<input type="submit" name="submit" value="delete all" class="btn btn-xs btn-primary">
<table class="table table-bordered" data-toggle="table"  id="table-pagination">		 
	<thead>
		<tr>
			<th data-field="state" data-checkbox="true"></th>
			<th data-field="id" data-hidden="true">Sl.No</th>
			<th data-field="name">Pitstop name</th>
			<th data-field="city">City</th>
			<th data-field="price">Latitude</th>
			<th>Longitude</th>
			<th>Connected</th>
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
			<tr class="gc_row" id="<?=$pitstop->pitstop_id;?>">
				<td></td>
				<td data-hidden="true"><?=$pitstop->pitstop_id;?></td>
				<td><input type="checkbox" style="display:none;" name="DeleteOptions[<?=$pitstop->pitstop_id;?>]" id="DeleteOption<?=$pitstop->pitstop_id;?>" class="DeleteOption">
					<?=$pitstop->pitstop_name;?>
				</td>
				<td><?=$pitstop->city;?></td>
				<td>
					<?=$pitstop->latitude; ?>
				</td>
				<td><?=$pitstop->langitude;?></td>
				<td><?php echo $this->Pitstop_model->CheckConnection($pitstop->pitstop_id); ?></td>
				<td><a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/form/'.$pitstop->pitstop_id); ?>" class="btn btn-info btn-xs">Edit</a>
				&nbsp;<a href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/delete/'.$pitstop->pitstop_id); ?>" class="btn btn-danger btn-xs">delete</a>
				<?php if($pitstop->enabled == 0) { ?>
					<a class="btn btn-success btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/pitstop/ChangeStatus/'.$pitstop->pitstop_id."/1"); ?>">activate</a>
					<?php } ?>
					<?php if($pitstop->enabled == 1) { ?>
					<a class="btn btn-danger btn-xs" data-toggle="modal" data-target="#DeactivateMenu" onclick="$('#pitid').val('<?=$pitstop->pitstop_id;?>')">Deactivate</a>
					
					<?php } ?>
				</td>
			</tr>
		<?php
			$i++;
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>
</form>

<div class="modal fade" id="DeactivateMenu" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
	<form action="<?php echo site_url($this->config->item('admin_folder').'/pitstop/ChangeStatus'); ?>" method="post">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		  <h4 class="modal-title">Deactivate Menu</h4>
		</div>
		<div class="modal-body">
		  <div class="form-group">
			<input type="hidden" name="pitid" id="pitid">
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

<script type="text/javascript">
    var datefield=document.createElement("input")
    datefield.setAttribute("type", "date")
    if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
        document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n') 
    }
</script>
 
<script>
if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
    jQuery(function($){ //on document.ready
        $('#FromDate').datepicker();
        $('#ToDate').datepicker();
    })
}
</script> 