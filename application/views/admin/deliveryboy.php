<script type="text/javascript">
function areyousure()
{
	return confirm('<?php echo lang('confirm_delete');?>');
}
</script>
<div class="btn-group pull-left">
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/form'); ?>"><i class="icon-plus-sign"></i> Add new delivery boy</a>
</div>

<table class="table table-striped" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<!-- <th data-field="id">id</th> -->
			<th data-field="name">Name</th>
			<th>Phone</th>
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
				<!-- <td>
					<?=$page->id;?>
				</td> -->
				<td>
					<?php echo $page->name; ?>
				</td>
				<td><?=$page->phone;?></td>
				<td><a class="btn btn-danger btn-xs" onclick="var result = confirm('Are you sure you want to delete?'); if(result) { location.href='<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/delete/'.$page->id.''); ?>'; }">delete</a>&nbsp;&nbsp;

				<!-- <a  class="btn btn-danger btn-xs" href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/delete/'.$page->id.''); ?>">delete</a>&nbsp;&nbsp; -->
				<a class="btn btn-info btn-xs"href="<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/form/'.$page->id.''); ?>">Edit</a>
				<!-- <a href="#" data-toggle="modal" data-target="#ratingdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/deliveryboy/ShowReviewDetails/'.$page->id.'');?>');" style="color:white;text-decoration:none;">Reviews</a> -->
				</td>
			</tr>
			<?php
			
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>
<div id="ratingdetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" id="modaldetails">
     
    </div>

  </div>
</div>
<script>
	function showdetails(url,data){
		$.ajax({
			url:url,
			method:"post",
			datatype:'json',
			data:{data:data},
			success:function(data){
				$("#modaldetails").html(data);
			}
		});
	}
</script>  

<!-- <?php $this->load->view('admin/delpartnermarque'); ?> -->

<?php $this->load->view('admin/autoredirect'); ?> 