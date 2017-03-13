
<div class="tabbable">

	<ul class="nav nav-tabs">
		<li class="active"><a href="#description_tab" data-toggle="tab">Restaurants suggestions</a></li>
		<li><a href="#attributes_tab" data-toggle="tab">Pitstops suggestions</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="description_tab">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Restaurant name</th>
						<th>Suggested customer</th>
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
			
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Pitstop address</th>
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
					<tr>
						<td><?=$Pitstop->restaurant_address;?></td>
						<td><?=isset($Pitstop->restaurant_latitude) ?  $Pitstop->restaurant_latitude : '';?></td>
						<td><?=isset($ressuggestion->restaurant_langitude) ? $ressuggestion->restaurant_langitude : '';?></td>
						<td><?=$Pitstop->date;?></td>
					</tr>
					<?php $i++; }} ?>
				</tbody>
					
			</table>
			
		</div>
		
	
	</div>

</div>