<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	 
	 <?php date_default_timezone_set('Asia/Kolkata'); 
	 if($this->auth->check_access('Restaurant manager')) { ?>
    <title>POS eatsapp</title>
 <?php }elseif($this->auth->check_access('Deliver manager')) { ?>
         <title>DBOS eatsapp</title>
 <?php }else{ ?>
 <title>Eatsapp…</title>
    
 <?php } ?>
    
    <!-- Bootstrap -->
    <link href="<?php echo base_url('vendors/css/bootstrap.css');?>" rel="stylesheet">
	
    <!-- Font Awesome -->
    <link href="<?php echo base_url('vendors/css/font-awesome.min.css');?>"  rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('vendors/css/nprogress.css');?>" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="<?php echo base_url('vendors/css/bootstrap-progressbar-3.3.4.min.css');?>" rel="stylesheet">
	
    <!-- bootstrap-daterangepicker -->
    <link href="<?php echo base_url('vendors/css/daterangepicker.css');?>"  rel="stylesheet">
	
    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('vendors/css/custom.min.css');?>" rel="stylesheet">

	 <link href="<?php echo base_url('vendors/css/bootstrap-table.css ');?>" rel="stylesheet">
	<script src="<?php echo base_url('vendors/js/jquery.min.js');?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('vendors/js/bootstrap.min.js');?>"></script>
	
	<script src="<?php echo base_url('vendors/js/bootstrap-table.js ');?>"></script>
   

	 <script src="<?php echo base_url('vendors/js/jquery.tabletoCSV.js');?>"></script>
	 
	
	<?php if($this->auth->is_logged_in(false, false)):?>
		
	<style type="text/css">
		
		@media (max-width: 979px){ 
			body {
				margin-top:0px;
			}
		}
		@media (min-width: 980px) {
			.nav-collapse.collapse {
				height: auto !important;
				overflow: visible !important;
			}
		 }
		
		.nav-tabs li a {
			text-transform:uppercase;
			background-color:#f2f2f2;
			border-bottom:1px solid #ddd;
			text-shadow: 0px 1px 0px #fff;
			filter: dropshadow(color=#fff, offx=0, offy=1);
			font-size:12px;
			padding:5px 8px;
		}
		
		.nav-tabs li a:hover {
			border:1px solid #ddd;
			text-shadow: 0px 1px 0px #fff;
			filter: dropshadow(color=#fff, offx=0, offy=1);
		}

	</style>
	 <link rel="shortcut icon" href="<?php echo base_url('uploads/favicon.png');?>" type="image/x-icon">  
	<script type="text/javascript">
	$(document).ready(function(){
		$('.redactor').redactor({
				minHeight: 200,
				imageUpload: '<?php echo site_url(config_item('admin_folder').'/wysiwyg/upload_image');?>',
				fileUpload: '<?php echo site_url(config_item('admin_folder').'/wysiwyg/upload_file');?>',
				imageGetJson: '<?php echo site_url(config_item('admin_folder').'/wysiwyg/get_images');?>',
				imageUploadErrorCallback: function(json)
				{
					alert(json.error);
				},
				fileUploadErrorCallback: function(json)
				{
					alert(json.error);
				}
		  });
		 
	});
	</script>
	<?php endif;?>
</head>
<body class="nav-sm">
<div class="container body">
    <div class="main_container">
	<?php if($this->auth->is_logged_in(false, false)):?>
	<?php $admin_url = site_url($this->config->item('admin_folder')).'/';?>
		<div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;background-color: red;">
              <?php if($this->auth->check_access('Admin')){ ?>
              <a href="<?=$admin_url;?>" class="site_title"> <span>Admin Panel</span></a>
			<?php } elseif($this->auth->check_access('Restaurant manager')){  ?>
			
             <!-- <a href="<?=$admin_url;?>" class="site_title"> <span>POS</span></a> -->
              <a href="" class="site_title"> <span>POS</span></a>
			<?php }else{ ?>
			
              <!-- <a href="<?=$admin_url;?>" class="site_title"> <span>DBOS</span></a> -->
              <a href="" class="site_title"> <span>DBOS</span></a>
			<?php } ?>
            </div>

            <div class="clearfix"></div>
			<div class="profile clearfix">
             
              <div class="profile_info">
				<?php $userdata = $this->session->userdata('admin'); ?>
                <h2>Welcome, <?=$userdata['firstname'];?></h2>
                
              </div>
            </div>
            

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
				<?php if($this->auth->check_access('Restaurant manager')) : ?>
						<!--<li><a href="<?php echo $admin_url;?>admin/form/<?=$userdata['id'];?>"><i class="fa fa-home"></i> Profile</a></li> -->
						<li><a href="<?php echo $admin_url;?>orders/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
						<li><a href="<?php echo $admin_url;?>restaurant"><i class="fa fa-home"></i> Control <span class=""></span></a>
							<ul class="nav child_menu">
							<!--  <li><a href="<?php echo $admin_url;?>restaurant">Restaurants</a></li>-->
							</ul>
						</li>
						<li><a href="<?php echo $admin_url;?>orders/previousorders"><i class="fa fa-shopping-cart"></i>Previous Orders </a></li>
							
					<!--	<li><a href="<?php echo $admin_url;?>orders/RequestBill"><i class="fa fa-home"></i> Request Bill <span class=""></span></a></li>-->
					   
						 <li><a href="<?php echo $admin_url;?>orders/SalesChart"><i class="fa fa-home"></i> <?php echo lang('common_sales') ?> <span class=""></span></a>
						 <li><a href="<?php echo $admin_url;?>message/restmessage"><i class="fa fa-comments"></i> Messages </a></li>

						<ul class="nav child_menu">
						
						</ul>
					</li>
				<?php endif; ?>
                <?php if($this->auth->check_access('Deliver manager')) : ?>
					<!--	<li><a href="<?php echo $admin_url;?>deliverypartner/form/<?=$userdata['id'];?>"><i class="fa fa-home"></i> Profile</a></li>-->
						<li><a href="<?php echo $admin_url;?>orders/delpartnerorders"><i class="fa fa-home"></i> Dashboard </a>
							<!--<ul class="nav child_menu">-->
							<!--  <li><a href="<?php echo $admin_url;?>orders/delpartnerorders">New orders</a></li>-->
							<!--  <li><a href="<?php echo $admin_url;?>orders/previousordersdelpartner">Previous Orders</a></li> -->
							<!--</ul>-->
						</li>
						<li><a href="<?php echo $admin_url;?>orders/previousordersdelpartner"><i class="fa fa-shopping-cart"></i> Previous Orders</a>

					<!--	<li><a href="<?php echo $admin_url;?>message/delmessage"><i class="fa fa-home"></i> Messages </a></li> -->
						<li><a href="<?php echo $admin_url;?>deliveryboy"><i class="fa fa-home"></i> Delivery Boys </a></li>
						<li><a href="<?php echo $admin_url;?>deliverypartner/form/<?=$userdata['id'];?>"><i class="fa fa-home"></i> Profile</a></li>
                         <li><a href="<?php echo $admin_url;?>message/delmessage"><i class="fa fa-comments"></i> Messages </a></li>

					<!--	  <li><a href="<?php echo $admin_url;?>orders/RequestBill"><i class="fa fa-home"></i> Request Bill <span class=""></span></a></li> -->
				<?php endif; ?>
                <?php if($this->auth->check_access('Admin')) : ?>
					<!--<li><a><i class="fa fa-home"></i> Dashboard <span class="fa fa-chevron-down"></span></a>-->
					<li><a href="<?php echo $admin_url;?>dashboard"><i class="fa fa-home"></i> Dashboard <span class=""></span></a>
						<ul class="nav child_menu">
					<!--	    <li><a href="<?php echo $admin_url;?>dashboard">Restaurant/Pitstops</a></li> -->
                           <!-- <li><a href="<?php echo $admin_url;?>dashboard/recentinfo">Recent information</a></li>-->
						</ul>
					</li>
					<li><a><i class="fa fa-comments"></i>Messages <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="<?php echo $admin_url;?>message/restmessage">Restaurant message</a></li>
                            <li><a href="<?php echo $admin_url;?>message/delmessage">Delivery partner message</a></li>
						<!--	<li><a href="<?php echo $admin_url;?>message/custmessage">customer message</a></li> -->
                            <li><a href="<?php echo $admin_url;?>message/notifications">Notification messages</a></li>
						</ul>
					</li>
					<li><a href="<?php echo $admin_url;?>deliverypartner"><i class="fa fa-motorcycle"></i>Delivery Partner </a></li>
                   
					<li><a><i class="fa fa-book"></i> <?php echo lang('common_catalog') ?> <span class="fa fa-chevron-down"></span></a>
						<ul class="nav child_menu">
							<li><a href="<?php echo $admin_url;?>categories"><?php echo lang('common_categories') ?></a></li>
                            <li><a href="<?php echo $admin_url;?>restaurant">Restaurants</a></li>
							
						</ul>
					</li>
					<li><a href="<?php echo $admin_url;?>pitstop"><i class="fa fa-map-marker"></i>Delivery Point</a></li>
					<li><a href="<?php echo $admin_url;?>here"><i class="fa fa-map-marker"></i>Here</a></li>
                   <!-- <li><a href="<?php echo $admin_url;?>orders/RequestBill"><i class="fa fa-home"></i> Request Bill <span class=""></span></a></li> -->
					<li><a href="<?php echo $admin_url;?>orders/previousordersdelpartner"><i class="fa fa-shopping-cart"></i>Previous orders</a></li>
					<li><a href="<?php echo $admin_url;?>customers"><i class="fa fa-users"></i>Customers</a></li>
					 
					<li><a href="<?php echo $admin_url;?>admin"><i class="fa fa-user"></i><?php echo lang('common_administrative') ?>  </a></li>
                    <?php endif; ?>
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->

           
          </div>
        </div>
		 <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
               
                <li><a href="<?php echo site_url($this->config->item('admin_folder').'/login/logout');?>"><?php echo lang('common_log_out') ?></a></li>
				 <?php if($this->auth->check_access('Restaurant manager') || $this->auth->check_access('Deliver manager')) : 
					$renewalmsg = $this->Customer_model->GetRenewalmsg(); 
					
					if($renewalmsg != '' && $renewalmsg <= 20){ ?>
					<li role="presentation" class="dropdown">
					  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-envelope-o"></i>
						<span class="badge bg-green">1</span>
					  </a>
					  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu" style="height:auto !important;">
						<li>
							<a>
							<form action="<?php echo site_url($this->config->item('admin_folder').'/orders/renew');?>" method="post">
								<span><?php echo "You have ".$renewalmsg." days to renew your licence. Please renew it."?></span>
								<span class="time"><input type="submit" name="Renew" value="Renew" class="btn btn-xs btn-danger"></span>
							</form>
							</a>
						</li>
					  </ul>
					</li>
				<?php  } endif; ?>
                <?php if($this->auth->check_access('Admin')) : ?>
					<?php $RestSuggestions = $this->Customer_model->GetRestSuggestions(); 
							
						  $PitstopSuggestion = $this->Customer_model->GetPitstopSuggestion(); 
						 // print_r($PitstopSuggestion); exit;
						  $total = count($RestSuggestions['data']) + count($PitstopSuggestion['data']);
					?>
					
					<li role="presentation" class="dropdown">
					  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-envelope-o"></i>
						<span class="badge bg-green"><?=$total;?></span>
					  </a>
					  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
						<?php if(count($RestSuggestions['data']) > 0){ 
						echo "<li><h5><strong>Recently Suggested Restaurants</h5></strong></li>";
						$i=0;
						foreach($RestSuggestions['data'] as $ressuggestion){ if($i > 2){ break ;} ?>
						
						<li>
						  <a>
							<span>
							  <span><?=$ressuggestion->restaurant_name;?></span>
							  <span class="time"><?=$ressuggestion->date;?></span>
							</span>
							
						  </a>
						</li>
						<?php $i++; }} ?>
						<?php if(count($PitstopSuggestion['data']) > 0){ 
						$j=0;
						echo "<li><h5><strong>Recently Suggested Delivery Point</strong></h5></li>";
						foreach($PitstopSuggestion['data'] as $pitsuggestion){ if($j > 2){ break ;} ?>
						
						<li>
						  <a>
							<span>
							  <span><?=$pitsuggestion->restaurant_address;?></span>
							  <span class="time"><?=$pitsuggestion->date;?></span>
							</span>
							
						  </a>
						</li>
						<?php $j++; }} ?>
						<a href="<?php echo site_url($this->config->item('admin_folder').'/customers/suggestions');?>" style="color: red;">View All</a>
					<?php endif; ?>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->
	<?php endif; ?>
<div class="right_col" role="main" style="overflow: auto;min-height:700px;">
	<div class="container">
	
		<?php
		//lets have the flashdata overright "$message" if it exists
		if($this->session->flashdata('message'))
		{
			$message    = $this->session->flashdata('message');
		}
		
		if($this->session->flashdata('error'))
		{
			$error  = $this->session->flashdata('error');
		}
		
		if(function_exists('validation_errors') && validation_errors() != '')
		{
			$error  = validation_errors();
		}
		?>
		
		<div id="js_error_container" class="alert alert-error" style="display:none;"> 
			<p id="js_error"></p>
		</div>
		
		<div id="js_note_container" class="alert alert-note" style="display:none;">
			
		</div>
		
		<?php if (!empty($message)): ?>
			<div class="alert alert-success">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $message; ?>
			</div>
		<?php endif; ?>

		<?php if (!empty($error)): ?>
			<div class="alert alert-error">
				<a class="close" data-dismiss="alert">×</a>
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
	</div>      

	<div class="container">
		 <div id="sound"></div>
		<?php if(!empty($page_title1)):?>
		<div class="page-header">
			<?php if($this->auth->check_access('Restaurant manager')) { ?>
			<h1><?php echo  $page_title; ?></h1>
			<?php } else {?>
			<h1><?php echo  $page_title1; ?></h1>
			<?php }  ?>
		
			<?php if(isset($_SERVER['HTTP_REFERER'])){ ?>
			<!--<span class="pull-right" style="font-size:16px;"><a href="<?=$_SERVER['HTTP_REFERER'];?>">Back</a></span>-->
			<?php } ?>
			</h1>
			
		</div>
		<?php endif;?>
    
	<script>
		setInterval(function(){
			$.ajax({
				url: "<?php echo site_url($this->config->item('admin_folder').'/customers/ShowAlert'); ?>",
				method:"post",
				datatype:'json',
				data:{},
				success:function(data){
					console.log(data);
					if(data != ""){
						playSound('http://app.eatsapp.in/smsalert5_7xL1bIAv',data);
					}
				}
			});
		}, 32*1000);
		
		function playSound(filename,data){   
			document.getElementById("sound").innerHTML='<audio autoplay="autoplay"><source src="' + filename + '.mp3" type="audio/mpeg" /><source src="' + filename + '.ogg" type="audio/ogg" /><embed hidden="true" autostart="true" loop="false" src="' + filename +'.mp3" /></audio>';
			alert(data);
			location.reload();
		}
	</script>