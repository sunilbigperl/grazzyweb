<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<p id="margin"> 
<?php
echo "" . date("jS F Y") . "<br>";

?></p>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">Sl.No</th>
			<th data-field="name">Order Number</th>
		<!--	<th data-field="price">Cost(Rs)</th> -->
			<th data-field="date">Ordered on</th>
			<th data-field="type">Order type</th>
			<!-- <th>Keep ready by</th> -->
			<th>Assign Delivery boy</th>
			<!--<th>Info</th>-->
			<th>Order status</th>
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
					<a href="#" style="color: #2f2fd0;text-decoration:underline;" data-toggle="modal" data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getOrderDetails');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?></a>
				</td>
			<!--	<td>
					<?=$order->total_cost; ?>
				</td> -->
				<td>
					<?=$order->ordered_on; ?>
				</td>
			
				<td>
					<?=$order->order_type;?>
				</td>
				<!-- <?php
				
                    $t1=date("h:i:s", strtotime("$order->delivered_on")) . "\n";

                  ?>
				<td><?php echo date("h:i:s", strtotime("$t1-$order->preparation_time minutes"));?></td>  -->
				<td> 
					<form id="the-basics" method="post" action="AssignDeliveryBoy/<?=$order->id;?>">
						<select type="text" name="deliveryboy" class="form-control typeahead" <?php if($order->delivered_by != 0 ||$order->status=='Rejected'){ echo "disabled";}?>>
							<option value="">select delivery boy</option>
							<?php foreach($deliveryboys as $deliveryboy){ if($order->delivered_by == $deliveryboy->id ){ $select= "selected";}else{$select ="";}?>
								<option value="<?=$deliveryboy->id?>" <?=$select?>><?=$deliveryboy->name;?></option>
							<?php } ?>
						</select>
						<!-- <?php if($order->delivered_by == 0){ ?>
						<input type="submit" value="Assign" name="assign" class="btn btn-primary">

						<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeDelPartnerStatus/0/'.$order->id.'');?>" class="btn btn-danger btn-xs">Reject</a> 
						<?php } ?>  -->
                        <?php if($order->status =='Rejected' ){ ?>
                        
                         <?php }else if($order->delivered_by == 0){?>
					     <input type="submit" value="Assign" name="assign" class="btn btn-primary">

			              <a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeDelPartnerStatus/0/'.$order->id.'');?>" class="btn btn-danger btn-xs"  onclick="two()">Reject</a>
				 <?php }else{?>

				<?php }?>






						<!-- 
						<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeDelPartnerStatus/0/'.$order->id.'');?>" class="btn btn-danger btn-xs">Reject</a> -->
					</form>
					
				</td>
			<!--	<td> 
				<?php $userdata = $this->session->userdata('admin'); 
					$CheckReview = $this->Order_model->CheckReview($order->order_number, 4, $userdata['id']);
					
					if($CheckReview == 0){ ?>
					<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/4');?>',<?=htmlspecialchars(json_encode($order));?>);">Review restaurant</a>
					<?php }else{
						echo "Review to Restaurant: ".$CheckReview[0]->comments;
					} 
					$CheckdelboyReview = $this->Order_model->CheckReview($order->order_number, 5, $userdata['id']);
					
					if($CheckdelboyReview == 0 && $order->delivered_by != 0 && $order->status == "Shipped"){ ?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/5');?>',<?=htmlspecialchars(json_encode($order));?>);">Review delivery boy</a>
					<?php }elseif($CheckdelboyReview != 0 && $order->delivered_by != 0 && $order->status == "Shipped"){
						echo "<br/>Review to Delivery Boy: ".$CheckReview[0]->comments;
					} ?>
				</td>-->
				<td>
					<?=$order->status;?>
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
var substringMatcher = function(strs) {
  return function findMatches(q, cb) {
    var matches, substringRegex;

    // an array that will be populated with substring matches
    matches = [];

    // regex used to determine if a string contains the substring `q`
    substrRegex = new RegExp(q, 'i');

    // iterate through the pool of strings and for any string that
    // contains the substring `q`, add it to the `matches` array
    $.each(strs, function(i, str) {
      if (substrRegex.test(str)) {
        matches.push(str);
      }
    });

    cb(matches);
  };
};

var states = ["Prepaid Sims","Postpaid Sims","International Sims","DTH","Broadband","Other Services","Sim Cards","DTH-Dish Tv","Coupens","Broadband-Hathaway","Data Card","Broadband-You","Broadband-Tikona","Prepaid Sims-Airtel","Prepaid Sims-Idea","Prepaid Sims-Docomo","Prepaid Sims-Reliance","Postpaid Sims-Airtel","Postpaid Sims-Idea","Postpaid Sims-Docomo","Postpaid Sims-Vodafone","Postpaid Sims-Reliance","DTH-Airtel Digital tv","DTH-Videocon","DTH-Tata Sky","DTH-Sun Direct","DTH-Big tv","Prepaid Sims-Aircel","Postpaid Sims-Aircel","Data Card-Vodafone","Data Card-Idea","Data Card-Reliance","Data Card-Tata docomo","Sim Cards-Airtel","Sim Cards-Aircel","Sim Cards-vodafone","Sim Cards-Docomo","Broadband-Airtel","International Sims-Reliance","International Sims-Matrix","International Sims-UniConnect","Sim Cards-Idea","Postpaid Sims-jio","Data Card-Airtel","International Sims-USA"]
$('#the-basics .typeahead').typeahead({ 
  hint: true,
  highlight: true,
  minLength: 1
},
{
  name: 'states',
  source: substringMatcher(states)
});

</script>
<?php $this->load->view('admin/marque'); ?>
<?php $this->load->view('admin/delpartnermarque'); ?>
