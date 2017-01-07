
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="chart_div" style="margin-top:40px;"></div>
<script>
google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawColColors);

function drawColColors() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'month');
      data.addColumn('number', 'Category1');
      data.addColumn('number', 'Category2');

      data.addRows([
        ['jan', 25000, 24000],
        ['feb', 16000, 11000],
      ]);

      var options = {
        title: 'Sales Chart',
        colors: ['#9575cd', '#33ac71'],
        hAxis: {
          title: 'Month',
        },
        vAxis: {
          title: 'Sales amount of items( in Rs)'
        }
      };

      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>