
  <div class="row">
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Customers</span>
            <div class="count"><?=$countcustomers;?></div>
          </div>
   
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Orders</span>
            <div class="count"><?=$totalorders;?></div>
          </div>
		  
		   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Food outlets</span>
            <div class="count"><?=$foodoutlets;?></div>
          </div>
		   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Previous Month Orders</span>
            <div class="count"><?=$previousorders;?></div>
          </div>
         
  </div>
  <div class="row" style="margin-top:40px;">
       <div class="col-md-12 col-sm-12 col-xs-12">
            
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDKUsjUabpe8-dBedcqnKchPAVfsNqFnlE"></script>
                <div id="map" style="height:520px;width:100%;"></div>

       </div>
  </div>

<script type="text/javascript">
    var locations = <?=$restaurant;?>;
	var locations1 = <?=$pitstops;?>;
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(19.0760, 72.8777),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var infowindow = new google.maps.InfoWindow();
    var marker, marker1, i, j;
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
		url: "http://app.eatsapp.in/assets/img/Map.png", // url
		scaledSize: new google.maps.Size(50, 50), // scaled size
		//origin: new google.maps.Point(0,0), // origin
		//anchor: new google.maps.Point(0, 0) // anchor
	};
	for (j = 0; j < locations1.length; j++) {  
      marker1 = new google.maps.Marker({
        position: new google.maps.LatLng(locations1[j][1], locations1[j][2]),
        map: map,
		icon: icon
      });
      google.maps.event.addListener(marker1, 'click', (function(marker1, j) {
        return function() {
          infowindow.setContent(locations1[j][0]);
          infowindow.open(map, marker1);
        }
      })(marker1, j));
    }
</script>
