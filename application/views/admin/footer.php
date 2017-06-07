
</div>
	</div>
	
	</div>
    <hr/>
	<script>
		$(document).ready(function(){
			$("li").click(function(){
				$(this).closest("li").addClass('active');
				 if($(this).closest("li").children("ul").length) {
					 $(this).closest("li").children("ul").css("display",'block');
				 }
			});
			$("ul.child_menu").each(function(){
				if($(this).css('display') == "block"){
					$(this).css('display','none');
				}
			});
				/* if($(".child_menu").css("display",'block')){
					$(this).css("display",'none')
				} */
			
		});
		
		 setInterval(function() {
                // alert();
           }, 300000); 
	</script>
	<script src="<?php echo base_url('vendors/js/bootstrap-table.js ');?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/js/redactor.min.js');?>"></script>

    <!-- NProgress -->
    <script src="<?php echo base_url('vendors/js/nprogress.js');?>"></script>
	
    <script src="<?php echo base_url('vendors/js/bootstrap-progressbar.min.js');?>"></script>

    <script src="<?php echo base_url('vendors/js/custom.min.js');?>"></script>
	
	
</body>
</html>