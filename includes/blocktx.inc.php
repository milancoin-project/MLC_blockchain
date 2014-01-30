<?php
$input = "";
$output = "";
$totalinput = 0;
$totaloutput = 0;
$totalsalary = 0;

$stmt = $mysqli->prepare("SELECT `transactions`.*, SUM(`outputs`.`value`) FROM `transactions` JOIN `outputs` ON (`outputs`.`tx` = ?) WHERE `transactions`.`hash` = ? LIMIT 1");
$stmt->bind_param('ss', $tx2hash, $tx2hash);
$stmt->execute();

$stmt->bind_result($txhash, $block, $confirmations, $txtime, $txfee, $txraw, $totalout);
$stmt->fetch();

if (!$txhash) {
	$stmt->close();
	header('Location: ?engine=404');
}

$stmt->close();

$stmt = $mysqli->prepare("SELECT SUM(`inputs`.`value`) as value, `keys`.`address` FROM `inputs` LEFT JOIN `keys` ON (`keys`.`hash160` = `inputs`.`hash160`) WHERE `inputs`.`tx` = ? GROUP BY `keys`.`address` ORDER BY `inputs`.`index` ASC");
$stmt->bind_param('s', $txhash);
$stmt->execute();
$stmt->bind_result($invalue, $inaddress);

while ($stmt->fetch()) {
	if ($inaddress) {
		$input .= '
					<tr>
                        <td><a href="http://www.mmc-chain.com/?engine=blockexplorer&address=' . $inaddress .'" class="internal">' . $inaddress .'</a></td>
                        <td style="text-align: right;">' . number_format($invalue, 8, '.', ',') .' MMC</td>
                    </tr>';
	} else {
		$input .= '
					<tr>
                        <td>Mined Block Reward</td>
						<td style="text-align: right;">' . number_format($invalue, 8, '.', ',') .' MMC</td>
                    </tr>';
	}
	
	$totalinput = bcadd($totalinput, $invalue, 8);
}
	
$stmt->close();

$stmt = $mysqli->prepare("SELECT SUM(`outputs`.`value`) value, `keys`.`address` FROM `outputs` LEFT JOIN `keys` ON (`keys`.`hash160` = `outputs`.`hash160`) WHERE `outputs`.`tx` = ? GROUP BY `keys`.`address` ORDER BY `outputs`.`index`");
$stmt->bind_param('s', $txhash);
$stmt->execute();
$stmt->bind_result($outvalue, $outaddress);

while ($stmt->fetch()) {
	$output .= '
				<tr>
                    <td><a href="http://www.mmc-chain.com/?engine=blockexplorer&address=' . $outaddress .'" class="internal">' . $outaddress .'</a></td>
                    <td style="text-align: right;">' . number_format($outvalue, 8, '.', ',') .' MMC</td>
                </tr>';

	if (!$inaddress && substr( $outaddress, 0, 4 ) === "MVTE") {
		$totalsalary = bcadd($totalsalary, $outvalue, 8);
	} else {
		$totaloutput = bcadd($totaloutput, $outvalue, 8);
	}
}
	
$stmt->close();


$totalfees = bcsub($totalinput, $totaloutput, 8);
if ($totalfees < 0) $totalfees = -$totalfees;
?>
<section>
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
</div></div>
<div id="main-region"><div><div id="transaction-details">
	<section>
		<div id="transaction-region"><div class="transaction-details"><div class="row">
    <div class="large-12 columns">
        <ul class="breadcrumbs">
            <li><a class="internal" href="http://www.mmc-chain.com/?engine=blockexplorer">blockchain</a></li>
            <li class="unavailable"><a href="http://www.mmc-chain.com/?engine=blockexplorer" class="internal">Transactions</a></li>
            <li class="current"><a href="http://www.mmc-chain.com/?engine=blockexplorer&tx=<?php echo $txhash; ?>" class="internal">#<?php echo $txhash; ?></a></li>
        </ul>
        <h2>Transaction <small class="hash">#<?php echo $txhash; ?></small></h2>
    </div>
</div>
<div class="row">
    <div class="large-7 columns">
        <table class="fullwidth">
            <colgroup>
                <col width="0%">
                <col width="100%">
            </colgroup>
            <thead><tr><th class="tableRowLabel" colspan="2">Transaction Details</th></tr></thead>
            <tbody>
                <tr><td class="tableRowLabel">Hash</td><td class="tableRowValue hashSize"><?php echo $txhash; ?></td></tr>
                <tr><td class="tableRowLabel">Time</td><td class="tableRowValue"><?php echo gmdate("M j Y g:i:s A", $txtime); ?></td></tr>
                <tr><td class="tableRowLabel">Confirmations</td>
					<td class="tableRowValue"><span class="success label"><?php echo $confirmations; ?> Confirmations</span></td>
				</tr>
                <tr>
					<td class="tableRowLabel">In Blocks</td><td class="tableRowValue hashSize">
						<a href="http://www.mmc-chain.com/?engine=blockexplorer&hash=<?php echo $block; ?>" class="internal"><?php echo $block; ?></a><br />
					</td>
                </tr>
                <tr><td class="tableRowLabel">Total Input</td><td class="tableRowValue"><?php echo number_format($totalinput, 8, '.', ','); ?> MMC</td></tr>
                <tr><td class="tableRowLabel">Total Output</td><td class="tableRowValue"><?php echo number_format($totaloutput, 8, '.', ','); ?> MMC</td></tr>
<?php if (!$inaddress) { ?>
				<tr><td class="tableRowLabel">Staff Salary</td><td class="tableRowValue"><?php echo number_format($totalsalary, 8, '.', ','); ?> MMC</td></tr>
<?php } ?>
                <tr><td class="tableRowLabel">Block Fees</td><td class="tableRowValue"><?php echo number_format($totalfees, 8, '.', ','); ?> MMC</td></tr>
            </tbody>
        </table>
        
    </div>
    <div class="large-5 columns" style="height: 100%">
        <div class="inputs-box arrow_box">
            <div id="inputs">
                <b>Inputs</b><br>
                <table class="fullwidth bare transactionAddressesBox">
                    <tbody>
						<?php echo $input; ?>					
					</tbody>
				</table>
            </div>
        </div>
        <div class="outputs-box">
            <div id="outputs">
                <b>Outputs</b><br>
                <table class="fullwidth bare transactionAddressesBox">
                    <tbody>
						<?php echo $output; ?>
					</tbody>
				</table>
            </div>
        </div>
    </div>
</div>
</div></div>
	</section>
</div>
</div></div>
<div id="push"></div>
</div>
