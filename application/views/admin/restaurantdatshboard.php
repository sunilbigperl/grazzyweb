 <?php if($this->auth->check_access('Restaurant manager')) : ?>
			<div class="alert alert-error" style="font-size:16px;text-align:center">
				<!-- <a class="close" data-dismiss="alert">×</a> -->
				<a class="close" data-dismiss="alert"></a>
					“Will You Be Able To Serve All The

					Items In The Menu or Do You Need To Disable Certain Items?
					<br/>

					To Deactivate The Menu Items, <a href="<?php echo site_url($this->config->item('admin_folder'));?>/restaurant">Please Click Here</a>”
			</div>
	<?php endif; ?>
	 <p id="margin"> 
<?php
echo "" . date("jS F Y") . "<br>";


?></p>
<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
			<!-- <th data-field="id">Order id</th> -->
			<th data-field="name">Order Number</th>
			<!-- <th data-field="price">Cost (Rs)</th> -->
			<th data-field="date">Ordered On</th>
			<th data-field="type">Order Type</th>
			<th>Keep Ready By</th>
			<!--<th>Delivery Status</th>-->
			<th>Action</th>
			
			<th></th>
		
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
				
				?>

			<tr class="gc_row">
				<!-- <td><?=$i;?></td> -->

				<td>
					<a href="#" style="color: #2f2fd0;text-decoration:underline;" data-toggle="modal" data-target="#orderdetails" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/getMenuDetails');?>',<?=htmlspecialchars(json_encode($order));?>);"><?=$order->order_number;?></a>
				</td>
				<td>
				     <?php $cost=$order->total_amount-$order->discount1-$order->discount2+$order->tax;?>
				     
					<!-- <?=$order->total_amount; ?> -->
					<!-- <?=$cost; ?> -->
				</td>
				<td>
					<?=$order->ordered_on; ?>
				</td>
			
				<td>
					<?=$order->order_type;?>
				</td>
				<?php
				
    $t1=date("h:i:s", strtotime("$order->delivered_on")) . "\n";
//     $t2=date("h:i:s", strtotime("$t1-$order->preparation_time minutes"));             

// echo $t1;
// echo $t2; 
      
                 
?>
				<td><?php echo date("h:i:s", strtotime("$t1-$order->preparation_time minutes"));?></td> 
				 
				
				<!--<td> 
					<?php if($order->ordertype_id == 3){ ?>
						<a href="#" data-toggle="modal" data-target="#orderdetails"style="color: #2f2fd0;text-decoration:underline;"  class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/2');?>',<?=htmlspecialchars(json_encode($order));?>);">Review customer</a>
					<?php }else{ 
						if($order->delivered_by != 0){?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" style="color: #2f2fd0;text-decoration:underline;" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/3');?>',<?=htmlspecialchars(json_encode($order));?>);">Review delivery boy</a>
					<?php } } ?>
				</td>-->
				<!--<td><?php echo $order->status ? $order->status : ''; ?></td>-->
				<td>
				<?php if($order->restaurant_manager_status == "0"){ ?>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/1/'.$order->id.'');?>" class="btn btn-success btn-xs">Accept</a>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/0/'.$order->id.'');?>" class="btn btn-danger btn-xs">Reject</a>


				<?php }else{
					//echo $order->restaurant_manager_status;
					// if($order->status == "Assigned"){ echo "Order Confirmed";}
					// if($order->status == "Rejected"){ echo "Order Cancelled";}
					// if($order->status == "Order Placed") { echo "Wait for confirmation"; }
					
                      if($order->ordertype_id!= 3){ 
						if($order->status == "Assigned"){ echo "Order Confirmed";}
						
						elseif($order->status == "Order Placed") { echo "Wait for confirmation";

                            


						 }
						else{
							echo $order->status;
						}
					}
					else{
						if($order->status == "Order Placed") { echo "order Confirmed";

                            


						 }
						if($order->status == "Delivered"){ echo "Picked Up";}
					}
				} ?>
				</td>
				<td>
				<?php if($order->restaurant_manager_status == "Accepted" && $order->ordertype_id== 3){ ?>
					<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/2/'.$order->id.'');?>" class="btn btn-danger btn-xs">Delivered</a>


				<?php } ?>
				</td>
				<!-- <td>
                
				<?php if($order->restaurant_manager_status == "Accepted" && $order->ordertype_id== 3){  ?>
				<a href="<?php echo site_url($this->config->item('admin_folder').'/orders/ChangeRestMangerStatus/2/'.$order->id.'');?>" class="btn btn-danger btn-xs">Delivered</a>
				<?php }?> 

				</td>  -->
				
				<td><?=$order->passcode;?></td>
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
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="chart_div" style="margin-top:40px;"></div>
<!--<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawColColors);

function drawColColors() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'month');
      data.addColumn('number', 'Category1');
      data.addColumn('number', 'Category2');

      data.addRows([
        ['jan', 25000, 24000],
        ['feb', 16000, 11000],
      ]);

      var options = {
        title: 'Sales Chart',
        colors: ['#9575cd', '#33ac71'],
        hAxis: {
          title: 'Month',
        },
        vAxis: {
          title: 'Sales amount of items( in Rs)'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>-->
<?php $this->load->view('admin/marque'); ?>

