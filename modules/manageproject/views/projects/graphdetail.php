<style type="text/css">
#chart-container {
    width: 100%;
    height: auto;
}
</style>
     <div id="chart-container">
        <canvas id="graphCanvas"></canvas>
        
    </div>

    <script>
		var menuid='<?=$menuid?>';
		 
		
		$(document).ready(function () {
            showGraph();
        });

function chartProgress() {/*
var chartProgress = $("#chartProgress");document.getElementById("chartProgress");
  var myChartCircle = new Chart(chartProgress, {
    type: 'doughnut',
    data: {
      labels: ["Africa", 'null'],
      datasets: [{
        label: "Population (millions)",
        backgroundColor: ["#5283ff"],
        data: [68, 48]
      }]
    },
    plugins: [{
      beforeDraw: function(chart) {
        var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx;
    
        ctx.restore();
        var fontSize = (height / 150).toFixed(2);
        ctx.font = fontSize + "em sans-serif";
        ctx.fillStyle = "#9b9b9b";
        ctx.textBaseline = "middle";
    
        var text = "69%",
            textX = web..round((width - ctx.measureText(text).width) / 2),
            textY = height / 2;
    
        ctx.fillText(text, textX, textY);
        ctx.save();
      }
  }],
    options: {
      legend: {
        display: false,
      },
      responsive: true,
      maintainAspectRatio: false,
      cutoutPercentage: 85
    }

  });
*/
  
}
        function showGraph()
        {
            {
               $.post("getplist?securekey="+menuid,
              // $.post("/emz/chart/data.php",
                function (data)
                {	 
                     console.log(data);
                    var short_project_name = [];
				    var project_name = [];
                    var project_cost = [];

                    for (var i in data) {  
                        short_project_name.push(data[i].short_project_name);
                        project_name.push(data[i].project_name);
                        project_cost.push(data[i].project_cost);
                    }

                    var chartdata = {
                        labels: short_project_name,
                        datasets: [
                            {
                                label: 'Project Cost',
                                fill: true,
								lineTension: 0.1,
								backgroundColor:[
								'rgba(255, 99, 132, 0.6)',
								'rgba(54, 162, 235, 0.6)',
								'rgba(255, 159, 64, 0.6)',
								'rgba(255, 206, 86, 0.6)',
								'rgba(75, 192, 192, 0.6)',
								'rgba(153, 102, 255, 0.6)',
								'rgba(255, 159, 64, 0.6)',
								'rgba(205, 99, 132, 0.6)'
							  ],
								 
								pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
								pointHoverBorderColor: "rgba(59, 89, 152, 1)",
								borderWidth:1,
								borderColor:'#777',
								hoverBorderWidth:3,
								hoverBorderColor:'#000',
								data: project_cost
                            }]
                        
                    };
                    var graphTarget = $("#graphCanvas");
                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',tooltips: {
								   callbacks: {
									  label: function(t, d) {
										 var xLabel = d.datasets[t.datasetIndex].label;
										 var yLabel = t.yLabel;
										 return xLabel + ': ' + yLabel + '%';
									  }
								   }
								},
						data: chartdata,options: {
							title:{
							  display:true,
							  text:'Projects List and Cost',
							  fontSize:25
							},
							legend:{
							  display:true,
							  position:'right',
							  labels:{
								fontColor:'#000'
							  }
							},
							layout:{
							  padding:{
								left:0,
								right:0,
								bottom:0,
								top:0
							  }
							},
							onClick: function(c,i) {
    e = i[0];
    console.log(e._index)
    var x_value = this.data.labels[e._index];
    var y_value = this.data.datasets[0].data[e._index];
    console.log(x_value);
    console.log(y_value);
},
							tooltips:{
							  title: function(tooltipItems, data) {
 								return 'Date: ' + tooltipItems.xLabel + ' GMT+2';
							},
							label: function(tooltipItems, data) { // Solution found on https://stackoverflow.com/a/34855201/6660135
								//Return value for label
								return 'Usage: ' + tooltipItems.yLabel*1000 + ' watt';
						}

							},
						/*'onClick' : function (evt, item) {
						//	showGraph();
							//alert('alert');
							console.log ('legend onClick', evt);
						chartProgress(); 
							//console.log('legd item', item);
							return false;
						},*/
						animation: {
								duration: 2000,
							},
					   }
                    });
                });
            }
        }
		function handleClick(evt)
{
    var activeElement = chart.getElementAtEvent(evt);
	alert(activeElement);
}
		function graphClickEvent(event, array){
				if(array[0]){
				   foo.bar;
				}
			}
        </script>
 