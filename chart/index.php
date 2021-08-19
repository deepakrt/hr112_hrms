<!DOCTYPE html>
<html>
<head>
<title>Creating Dynamic Data Graph using PHP and Chart.js</title>
<style type="text/css">
 
#chart-container {
    width: 100%;
    height: auto;
}
</style>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/Chart.min.js"></script>


</head>
<body>
    <div id="chart-container">
        <canvas id="graphCanvas"></canvas>
    </div>

    <script>
        $(document).ready(function () {
            showGraph();
        });


        function showGraph()
        {
            {
                $.post("data.php",
                function (data)
                {
					alert(data);
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
                                backgroundColor: '#49e2ff',
                                borderColor: '#46d5f1',
                                hoverBackgroundColor: '#CCCCCC',
                                hoverBorderColor: '#666666',
                                data: project_cost
                            }
                        ]
                    };

                    var graphTarget = $("#graphCanvas");

                    var barGraph = new Chart(graphTarget, {
                        type: 'bar',
                        data: chartdata
                    });
                });
            }
        }
        </script>
</body>
</html>