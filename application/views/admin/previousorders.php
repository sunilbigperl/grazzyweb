<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<script src="<?=base_url();?>assets/js/star-rating.min.js"></script>

<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/orders/GetPreviousOrders'); ?>" method="post">
		<div class="form-group span4">
		  <label for="from date"><strong>from date:</strong></label>
		  <input type="date" class="form-control" id="fromdate" name="fromdate">
		</div>
		<div class="form-group span4">
		  <label for="to date"><strong>To date:</strong></label>
		  <input type="date" class="form-control" id="todate" name="todate">
		</div>
		<div class="form-group span2"><input type="submit" class="btn btn-default" value="Go" name="action"></div>
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
			<th data-field="name">Order number</th>
			<th data-field="price">Customer bill amount(Rs)</th>
			<th data-field="Commission">Commission</th>
			<th data-field="Penalty">Penalty</th>
			<th>Net amount</th>
			<th>Status</th>
			<th>Del partner remarks</th>
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
					<?=$order->commission;?>
				</td>
				<td>
					<?=$order->penalty;?>
				</td>
				<td>
					
				</td>
			
				<td>
					<?php if($order->restaurant_manager_status == "0"){ ?>
					<?php }else{
						echo $order->restaurant_manager_status;
					} ?>
				</td>
				<td> 
					<?php $remarks = $this->Order_model->get_delpartnerremarks($order);
					echo isset($remarks[0]->comments) ? $remarks[0]->comments : "" ; ?>
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
<?php }else{
	echo "<div class='container'><h3>No data found</h3></div>";
} ?>
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
