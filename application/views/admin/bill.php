<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>

<?php if(count($orders) > 1){ ?>
<table class="table table-striped table-bordered" >
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
					<?=$order->order_number;?>
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

<?php }else{
	echo "<div class='container'><h3>No data found</h3></div>";
} ?>

