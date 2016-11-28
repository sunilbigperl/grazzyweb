<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<script src="<?=base_url();?>assets/js/star-rating.min.js"></script>

<div class="container">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/orders/GetPreviousOrders'); ?>" method="post">
		<div class="form-group span4">
		  <label for="from date"><strong>from date:</strong></label>
		  <input type="date" class="form-control" id="fromdate" name="fromdate">
		</div>
		<div class="form-group span4">
		  <label for="to date"><strong>To date:</strong></label>
		  <input type="date" class="form-control" id="todate" name="todate">
		</div>
		<div class="form-group span2"><input type="submit" class="btn btn-primary" value="Go" name="action"></div>
	<br/>
		<div class="span12" style="margin-top:20px;">
			<div class="form-group span6"><input type="submit" class="btn btn-primary" value="PreviousMonth" name="action"></div>
			<div class="form-group span4"><input type="submit" class="btn btn-primary" value="CurrentMonth" name="action"></div>
		</div>
	</form>
</div>
<?php if(count($orders) > 1){ ?>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Order id</th>
			<th data-field="name">Order Number</th>
			<th data-field="price">Cost(Rs)</th>
			<th data-field="date">Ordered on</th>
			<th data-field="type">Order type</th>
			<th>Keep ready by</th>
			<th>Info</th>
			<th>Status</th>
		</tr>
	</thead>
	
	<?php echo (count($orders) < 1)?	'<tr><td style="text-align:center;" colspan="2">No new orders found</td></tr>'	:	''; ?>
	<?php if($orders):?>
	<tbody>
		
		<?php
		$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			foreach($orders as $order)
			{?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				<td>
					<a href="#" data-toggle="modal" data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getMenuDetails');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?></a>
				</td>
				<td>
					<?=$order->total_cost; ?>
				</td>
				<td>
					<?=$order->ordered_on; ?>
				</td>
			
				<td>
					<?=$order->order_type;?>
				</td>
				<td></td>
				<td> 
					<?php if($order->ordertype_id == 3){ ?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/2');?>',<?=htmlspecialchars(json_encode($order));?>);">Review customer</a>
					<?php }else{ 
						if($order->delivered_by != 0){?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/3');?>',<?=htmlspecialchars(json_encode($order));?>);">Review delivery boy</a>
						<?php } } ?>
				</td>
				<td>
				<?php if($order->restaurant_manager_status == "0"){ ?>
				<?php }else{
					echo $order->restaurant_manager_status;
				} ?>
				</td>
			</tr>
			<?php
			$i++;
			}
		
		?>
	</tbody>
	<?php endif;?>
</table>

<div id="orderdetails" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" id="modaldetails">
      
    </div>

  </div>
</div>
<?php } ?>
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
