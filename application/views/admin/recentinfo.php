
<h2 style="font-weight:bold;margin-top:50px;border-bottom:1px solid #cecece;">Recent Orders</h2>
<p id="margin"> 
<?php
echo "Date: " . date("Y/m/d") . "<br>";

?></p>
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
			<th>Action</th>
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
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/2');?>',<?=htmlspecialchars(json_encode($order));?>);" style="color: #2f2fd0;text-decoration:underline;">Review customer</a>
					<?php }else{ 
						if($order->delivered_by != 0){?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/3');?>',<?=htmlspecialchars(json_encode($order));?>);" style="color: #2f2fd0;text-decoration:underline;">Review delivery boy</a>
					<?php } } ?>
				</td>
				<td>
				<?php if($order->restaurant_manager_status == "0"){ ?>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/1/'.$order->id.'');?>" class="btn btn-sucess btn-xs">Accept</a>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/0/'.$order->id.'');?>" class="btn btn-danger btn-xs">Reject</a>
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


<h2 style="font-weight:bold;margin-top:50px;border-bottom:1px solid #cecece;"><?php echo lang('recent_customers') ?></h2>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<?php /*<th>ID</th> uncomment this if you want it*/ ?>
			<th class="gc_cell_left"><?php echo lang('firstname') ?></th>
			<th><?php echo lang('lastname') ?></th>
			<th><?php echo lang('email') ?></th>
			<th class="gc_cell_right"><?php echo lang('active') ?></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($customers as $customer):?>
		<tr>
			<?php /*<td style="width:16px;"><?php echo  $customer->id; ?></td>*/?>
			<td class="gc_cell_left"><?php echo  $customer->firstname; ?></td>
			<td><?php echo  $customer->lastname; ?></td>
			<td><a href="mailto:<?php echo  $customer->email;?>"><?php echo  $customer->email; ?></a></td>
			<td>
				<?php if($customer->active == 1)
				{
					echo lang('yes');
				}
				else
				{
					echo lang('no');
				}
				?>
			</td>
		
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
