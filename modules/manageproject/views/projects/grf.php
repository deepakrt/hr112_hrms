<style type="text/css">
#chart-container {
    width: 100%;
    height: auto;
}
</style>
     <div id="chart-container">
        <canvas id="graphCanvas"></canvas>
        <canvas id="chartProgress"></canvas>
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
                    var project_name = [];
                    var project_cost = [];

                    for (var i in data) {  
                        project_name.push(data[i].project_name);
                        project_cost.push(data[i].project_cost);
                    }

                    var chartdata = {
                        labels: project_name,
                        datasets: [
                            {
                                label: 'Project Cost',
                                fill: true,
								lineTension: 0.1,
								backgroundColor: "rgba(59, 89, 152, 0.75)",
								borderColor: "rgba(59, 89, 152, 1)",
								pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
								pointHoverBorderColor: "rgba(59, 89, 152, 1)",
                                data: project_cost
                            }
                        ]
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
						'onClick' : function (evt, item) {
						//	showGraph();
							alert('alert');
							console.log ('legend onClick', evt);
						chartProgress(); 
							//console.log('legd item', item);
							return false;
						},
						animation: {
								duration: 3000,
							},
					   }
                    });
                });
            }
        }
		
		function graphClickEvent(event, array){
				if(array[0]){
				   foo.bar;
				}
			}
        </script>
 