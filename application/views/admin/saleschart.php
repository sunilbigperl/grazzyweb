
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<div id="chart_div" style="margin-top:40px;"></div>
<script>

$(document).ready(function(){
$.ajax({
  url:"<?php echo site_url($this->config->item('admin_folder').'/orders/data'); ?>",
  dataType:"JSON",
  success:function(result){
    var array1=[];
    for(let i=0;i<result.length/8;i++){
      array1.push([result[i].menu,parseInt(result[i].price)]);
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
      data.addColumn('string', 'month');
      data.addColumn('number', 'Categories');
      //data.addColumn('number', 'Category2');

      dataArray=[];
      dataArray=array1;
      
      data.addRows(dataArray);
      
      var options = {
        title: 'Sales Chart',
        colors: ['#9575cd', '#33ac71'],
        hAxis: {
          title: 'Categories',
        },
        vAxis: {
          title: 'Sales amount of items( in Rs)'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>


