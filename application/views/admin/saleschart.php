
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
    
    for(let i=0;i<result.length;i++){
      array1.push([result[i].day,parseInt(result[i].daily_total)]);
    }

   
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
        colors: ['#9575cd', '#33ac71'],
        hAxis: {
          title: '',

        },
        vAxis: {
          title: 'Sales amount of items( in Rs)'
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>





