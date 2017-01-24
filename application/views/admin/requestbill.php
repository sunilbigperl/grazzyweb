<link href="<?=base_url();?>assets/css/bootstrap-table.css">
<script src="<?=base_url();?>assets/js/bootstrap-table.js"></script>
<script src="<?=base_url();?>assets/js/star-rating.min.js"></script>

<div class="container" style="margin-top:20px;margin-bottom:20px;">
	<form class="form-inline"  method="post" name="form" action="<?php echo site_url($this->config->item('admin_folder').'/orders/GenerateBillMail'); ?>">
		<div class="form-group span4">
		  <label for="from date"><strong>from date:</strong></label>
		  <input type="date" class="form-control" id="fromdate" name="fromdate">
		</div>
		<div class="form-group span4">
		  <label for="to date"><strong>To date:</strong></label>
		  <input type="date" class="form-control" id="todate" name="todate">
		</div>
		<div class="form-group span2"><input type="submit" class="btn btn-primary" id="BtnGo" value="Go" name="action"></div>
	<br/>
		<div class="form-group" style="margin-top:20px;">
			<div class="form-group span6"><input type="submit" class="btn btn-primary" value="Previous Month" name="action"></div>
			<div class="form-group span4"><input type="submit" class="btn btn-primary" value="Current Month" name="action"></div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#BtnGo').click(function(){

			if($("#fromdate").val() == "" || $("#todate").val() == ""){
				alert("Please select the dates");
				return false;
			}else{
				$(form).submit();
			}
		});
		


	});
</script>
<?php $this->load->view('admin/marque'); ?>
<?php $this->load->view('admin/delpartnermarque'); ?>