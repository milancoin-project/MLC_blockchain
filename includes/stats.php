<?php
	$node_data = 'var node_data = [["timestamp","Nodes"]';
	$json = file_get_contents("./data/getaddr-latest-chart.json");
	$obj = json_decode($json);
	foreach ($obj as $key => $value) {
		$node_data .= ',[new Date('.($key*1000).'),'.$value.']';
	}
	$node_data .= '];';

	$json = file_get_contents("./data/getaddr-latest.json");
	$obj = json_decode($json);
	$countries = $obj->{"countries"};
	$chart_data = 'var chart_data = [["country", "nodes"]';
	$max = 0;
	foreach ($countries as $key => $value) {
		$chart_data .= ',["'.$key.'",'.$value.']';
		$max = max($max, $value);
	}
	$chart_data .= '];';
	$map_ticks = 'var map_ticks = ['.($max).','.(($max/8)*7).','.(($max/8)*6).','.(($max/8)*5).','.(($max/8)*4).','.(($max/8)*3).','.(($max/8)*2).','.(($max/8)*1).'];';
		
	$lastUpdate = $obj->{"time"};
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>MLC Network</title>
</head>
<body>
<div>Last Update: <? echo date("F j, Y, g:i a",$lastUpdate);?></div>
<div id="map" style="width: 800px;"></div>
<div id="chart" style="width: 800px;"></div>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
var region = "world";
var total_count = 150;

<? echo $chart_data."\n"; ?>
<? echo $map_ticks."\n"; ?>
<? echo $node_data."\n"; ?>

var threshold_count = map_ticks[map_ticks.length - 1];
map_ticks.push(1); // 10 ticks, starts at 1
map_ticks = map_ticks.reverse();

google.load("visualization", "1", {"packages": ["corechart", "geochart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable(chart_data);

    var mapOptions = {
        colorAxis: {
            colors: [
                "#ffffcc", "#ffeda0", "#fed976", "#feb24c", "#fd8d3c",
                "#fc4e2a", "#e31a1c", "#bd0026", "#800026"
            ],
            values: map_ticks
        },
        legend: {
            textStyle: {color: "#303030", fontSize: 11}
        },
        tooltip: {
            textStyle: {color: "#303030", fontSize: 11}
        },
        region: region
    };

    var map = new google.visualization.GeoChart(document.getElementById("map"));
    map.draw(data, mapOptions);
	
	var data = google.visualization.arrayToDataTable(node_data);

    var options = {
		title: 'Nodes online in a 1-day period',
		legend: 'none'
    };

     var chart = new google.visualization.ScatterChart(document.getElementById('chart'));
     chart.draw(data, options);
};
</script>
</body>
</html>
