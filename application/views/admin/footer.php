
</div>
	</div>
	
	</div>
    <hr/>

	<script src="<?php echo base_url('vendors/js/bootstrap-table.js ');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/redactor.min.js');?>"></script>

    <!-- FastClick -->
    <script src="<?php echo base_url('vendors/js/fastclick.js');?>"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url('vendors/js/nprogress.js');?>"></script>
	
    <script src="<?php echo base_url('vendors/js/bootstrap-progressbar.min.js');?>"></script>

    <script src="<?php echo base_url('vendors/js/custom.min.js');?>"></script>
	
	<script>
		$(document).ready(function(){
			$("ul.child_menu").each(function(){
				if($(this).css('display') == "block"){
					$(this).css('display','none');
				}
			});
				/* if($(".child_menu").css("display",'block')){
					$(this).css("display",'none')
				} */
			
		});
	</script>
</body>
</html>