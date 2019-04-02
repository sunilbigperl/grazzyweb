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
			<?php $delpartners= $this->Message_model->get_delpartners(); ?>
			<select name="delpartner" class="form-control">
				<option value="">Select delivery partner</option>
				<?php foreach($delpartners as $delpat){?>
				<option value="<?=$delpat['id']?>"><?=$delpat['username']?></option>
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
			<div class="form-group"><input type="submit" class="btn btn-primary" value="PreviousMonth" name="action"></div>
			<div class="form-group"><input type="submit" class="btn btn-primary" value="CurrentMonth" name="action"></div>
		</div>
	</form>
</div>
<!-- <?php foreach($orders1 as $order1) ?> -->

<?php if(count($orders) > 0){?>
<div class="btn-group">
<!-- <?php if($this->auth->check_access('Admin') ){ ?>
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/delpartnerbill/'.$delpartner.'/pdf') ?>" class="btn btn-xs btn-primary">Download pdf</a>
	<?php } ?>
	<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/delpartnerbill/'.$delpartner.'/xls') ?>" class="btn btn-xs btn-primary">Download xls</a> -->
	<?php if($this->auth->check_access('Admin') ){ ?>
	<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/orders/GetDelpartnerBill/'.$delpartner); ?>"><i class="icon-plus-sign"></i>Download Pdf</a> 
	<?php } ?>
<a class="btn btn-primary" href="<?php echo site_url($this->config->item('admin_folder').'/orders/GetDelpartnerBill1/'.$delpartner); ?>"><i class="icon-plus-sign"></i>Download Excel </a>
	
</div>
<?php if(isset($delpartner)){ ?>



<?php } } ?>

<?php  if(count($orders) > 0){ ?>

<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
           
			<!-- <th data-field="id">Order id</th> -->
			<th>Order type</th>
			<th data-field="date">Delivered On</th>
			<th data-field="name">Order number</th>
			<th>Customer Name</th>
			<th>Customer Mobile</th> 
			<th>Delivery Boy Name</th> 
			
			<th data-field="pickup">Pickup Location</th>
			<th data-field="delivery">Delivery Location</th>

            <?php if($this->auth->check_access('Admin')&& !isset($url) ){ ?>
		    <th>Order value(Rs)</th>
		   
			<th>Convenience Charge</th>
			<th>Discount(%)</th>
			<th>Discount(Rs)</th>
			<th>Vocher Discount</th>
			<th>Net Order Value</th>
			<!-- <th>GST on Net Order Value </th> -->
			<th>Net Order Value fulfilled</th>
			<!-- <th>GST on Net Order Value fulfilled</th> -->
			<th>Commission</th>
			<th>Penalty</th>
			<th>Reimbursement of delivery charges</th>
			<th>Payment Mode</th>
			<th>Net amount</th>
			<!-- <th>GST</th> -->
			<th>Keep amount for eatsapp</th>
			<th>Give to Restaurant</th>
			<th>Give to Customer</th>
			<?php } ?>
			<!-- <th data-field="price">Delivery Charge</th>
				
			<th data-field="distance">KM</th>
			<th data-field="Penalty">Penalty</th>
			<th>Net amount</th>
			<th>GST</th>
			<th>Total Amount</th> -->
			<!-- <th>Remarks</th> -->
			
			<th>Status</th>
			<th>Passcode</th>
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
				$deliverycharge1 = $charges['deliverycharge'];
				$deliverycharge = $this->Order_model->DelPartnerDeliveryCharge($order->distance,$order->id);
				$orders1 = $this->Order_model->get_previousorders1($order->delivery_partner);
				$orders2 = $this->Order_model->get_deliveryboy($order->delivered_by);
				// print_r($orders2[0]->name);exit;
				?>
			<tr class="gc_row">
				<!-- <td><?=$i;?></td> -->
				<td><?=$order->order_type;?></td>
				
				<td><?=date("Y-m-d", strtotime($order->ordered_on));?></td>
				<td>
					<a href="#" data-toggle="modal" style="color: #2f2fd0;text-decoration:underline;"  data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getMenuDetails1');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?></a>
				</td>
				
			     <td>
				<?php echo $order->firstname; ?>
				</td>
				<td>
				<?php echo $order->phone; ?>
				</td>
				
				<td>
				<!--  <?php if($order->delivered_by == "0"){ ?>
						No Deliveryboy
					<?php }else{
						// echo $order->delivered_by;
						$beliveryboy=$orders2[0]->name;
				 	    echo "$beliveryboy";
					} ?> -->
					<?php if($order->delivered_by == "0"){ ?>
						No Deliveryboy
					<?php }else{
						
				 	    echo $order->delivered_name;
					} ?>
					
				
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
                <?php if($this->auth->check_access('Admin') && !isset($url)){ ?>
				<td>
				   <?php echo $order->total_amount; ?>
				</td>

				 <td>
					<!-- <?=$deliverycharge1; ?> -->
					 <?php if($order->order_type !="I'll pickup"){ 
					 	$deliverycharge2 =$deliverycharge1;
					 	echo $deliverycharge2 ;?>
						<!-- <?=$deliverycharge1; ?> -->
						


					<?php }else{
						
						echo $deliverycharge2= 0;
					} 
					?>
				</td>
 
				<td>
                    
					<?=$order->discount1; ?>
				</td>
				<td>
					<?=$order->discount2; ?>
				</td>

				<td>
					<?=$order->coupon_discount; ?>
				</td>

				<td>
				   <?php $netordervalue=$order->netordervalue+$order->coupon_discount;?> 
					<?=$netordervalue;?>
				</td>
				<!-- <td>
				<?php $gstonnetordervalue=$order->tax;?> 
				<?=$gstonnetordervalue; ?>
				</td> -->
				<td>
					<?php  if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'){
						$netordervalue1 = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $netordervalue1=$netordervalue ; }else{ $netordervalue1 = "0"; }
					echo $netordervalue1;
					?>
				</td>

				<!-- <td>
					<?php  if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'){
						$gstonnetordervalue1 = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $gstonnetordervalue1=$gstonnetordervalue; }else{ $gstonnetordervalue1 = "0"; }
					echo $gstonnetordervalue1;
					?>
				</td> -->
				
				<td>
					<?php  if($order->delivery_partner_status == "Rejected" || $order->status=='order cancelled'){
						$commission = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $commission = 
						$netordervalue*($order->commission/100); }else{ $commission = "0"; }
					echo $commission;
					?>
				</td> 

				<!-- <td>
				
					<?php  if($order->delivery_partner_status == "Accepted" || $order->status == "Order Placed"){
						$penalty = 0;
					}elseif($order->restaurant_manager_status == "Accepted"){ $penalty="0"; }else{ $penalty = ($order->penalty);  }
					echo $penalty;
					?>
				</td> -->

				<td>
					<?php if($order->status=='order cancelled'&& $order->restaurant_manager_status == "0"){
						$penalty = ($order->penalty);
					}elseif($order->restaurant_manager_status == "Rejected"){
						$penalty = ($order->penalty);
					}else{
						$penalty = 0; 
					}
					echo $penalty;
					?>
				</td>
				
				<td>
					 <?php if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled' ){
						$reimb =  0;
					}elseif($order->restaurant_manager_status == "Rejected" ){
						$reimb = 0;
					}else{
						
						if($order->order_type!="I'll pickup")
						{
							$reimb = $order->reimb;
							
						}
						else{
						    $reimb = 0;
						} 
					}
					echo $reimb;
					?>
				</td>

				<td>
					<?php if($order->payment_mode ==0) 
					{ 
						echo "Paid Online";
				    }else{
                         echo "Collect Cash";
					    }
					?>
				</td>
				<td><?php if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'&& 
				 	$order->restaurant_manager_status == "Accepted"){
						$netamount = 0;
					}else{
						$netamount = $commission + $penalty + $reimb; ; 
					}
					 echo $netamount
					?></td>

					<td><?php  if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'&& 
				 	$order->restaurant_manager_status == "Accepted"){
						$keepamt = 0;
					}elseif($order->restaurant_manager_status == "Rejected" || $order->status=='order cancelled' )
					{
						$keepamt =  $netamount;
					}
					else{
						$keepamt =  $netamount+$deliverycharge2-$order->coupon_discount;
					}
					echo $keepamt; ?></td>
				<td><?php  if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'&& 
				 	$order->restaurant_manager_status == "Accepted"){
						echo  0;
					}elseif($order->restaurant_manager_status == "Accepted"){
						//echo $order->total_cost - $keepamt;
						echo $netordervalue+$gstonnetordervalue-$keepamt-$order->coupon_discount+$deliverycharge2;
					}else{
						echo  "-".$keepamt;
					}						?></td>
				
				<!-- <td><?php if($order->restaurant_manager_status == "Rejected"){
					 $givetocust=$netordervalue+$gstonnetordervalue+$deliverycharge2-$order->coupon_discount;
				      echo $givetocust;
					}elseif($order->delivery_partner_status== "Rejected"){
						$givetocust=$netordervalue+$gstonnetordervalue+$deliverycharge2-$order->coupon_discount;
						echo $givetocust;
					}elseif($order->status=='order cancelled'){
						$givetocust=$netordervalue+$gstonnetordervalue+$deliverycharge2-$order->coupon_discount;
				      echo $givetocust;
					}
					else{
						echo  0;
					}?></td> -->


					<td><?php if($order->payment_mode ==0 && ($order->restaurant_manager_status == "Rejected" || $order->status=='order cancelled')){
					 $givetocust=$netordervalue+$gstonnetordervalue+$deliverycharge2-$order->coupon_discount;
				      echo $givetocust;
					
					}else{
						echo  0;
					}?></td>
				<?php } ?>

				<!-- <td>
					 <?php if($order->delivery_partner_status == "Rejected" ||$order->status=='order cancelled'){
						$delivery  =  0;
						
					}elseif($order->restaurant_manager_status == "Rejected"){
						$delivery  = 0;
						
					}else{
						//$delivery = $deliverycharge['rate']; 
						$delivery = $order->delpartner_delivery_charge; 
					}
					echo $delivery;
					?>
				</td>  -->
			<!--<td>
					<?php 
						echo $deliverycharge['rate'];
						
					 ?>
					
				</td>  -->
				<!-- <td>
					<?php echo isset($order->distance) ?  $order->distance : '';?>
				</td> -->
				
				<!-- <td>
					<?php if($order->delivery_partner_status == "Accepted" || $order->restaurant_manager_status == "Accepted" || $order->status == "Order Placed"){
						echo "0";
					}else{
						echo $order->penalty;
					}
					?>
				</td> -->

				<!-- <td>
					<?php if($order->delivery_partner_status == "Rejected" ||  
						$order->status=='order cancelled'&&$order->restaurant_manager_status == "Accepted"){
						$penalty1 = ($order->del_partner_penalty);
					}else{
						$penalty1 = 0; 
					}
					echo $penalty1;
					?>
				</td> -->
				
				<!-- <td> <?php if($order->delivery_partner_status == "Accepted" || $order->status == "Order Placed"){
						//$netamount = $deliverycharge['rate'];
					$netamount = $order->delpartner_delivery_charge;
					}else{
						
						// $netamount = $deliverycharge['rate'] - $penalty1;
						$netamount =  $order->delpartner_delivery_charge - $penalty1;
						
					} 
					echo $netamount;
					?>
				</td> -->
				<!--
				<td><?php $servicetax1 = (($netamount*$servicetax)/100); echo $servicetax1; ?></td> -->
				<!-- <td>
					<?php echo $netamount+$servicetax1; ?>

				</td> -->
			
				<!-- <td> 
					<?php if($order->delivery_partner != 0 ){ ?>
					<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-info btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/ShowReviewDetailstodelpartner/'.$order->delivery_partner.'');?>');" style="color: #2f2fd0;text-decoration:underline;">Reviews/Ratings</a>
					<?php } ?>
				</td> -->
				
				<td>
				
				<!-- <?php echo $order->status; ?> --> 
				 <?php if($order->delivery_partner_status == "Rejected"){ 
						// echo "$order->status";
				 	$username=$orders1[0]->firstname;
				 	echo "Rejected by $username";
					}elseif($order->delivery_partner_status == "Accepted"){
						echo " $order->status";
					}else if($order->restaurant_manager_status == "Accepted" && $order->status == "order cancelled" ){
						if($order->order_type!="I'll pickup")
						{
						    $username=$orders1[0]->firstname;
				 	        echo "Rejected by $username";
					    }
					    else{
					    	  echo "Rejected by $order->restaurant_name";
					    }
					}elseif($order->restaurant_manager_status == "Accepted"){
						echo "$order->status ";
					}
					else if($order->restaurant_manager_status == "Rejected"){
						echo "Rejected by $order->restaurant_name";
					}
					else if($order->status == "order cancelled"){
						// $username=$orders1[0]->firstname;
						// echo "Rejected by $order->$username";
						echo "Rejected by $order->restaurant_name";
					}else{
						echo "Not acted yet";
						//echo "$order->status ";
					}
					?>  
                    

				</td>
				<td>
				<?php echo $order->passcode; ?>
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
<!-- <?php $this->load->view('admin/delpartnermarque'); ?> -->
