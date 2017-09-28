
<div class="container" style="margin-top:20px;margin-bottom:20px;">
  <form class="form-inline" action="<?php echo site_url($this->config->item('admin_folder').'/orders/SalesChart'); ?>" method="post">
    <div class="form-group">
      <label for="from date"><strong>from date:</strong></label>
      <input type="date" class="form-control" id="fromdate" name="fromdate">
    </div>
    <div class="form-group">
      <label for="to date"><strong>To date:</strong></label>
      <input type="date" class="form-control" id="todate" name="todate">
    </div>
    
    <div class="form-group"><input type="submit" class="btn btn-primary" value="Go" name="action"></div>
<div  style="margin-top:20px;">
      <div class="form-group"><input type="submit" class="btn btn-primary" value="SixMonth" name="action"></div>
      <div class="form-group"><input type="submit" class="btn btn-primary" value="ThreeMonth" name="action"></div>
      <div class="form-group"><input type="submit" class="btn btn-primary" value="PreviousMonth" name="action"></div>
      <div class="form-group"><input type="submit" class="btn btn-primary" value="CurrentMonth" name="action"></div>
    
  </form>
</div>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div id="chart_div" style="margin-top:40px;"></div>
<script>

$(document).ready(function(){

$.ajax({
  url:"<?php echo site_url($this->config->item('admin_folder').'/orders/data'); ?>",
  dataType:"JSON",
  success:function(result){
     //alert(result);

  var array1=[];
	var fromdate = <?php echo  '"'. $fromdate . '"'; ?>;
  var todate = <?php echo  '"'. $todate . '"'; ?>;
	// 	var current_date = new Date(result[0].day);
  //var current_date = new Date('2017-09-01');
  var current_date = new Date(fromdate);	
  
  // var end_date = new Date(result[result.length-1].day);
  var end_date = new Date(todate);
 
	var pushed=false;
	
	var i=10;
	
	
	while(current_date<=end_date)
	{
		
		
		pushed = false;
		
		for(let i=0;i<result.length;i++){
			
    var temp_date = new Date(result[i].day);

		console.log("dates compare "+temp_date+" "+current_date+" "+result[i].day+" "+(current_date==temp_date));
     
    if(current_date.getTime()==temp_date.getTime())
			{
         
				// console.log("push date"+current_date.getFullYear()+"-"+(current_date.getMonth()+1)+"-"+current_date.getDate()+"val"+result[i].daily_total);
				array1.push([current_date.getFullYear()+"-"+(current_date.getMonth()+1)+"-"+current_date.getDate(),parseInt(result[i].daily_total)]);
        pushed = true;
				break;

			}

		}	
		

		if(!pushed)
		{ 
      
     //console.log("push date"+current_date.getFullYear()+"-"+(current_date.getMonth()+1)+"-"+current_date.getDate());
			array1.push([current_date.getFullYear()+"-"+(current_date.getMonth()+1)+"-"+current_date.getDate(),0]);
     
		}
   
		current_date.setDate(current_date.getDate() + 1);
   
	}    
 
	  console.log("array"+array1);
   
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(function(){
                                    drawColColors(array1)
                                });
     }
     });
 });


function drawColColors(array1) {
  var data = new google.visualization.DataTable();
      data.addColumn('string', '');
      data.addColumn('number', '');
      //data.addColumn('number', 'Category2');

      dataArray=[];
      dataArray=array1;
      
      data.addRows(dataArray);
      
      var options = {
        title: 'Sales Chart',
        fontSize:10,
        width:6000,
        titleFontSize:12,
       
        colors: ['#9575cd', '#33ac71'],
        hAxis: {
          title: '',

         
        },
        vAxis: {
           titleFontSize:12,
          title: 'Sales amount of items( in Rs)'
        } 
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>





