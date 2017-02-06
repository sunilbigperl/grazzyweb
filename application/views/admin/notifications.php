<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-horizontal" action="<?php echo site_url($this->config->item('admin_folder').'/message/addnotification'); ?>" method="post">
		
		<div class="form-group">
		  <textarea class="form-control" id="message" name="message" placeholder="Type new message here"></textarea>
		</div>
		
		<div class="form-group pull-right"><input type="submit" class="btn btn-primary" value="Send" name="action"></div>

	</form>
</div>

<h4>Notification History</h4>
<!--<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" data-show-refresh="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">-->
<table class="table table-striped table-bordered" data-toggle="table"  data-cache="false" data-pagination="true" 
		 data-search="true" id="table-pagination" data-sort-order="desc">		 
	<thead>
		<tr>
			<th data-field="id">Sl.No</th>
			<th data-field="Date">Date</th>
			<th data-field="Time">Time</th>
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
					<?=date('d-m-Y',strtotime($message['date'])); ?>
				</td>
				<td>
					<?=date('H:i:s',strtotime($message['date'])); ?>
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


