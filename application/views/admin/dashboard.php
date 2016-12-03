
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKUsjUabpe8-dBedcqnKchPAVfsNqFnlE"></script>
<div id="map" style="width: 1200px; height: 800px;"></div>

  <script type="text/javascript">
    var locations = <?=$restaurant;?>;
	var locations1 = <?=$pitstops;?>;
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(-33.92, 151.25),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
	var icon = {
		url: "http://localhost/grazzyweb/assets/img/Map.png", // url
		scaledSize: new google.maps.Size(50, 50), // scaled size
		origin: new google.maps.Point(0,0), // origin
		anchor: new google.maps.Point(0, 0) // anchor
	};
	for (i = 0; i < locations1.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations1[i][1], locations1[i][2]),
        map: map,
		icon: icon
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations1[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>
  
<h2><?php echo lang('recent_orders') ?></h2>
<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<script src="<?=base_url();?>assets/js/star-rating.min.js"></script>
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
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/2');?>',<?=htmlspecialchars(json_encode($order));?>);">Review customer</a>
					<?php }else{ 
						if($order->delivered_by != 0){?>
						<a href="#" data-toggle="modal" data-target="#orderdetails" class="btn btn-primary btn-xs" onclick="showdetails('<?php echo site_url($this->config->item('admin_folder').'/orders/Review/3');?>',<?=htmlspecialchars(json_encode($order));?>);">Review delivery boy</a>
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


<!--<div class="row">
	<div class="span12" style="text-align:center;">
		<a class="btn btn-large" href="<?php echo site_url(config_item('admin_folder').'/orders');?>"><?php echo lang('view_all_orders');?></a>
	</div>
</div>-->


<h2><?php echo lang('recent_customers') ?></h2>
<table class="table table-striped">
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


<div class="row">
	<div class="span12" style="text-align:center;">
		<a class="btn btn-large" href="<?php echo site_url(config_item('admin_folder').'/customers');?>"><?php echo lang('view_all_customers');?></a>
	</div>
</div>