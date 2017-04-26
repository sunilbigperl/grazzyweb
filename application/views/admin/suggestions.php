<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">Restaurants suggestions</a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Pitstops suggestions</a></li>
	</ul>

	<div class="tab-content" >
		<div class="tab-pane active" id="description_tab">
			<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
				<thead>
					<tr>
						<th>Restaurant name</th>
						<th>Suggested customer</th>
						<th>customer Phoneno</th>
						<th>Restaurant email</th>
						<th>Restaurant phone</th>
						<th>Restaurant address</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($RestSuggestions['data']) > 0){ 
						$i=0;
						foreach($RestSuggestions['data'] as $ressuggestion){
					?>
					<tr>
						<td><?=$ressuggestion->restaurant_name;?></td>
						<td><?=isset($ressuggestion->firstname) ? $ressuggestion->firstname : '';?></td>
						<td><?=isset($ressuggestion->phone) ? $ressuggestion->phone : '';?></td>
						<td><?=isset($ressuggestion->restaurant_email) ? $ressuggestion->restaurant_email : '';?></td>
						<td><?=isset($ressuggestion->restaurant_phone) ? $ressuggestion->restaurant_phone : '';?></td>
						<td><?=isset($ressuggestion->restaurant_address) ? $ressuggestion->restaurant_address : '';?></td>
						<td><?=$ressuggestion->date;?></td>
					</tr>
					<?php $i++; }} ?>
				</tbody>
			</table>
		</div>

		<div class="tab-pane" id="attributes_tab">
			<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
				<thead>
					<tr>
						<th>Pitstop address</th>
						<th>Suggested customer</th>
						<th>Customer phoneno</th>
						<th>Latitude</th>
						<th>Langitude</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($PitstopSuggestion['data']) > 0){ 
						$i=0;
						foreach($PitstopSuggestion['data'] as $Pitstop){
					?>
					<tr >
						<td><a data-toggle="modal" data-target="#myModal"  onclick="ShowMap('<?=$Pitstop->restaurant_latitude; ?>','<?=$Pitstop->restaurant_langitude; ?>');"><?=$Pitstop->restaurant_address;?></a></td>
						<td><?=isset($Pitstop->firstname) ? $Pitstop->firstname : '';?></td>
						<td><?=isset($Pitstop->phone) ? $Pitstop->phone : '';?></td>
						<td><?=isset($Pitstop->restaurant_latitude) ?  $Pitstop->restaurant_latitude : '';?></td>
						<td><?=isset($Pitstop->restaurant_langitude) ? $Pitstop->restaurant_langitude : '';?></td>
						<td><?=$Pitstop->date;?></td>
					</tr>
					<?php $i++; }} ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
 <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Map</h4>
        </div>
        <div class="modal-body" id="map" style="height: 500px;
    width: 100%;">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
<script type="text/javascript">
function ShowMap(lat,lang){
 var myCenter = new google.maps.LatLng(lat,lang);
  var mapCanvas = document.getElementById("map");
  var mapOptions = {center: myCenter, zoom: 5};
  var map = new google.maps.Map(mapCanvas, mapOptions);
  var marker = new google.maps.Marker({position:myCenter});
  marker.setMap(map);
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKUsjUabpe8-dBedcqnKchPAVfsNqFnlE"></script>