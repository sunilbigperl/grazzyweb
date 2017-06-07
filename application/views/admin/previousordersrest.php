<?php $url = $this->uri->segment(4); if(isset($url)){ $idurl = $url; }else{ $idurl = ''; }  ?>
<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/orders/GetRestPreviousOrders/'.$idurl); ?>" method="post">
		<div class="form-group">
		  <label for="from date"><strong>from date:</strong></label>
		  <input type="date" class="form-control" id="fromdate" name="fromdate">
		</div>
		<div class="form-group">
		  <label for="to date"><strong>To date:</strong></label>
		  <input type="date" class="form-control" id="todate" name="todate">
		</div>
		
		<div class="form-group"><input type="submit" class="btn btn-primary" value="Go" name="action"></div>

      
		<div  style="margin-top:20px;">
			<div class="form-group"><input type="submit" class="btn btn-primary" value="PreviousMonth" name="action"></div>
			<div class="form-group"><input type="submit" class="btn btn-primary" value="CurrentMonth" name="action"></div>
		</div>
		

<?php if(count($orders) > 0){ ?>
<?php if(isset($id)){ ?>
<div class="btn-group">
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/restbill/'.$id.'/pdf') ?>" class="btn btn-xs btn-primary">Download pdf</a>
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/excel/'.$id.'/xls') ?>" class="btn btn-xs btn-primary">Download xls</a>
</div> 
<?php } ?>
</form>
</div>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Order id</th>
			<th data-field="date">Ordered date</th>
			<th data-field="name">Order number</th>
			<th data-field="price">Customer bill amount(Rs)</th>
			<th data-field="Commission">Commission</th>
			<th data-field="Penalty">Penalty</th>
			<th data-field="Reimb">Reimbursement of delivery charges</th>
			<th>Net amount</th>
			<th>Service tax</th>
			<th>Keep amount</th>
			<th>Total</th>
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
			{
				$charges = $this->Order_model->GetChargesForOrder($order->ordered_on);
				$servicetax = $charges['servicetax'];
				$deliverycharge = $charges['deliverycharge'];
		?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				<td><?=$order->ordered_on;?> </td>
				<td>
					<a href="#" style="color: #2f2fd0;text-decoration:underline;" data-toggle="modal" data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getMenuDetails');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?> </a>
					
				</td>
				<td>
					<?=$order->total_cost; ?>
				</td>
				
				
				<td>
					<?php  if($order->delivery_partner_status == "Rejected"){
						$commission = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $commission = (($order->total_cost *$order->commission)/100); }else{ $commission = "0"; }
					echo $commission;
					?>
				</td> 


				<td>
				<!-- (($order->total_cost * $order->penalty)/100) -->
					<?php  if($order->delivery_partner_status == "Rejected"){
						$penalty = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $penalty="0"; }else{ $penalty = ($order->penalty);  }
					echo $penalty;
					?>
				</td>
				<td>
					 <?php if($order->delivery_partner_status == "Rejected"){
						$reimb =  0;
					}elseif($order->restaurant_manager_status == "Rejected"){
						$reimb = 0;
					}else{
						$reimb = $order->reimb; 
					}
					echo $reimb;
					?>
				</td>
				
				 <td><?php if($order->delivery_partner_status == "Rejected"){
						$netamount = 0;
					}else{
						$netamount = $commission + $penalty + $reimb; ; 
					}
					 echo $netamount
					?></td>
				<td>
					<?php  if($order->delivery_partner_status == "Rejected"){
						$servicetax1 = 0;
					}else{
						$servicetax1 =($netamount*$servicetax)/100; 
					}
					echo $servicetax1;   ?>
				</td>
				<td><?php  if($order->delivery_partner_status == "Rejected"){
						$keepamt = 0;
					}else{
						$keepamt =  $netamount+$servicetax1;
					}
					echo $keepamt; ?></td>
				<td><?php if($order->delivery_partner_status == "Rejected"){
						echo  0;
					}elseif($order->restaurant_manager_status == "Accepted"){
						echo $order->total_cost - $keepamt;
					}else{
						echo  "-".$keepamt;
					}						?></td>
				<td>
					<?php if($order->restaurant_manager_status == "0"){ ?>
						Not acted yet
					<?php }elseif($order->delivery_partner_status == "Rejected"){
						echo "Delivery manager rejected";
					}elseif($order->delivery_partner_status == "Accepted"){
						echo "Delivery manager Accepted";
					}elseif($order->restaurant_manager_status == "Accepted"){
						echo "Restaurant manager accepted";
					}else{
						echo "Restaurant manager rejected";
					} ?>

				</td>
				<td> 
					<?php $remarks = $this->Order_model->get_delpartnerremarks($order);
					echo isset($remarks[0]->comments) ? $remarks[0]->comments : "No comments" ; ?>
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
        $('#fromdate').datepicker();
        $('#todate').datepicker();
    })
}
</script>
<?php $this->load->view('admin/marque'); ?>