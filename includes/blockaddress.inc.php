<?php

$txrows = "";
$totalvalue = 0;
$received_txs = 0;
$received_mlc = 0;
$sent_txs = 0;
$sent_mlc = 0;

$stmt = $mysqli->prepare("SELECT `keys`.`hash160`, `keys`.`address`, `keys`.`firstseen`, `blocks`.`height`, `blocks`.`time` FROM `keys` LEFT JOIN `blocks` ON (`blocks`.`hash` = `keys`.`firstseen`) WHERE `address` LIKE ? LIMIT 1");
$stmt->bind_param('s', $address);
$stmt->execute();
$stmt->bind_result($hash160, $address, $firstseen, $height, $time);
$stmt->fetch();

if (!$hash160) {
	$stmt->close();
	header('Location: ?engine=404');
}

$stmt->close();

$stmt = $mysqli->prepare("
SELECT `inputs`.`type` AS txtype, 'debit' AS type, `inputs`.`tx` AS tx, SUM(`inputs`.`value`) AS value, `inputs`.`index` AS id, `transactions`.`block` AS block, `blocks`.`height` AS blocknum, `blocks`.`time` AS time FROM `inputs`, `transactions`, `blocks` WHERE `inputs`.`hash160` = ? AND `inputs`.`tx` = `transactions`.`hash` AND `transactions`.`block` = `blocks`.`hash` GROUP BY tx 

UNION ALL

SELECT `outputs`.`type` AS txtype, 'credit' AS type, `outputs`.`tx` AS tx, SUM(`outputs`.`value`) AS value, `outputs`.`index` AS id, `transactions`.`block` AS block, `blocks`.`height` AS blocknum, `blocks`.`time` AS time FROM `outputs`, `transactions`, `blocks` WHERE `outputs`.`hash160` = ? AND `outputs`.`tx` = `transactions`.`hash` AND `transactions`.`block` = `blocks`.`hash` GROUP BY tx 

ORDER BY time DESC");

$stmt->bind_param('ss', $hash160, $hash160);
$stmt->execute();
$stmt->bind_result($txtype, $type, $tx, $value, $id, $block, $blocknum, $time2);

while ($stmt->fetch()) {

	if($type == "credit") {
		$received_txs++;
		$received_mlc = bcadd($received_mlc, $value, 8);
	} elseif($type == 'debit') {
		$sent_txs++;
		$sent_mlc = bcadd($sent_mlc, $value, 8);
	}
			
	//TX Output
/*	
	$stmt = $mysqli->prepare("SELECT DISTINCT `outputs`.`type` AS type, `outputs`.`value` AS value, `keys`.`address` AS address FROM `outputs` LEFT JOIN `keys` ON (`outputs`.`hash160` = `keys`.`hash160`) WHERE `outputs`.`tx` = '" . $rows['tx'] . "' ORDER BY `outputs`.`index`";
	$outputresult = mysql_query($query) or die(mysql_error());
	while($outputrows = mysql_fetch_array($outputresult)) {
		print_r($outputrows);
	}
*/
	// TX Input
/*	
	$query  = "SELECT DISTINCT `inputs`.`value` AS value, `inputs`.`type` AS type, `keys`.`address` AS address FROM `inputs` LEFT JOIN `keys` ON (`inputs`.`hash160` = `keys`.`hash160`) WHERE `inputs`.`tx` = '" . $rows['tx'] . "' ORDER BY `inputs`.`index`";

	$inputresult = mysql_query($query) or die(mysql_error());
	while($inputrows = mysql_fetch_array($inputresult)) {
		print_r($inputrows);
	}
*/
	$txrows .= '
				<tr>
					<td class="blocksHash">
						<a href="/blockpath/index.php?engine=blockexplorer&blockid=' . $blocknum . '" class="internal transactionLink">' . $blocknum . '</a></td>
					<td class="transactionHash">
						<a href="/blockpath/index.php?engine=blockexplorer&tx=' . $tx . '" class="internal transactionLink">' . $tx . '</a>
					</td>
					<td class="hide-for-small transactedDate">' . gmdate("M j Y g:i:s A", $time2) . '</td>
					<td class="transactedAmount"><img class="transactionDirection" src="/blockpath/img/' . $type .'.png" /></td>
					<td class="transactedAmount" style="text-align: right;">' . number_format($value, 8, '.', ',') . ' MLC</td>
				</tr>';	
}
								 
?>
<section>
<div id="logo-region">
  <div class="row">
    <div class="small-8 large-3 large-offset-0 small-offset-2 columns logo">
	<a href="/blockpath/" class="internal"><img src="/blockpath/img/logo_small.png" alt="" /></a>
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
</div></div>
<div id="main-region"><div><div id="block">
	<section>
		<div id="address-region"><div class="address-details"><div class="row">
    <div class="large-12 columns">
        <ul class="breadcrumbs">
            <li><a class="internal" href="/blockpath/index.php?engine=blockexplorer">blockchain</a></li>
            <li class="unavailable"><a href="" class="internal">Address</a></li>
            <li class="current"><a href="" class="internal"><?php echo $address; ?></a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="large-9 columns">
        <h2>Address <small><?php echo $address; ?></small></h2>
        <table width="100%">
            <colgroup>
                <col width="0%">
                <col width="100%">
            </colgroup>
            <tbody><tr><td class="tableRowLabel">Address</td><td class="tableRowValue"><?php echo $address; ?></td></tr>
            <tr><td class="tableRowLabel">Hash160</td><td class="tableRowValue"><?php echo $hash160; ?></td></tr>
			<tr>
				<td class="tableRowLabel">FirstSeen</td><td class="tableRowValue">
					<a href="/blockpath/index.php?engine=blockexplorer&hash=<?php echo $firstseen; ?>" class="internal transactionLink"><?php echo gmdate("M j Y g:i:s A", $time); ?> - Block n. <?php echo $height; ?></a>
				</td>
			</tr>
            <tr><td class="tableRowLabel">Total Received</td><td class="tableRowValue"><?php echo number_format($received_mlc, 8, '.', ','); ?> MLC</td></tr>
            <tr><td class="tableRowLabel">Total Sent</td><td class="tableRowValue"><?php echo number_format($sent_mlc, 8, '.', ','); ?> MLC</td></tr>
			 <tr><td class="tableRowLabel">Current Balance</td><td class="tableRowValue"><b><?php echo number_format($received_mlc - $sent_mlc, 8, '.', ','); ?> MLC</b></td></tr>
        </tbody></table>
    </div>
    <div id="address-qrcode" class="large-3 columns" style="padding-top: 25px;">
		<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?php echo $address; ?>&choe=UTF-8" title="<?php echo $address; ?>" />
	</div>
</div>
</div></div>
	</section>
    <section>
        <div id="transactions-region"><div><div class="row">
    <div class="large-12 columns">
        <h3>Transactions
            <small>transactions this address relates to</small>
        </h3>
        <table class="fullwidth hover">
            <colgroup>
				<col width="5%">
                <col width="60%">
                <col width="10%">
				<col width="4%">
                <col width="21%">
            </colgroup>
            <thead>
				<tr>
					<th class="blockAmount">Block</th>
					<th class="transactionHash">Transaction Hash</th>
					<th class="transactionDate">Datetime</th>
					<th class="transactedAmount" colspan="2">Transacted Amount</th>
				</tr>
			</thead>
            <tbody>
				<?php echo $txrows; ?>
			</tbody>
        </table>
    </div>
</div>
</div></div>
    </section>
</div>
</div></div>
<div id="push"></div>
</div>
