
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
						<th>Customer email</th>
						<th>Customer phone</th>
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
						<td><?=$ressuggestion->firstname;?></td>
						<td><?=$ressuggestion->email;?></td>
						<td><?=$ressuggestion->phone;?></td>
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
						<th>Pitstop name</th>
						<th>Suggested customer</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					<?php if(count($PitstopSuggestion['data']) > 0){ 
						$i=0;
						foreach($PitstopSuggestion['data'] as $Pitstop){
					?>
					<tr>
						<td><?=$Pitstop->pitstop_name;?></td>
						<td><?=$Pitstop->firstname;?></td>
						<td><?=$ressuggestion->email;?></td>
						<td><?=$ressuggestion->phone;?></td>
						<td><?=$Pitstop->date;?></td>
					</tr>
					<?php $i++; }} ?>
				</tbody>
					
			</table>
			
		</div>
		
	
	</div>

</div>