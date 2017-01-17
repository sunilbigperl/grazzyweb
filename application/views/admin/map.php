
  <div class="row">
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Customers</span>
            <div class="count"><?=$countcustomers;?></div>
          </div>
   
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
            <span class="count_top"><i class="fa fa-user"></i> Total Collections</span>
            <div class="count">2,315</div>
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
    var marker, i;
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
		url: "http://localhost/grazzyweb/assets/img/Map.png", // url
		scaledSize: new google.maps.Size(50, 50), // scaled size
		origin: new google.maps.Point(0,0), // origin
		anchor: new google.maps.Point(0, 0) // anchor
	};
	for (i = 0; i < locations1.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations1[i][1], locations1[i][2]),
        map: map,
		icon: icon
      });
      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations1[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
</script>
