<?php
$stmt = $mysqli->prepare("SELECT `height`, `difficulty` FROM `blocks` ORDER BY `height` DESC LIMIT 1");
$stmt->execute();
$stmt->bind_result($height, $diff);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("SELECT SUM(`value`) AS value FROM `inputs` WHERE `type` = 'Generation'");
$stmt->execute();
$stmt->bind_result($totalmlc);
$stmt->fetch();
$stmt->close();
?>
<section>
<div id="logo-region" style="display: none;">
<div class="row">
<div class="small-8 large-3 large-offset-0 small-offset-2 columns logo">
	<a href="/blockpath/" class="internal"><img src="/blockpath/img/logo_small.png"></a>
</div>
<div class="large-9 small-12 columns main-search-box" style="margin-bottom: 0">
	<form action="/blockpath/index.php?engine=search" method="POST" >
		<input id="searchBox" name="query" type="text" placeholder="Search for block, transaction or address in " style="font-size: 1.2em;" size="64" />
	</form>
</div>
</div>
</div>
</section>
</header>
</div>
</div>
<div id="main-region">
	<div>
		<div class="row">
			<div class="small-9 large-8 small-centered columns" style="text-align: center;margin-top: 4.5em">
				<img src="/blockpath/img/logo_big.png">
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-8 small-centered columns" style="text-align: center;margin-top: 0.9em">
				<form action="/blockpath/index.php?engine=search" method="POST" >
					<input id="searchBox" name="query" type="text" placeholder="Search for block, transaction or address in " style="font-size: 1.2em;" size="64" />
				</form>
			</div>
		</div>
		<div class="row">
			<div class="small-12 large-8 small-centered columns" style="text-align: center;margin-top: 9.0em">
				<table align="center">
					<tr>
						<td>Total Blocks: <a href="/block-height/<?php echo $height; ?>"><?php echo $height; ?></a></td><td>Total Coins: <?php echo number_format($totalmlc, 8, '.', ','); ?></td><td>Current Difficulty: <?php echo $diff; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div id="push"></div>
</div>
