<?php $url = $this->uri->segment(4); if(isset($url)){ $idurl = $url; }else{ $idurl = ''; }  ?>
<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/orders/previousordersdelpartner/'.$idurl); ?>" method="post">
		<div class="form-group">
		  <label for="from date"><strong>From date:</strong></label>
		  <input type="date" class="form-control" id="fromdate" name="fromdate">
		</div>
		<div class="form-group">
		  <label for="to date"><strong>To date:</strong></label>
		  <input type="date" class="form-control" id="todate" name="todate">
		</div>
		<?php if($this->auth->check_access('Admin') && !isset($url)){ ?>
		<div class="form-group">
			<label for="to date"><strong>Delivery partner:</strong></label>
			<?php $delpartners = $this->Message_model->get_delpartners(); ?>
			<select name="delpartner" class="form-control">
				<option value="">Select delivery partner</option>
				<?php foreach($delpartners as $delpartner){?>
				<option value="<?=$delpartner['id']?>"><?=$delpartner['username']?></option>
				<?php } ?>
			</select>
			<label for="to date"><strong>Restaurant:</strong></label>
			<?php $restaurants = $this->Message_model->get_restaurants1(); ?>
			<select name="restaurant" class="form-control">
				<option value="">Select Restaurant</option>
				<?php foreach($restaurants as $restaurant){?>
				<option value="<?=$restaurant['restaurant_id']?>"><?=$restaurant['restaurant_name']?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="form-group"><input type="submit" class="btn btn-primary" value="Go" name="action"></div>

		<div  style="margin-top:20px;">
			<div class="form-group"><input type="submit" class="btn btn-primary" value="Previous Month" name="action"></div>
			<div class="form-group"><input type="submit" class="btn btn-primary" value="Current Month" name="action"></div>
		</div>
	</form>
</div>
<?php if(count($orders) > 0){ ?>
<?php if(isset($delpartner)){ ?>
<div class="btn-group">
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/delpartnerbill/'.$delpartner.'/pdf') ?>" class="btn btn-xs btn-primary">Download pdf</a>
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/delpartnerbill/'.$delpartner.'/xls') ?>" class="btn btn-xs btn-primary">Download xls</a>
</div>
<?php } } ?>

<?php  if(count($orders) > 0){ ?>
<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
			<th data-field="id">Order id</th>
			<th data-field="date">Ordered date</th>
			<th data-field="name">Order number</th>
			<th data-field="pickup">Pickup location</th>
			<th data-field="delivery">Delivery location</th>
			<th data-field="price">Delivery charge</th>
			<th data-field="distance">KM</th>
			<th data-field="Penalty">Penalty</th>
			
			<th>Net amount</th>
			<th>Service tax</th>
			<th>Total</th>
			<th>Remarks</th>
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
				<td><?=$order->ordered_on;?></td>
				<td>
					<a href="#" data-toggle="modal" style="color: #2f2fd0;text-decoration:underline;"  data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getMenuDetails');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?></a>
				</td>
				<?php 
				$data['restaurant'] = $this->Restaurant_model->get_restaurant($order->restaurant_id);
				$data['fromaddress'] = $data['restaurant']->restaurant_address;
				
				if($order->order_type == 1 && $order->pitstop_id != ""){
					$pitstop = $this->Pitstop_model->get_pitstop($order->pitstop_id);
					$data['toaddress'] = $pitstop->address;
				}else{
					$data['toaddress'] = $order->delivery_location;
				}
				?>
				<td>
					<?php echo isset($data['fromaddress']) ?  $data['fromaddress'] : ''; ?>
				</td>
				<td>
				
					<?php echo isset($data['toaddress']) ? $data['toaddress'] : ''; ?>
				</td>
				<td>
					<?php if($order->delivery_partner_status == "Accepted"){
						echo $deliverycharge;
					}else{
						echo "0";
					} ?>
					
				</td>
				<td>
					<?php echo isset($order->distance) ?  $order->distance : '';?>
				</td>
				
				<td>
					<?php if($order->delivery_partner_status == "Accepted"){
						echo "0";
					}else{
						echo $order->penalty;
					}
					?>
				</td>
				
				<td> <?php if($order->delivery_partner_status == "Accepted"){
						$netamount = $deliverycharge;
					}else{
						$netamount = $order->penalty;
					}
					echo $netamount;
					?>
				</td>
				<td><?php $servicetax1 = (($netamount*$servicetax)/100); echo $servicetax1; ?></td>
				<td>
					<?php echo $netamount+$servicetax1; ?>
				</td>
			
				<td> 
					<?php if($order->delivery_partner != 0 ){ ?>
					<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/ShowReviewDetailstodelpartner/'.$order->delivery_partner.'');?>');" style="color: #2f2fd0;text-decoration:underline;">Reviews/Ratings</a>
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

<?php $this->load->view('admin/delpartnermarque'); ?>
