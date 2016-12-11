<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-horizontal" action="<?php echo site_url($this->config->item('admin_folder').'/message/messagedel'); ?>" method="post">
		<div class="form-group" id="the-basics">
				<select id="delpartner_id" name="delpartner_id" class="form-control">
				<?php foreach($delpartners as $delpartner){?>
					<option value="<?=$delpartner['id']?>"><?=$delpartner['firstname'];?></option>
				<?php } ?>
				</select>
		</div>
		<div class="form-group">
		  <textarea class="form-control" id="message" name="message" placeholder="Type new message here"></textarea>
		</div>
		
		<div class="form-group pull-right"><input type="submit" class="btn btn-primary" value="Send" name="action"></div>

	</form>
</div>

<h4>Message History</h4>
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">
	<thead>
		<tr>
			<th data-field="id">message id</th>
			<th data-field="Date">Date</th>
			<th data-field="Time">Time</th>
			<th data-field="Restaurant">Delivery partber</th>
			<th data-field="Message">Message</th>
			
		</tr>
	</thead>
	<tbody>
		
		<?php
			$GLOBALS['admin_folder'] = $this->config->item('admin_folder');
			$i=1;
			if($messages != 0){
			foreach($messages as $message)
			{ 
		?>
			<tr class="gc_row">
				<td><?=$i;?></td>
				
				<td>
					<?=date('Y-m-d',strtotime($message['date'])); ?>
				</td>
				<td>
					<?=date('H:i:s',strtotime($message['date'])); ?>
				</td>
				<td>
					<?=$message['firstname'];?>
				</td>
				<td>
					<?=$message['message'];?>
				</td>
				
				
			</tr>
			<?php
			$i++;
			}
		}
		?>
	</tbody>
</table>


