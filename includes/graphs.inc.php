<section>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/stock/highstock.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/stock/modules/exporting.js"></script>
<script type="text/javascript">
$(function() {
	$.getJSON('http://www.mmc-chain.com/data/diff.json.php?callback=?', function(data) {
		$('#container').highcharts('StockChart', {
			yAxis : { min : 0 },
			xAxis : { lineColor: "#C0C0C0", lineWidth: 2 },
			rangeSelector : { selected : 1 },
			title : { text : 'MemoryCoins Difficulty' },
			series : [{ name : 'MMC2', data : data, tooltip: { valueDecimals: 8	} }]
		});
	});
	
	$.getJSON('http://www.mmc-chain.com/data/quote.json.php?callback=?', function(data) {
		$('#container2').highcharts('StockChart', {
			yAxis : { min : 0 },
			xAxis : { lineColor: "#C0C0C0", lineWidth: 2 }, 
			rangeSelector : { selected : 1 }, 
			title : { text : 'MemoryCoins Quote' }, 
			series : [{ name : 'BTC', data : data, tooltip: { valueDecimals: 8 } }]
		});
	});

	$.getJSON('http://www.mmc-chain.com/data/profit.json.php?callback=?', function(data) {
		$('#container3').highcharts('StockChart', {
			yAxis : { min : 0 },
			xAxis : { lineColor: "#C0C0C0", lineWidth: 2 },
			rangeSelector : { selected : 1 },
			title : { text : 'MMC Mining Profit (BTC per DAY/HASH per Minute)' },
			series : [{ name : 'BTC', data : data, tooltip: { valueDecimals: 8 } }]
		});
	});
	
	$.getJSON('http://www.mmc-chain.com/data/reward.json.php?callback=?', function(data) {
		$('#container4').highcharts('StockChart', {
			rangeSelector : { selected : 5 },
			title : { text : 'MMC - Mined Block Reward' },
			series : [{ name : 'MMC', data : data, tooltip: { valueDecimals: 3 } }]
		});
	});
	
	$.getJSON('http://www.mmc-chain.com/data/volume.json.php?callback=?', function(data) {
		$('#container5').highcharts('StockChart', {
			rangeSelector : { selected : 5 },
			title : { text : 'MMC Coins Amount' },
			series : [{ name : 'MMC', data : data, tooltip: { valueDecimals: 3 } }]
		});
	});	
});
</script>
<div id="logo-region">
  <div class="row">
    <div class="small-8 large-3 large-offset-0 small-offset-2 columns logo">
	<a href="http://www.mmc-chain.com" class="internal"><img src="/img/logo_small.png" alt="" /></a>
    </div>
    <div class="large-9 small-12 columns main-search-box" style="margin-bottom: 0">
	<form action="/?engine=search" method="POST" >
		<input id="searchBox" name="query" type="text" placeholder="Search for block, transaction or address in " style="font-size: 1.2em;" size="64" />
	</form>
	</div>
</div>
</div>
</section>
</header>
<div id="main-region">
	<div class="row">
		<div class="large-12 columns" style="text-align:center;">
			<div id="containers" style="display:block;">
				<div id="container" style="height: 500px; width: 1000px"></div>
				<br /><br /><br />
				<div id="container2" style="height: 500px; width: 1000px"></div>
				<br /><br /><br />
				<div id="container3" style="height: 500px; width: 1000px"></div>
				<br /><br /><br />
				<div id="container4" style="height: 500px; width: 1000px"></div>
				<br /><br /><br />
				<div id="container5" style="height: 500px; width: 1000px"></div>				
			</div>
		</div>
	</div>
</div>
<div id="push"></div>
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</div>
