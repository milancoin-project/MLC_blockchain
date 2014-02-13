<?php

$txrows = "";

if ($blockhash) {
	$stmt = $mysqli->prepare("SELECT * FROM `blocks` WHERE `hash` LIKE ? LIMIT 1");
	$stmt->bind_param('s', $blockhash);
} else {
	$stmt = $mysqli->prepare("SELECT * FROM `blocks` WHERE `height` LIKE ? LIMIT 1");
	$stmt->bind_param('s', $blockid);
}

$stmt->execute();
$stmt->bind_result($height, $hash, $confirmations, $size, $version, $merkleroot, $time, $nonce, $bits, $difficulty, $totalvalue, $totalfee, $transactions, $previousblockhash, $nextblockhash, $blockraw);
$stmt->fetch();

if (!$height) {
	$stmt->close();
	header('Location: ?engine=404');
}

$stmt->close();

$stmt = $mysqli->prepare("SELECT `transactions`.`hash`, `transactions`.`fee`, `transactions`.`time`, SUM(`outputs`.`value`) AS value, `outputs`.`type`, `outputs`.`index` FROM `transactions` LEFT JOIN `outputs` ON (`outputs`.`tx` = `transactions`.`hash`) WHERE `transactions`.`block` LIKE ? GROUP BY `transactions`.`hash` ORDER BY `outputs`.`type` DESC, `outputs`.`index` ASC");
$stmt->bind_param('s', $hash);
$stmt->execute();
$stmt->bind_result($txhash, $txfee, $txtime, $txoutvalue, $txtype, $txindex);

while ($stmt->fetch()) {
	if ($txfee < 0) $txfee = - $txfee;
		
	$tvalue = bcadd($tvalue, $txoutvalue, 8);
	
	if ($txtype == "Pubkey" && $txindex == 0) {
		$tfee = $txfee;
		$reward = bcsub($txoutvalue, $totalfee, 8);
		$rewhash = $txhash;
	} else {
		$tfee = bcadd($tfee, $txfee, 8);
	}
	
	$txrows .= '
		<tr>
			<td class="transactionHash"><a href="/blockpath/index.php?engine=blockexplorer&tx=' . $txhash . '" class="internal transactionLink">' . $txhash . '</a></td>
			<td class="transactedDate hide-for-small">' . gmdate("M j Y g:i:s A", $txtime) . '</td>
			<td class="transactedAmount hide-for-small">' . number_format($txfee, 8, '.', ',') . '</td>
			<td class="transactedAmount hide-for-small">' . number_format($txoutvalue, 8, '.', ',') . ' MLC</td>
		</tr>';	
}

$stmt->close();

if ($reward > 280) {
	$stmt = $mysqli->prepare("SELECT SUM(`outputs`.`value`) value, `keys`.`address` FROM `outputs` LEFT JOIN `keys` ON (`keys`.`hash160` = `outputs`.`hash160`) WHERE `outputs`.`tx` = ? AND `keys`.`address` LIKE 'MVTE%'");
	$stmt->bind_param('s', $rewhash);
	$stmt->execute();
	$stmt->bind_result($rewvalue, $rewaddress);
	$stmt->fetch();
	$stmt->close();
	
	$reward = bcsub($reward, $rewvalue, 8);
	$tfee = bcsub($tfee, $rewvalue, 8);
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
<div id="main-region"><div><div id="block">
	<section>
		<div id="block-region"><div class="block-details"><div class="row">
    <div class="large-12 columns">
        <ul class="breadcrumbs">
            <li><a class="internal" href="/blockpath/index.php?engine=blockexplorer">blockchain</a></li>
            <li class="current"><a href="/blockpath/index.php?engine=blockexplorer&blockid=<?php echo $height; ?>" class="internal">Block #<?php echo $height; ?></a></li>
        </ul>
        <h2>Block #<?php echo $height; ?></h2>
        <table width="100%">
            <colgroup>
                <col width="0%">
                <col width="100%">
            </colgroup>
            <tbody>
            <tr>
            <td class="tableRowLabel">Height:</td>
            <td class="tableRowValue"><?php echo $height; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Solved at:</td>
                <td class="tableRowValue"><?php echo gmdate("M j Y g:i:s A", $time); ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Confirmations:</td>
                <td class="tableRowValue"><span class="success label"><?php echo $confirmations; ?> confirmations</span></td>
            </tr>			
            <tr>
                <td class="tableRowLabel">Hash:</td>
                <td class="tableRowValue"><?php echo $hash; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Previous Block:</td>
                <td class="tableRowValue"><a href="/blockpath/index.php?engine=blockexplorer&hash=<?php echo $previousblockhash; ?>" class="internal"><?php echo $previousblockhash; ?></a></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Next Block:</td>
                <td class="tableRowValue"><a href="/blockpath/index.php?engine=blockexplorer&hash=<?php echo $nextblockhash; ?>" class="internal"><?php echo $nextblockhash; ?></a></td>
            </tr>            
            <tr>
                <td class="tableRowLabel">Merkle root:</td>
                <td class="tableRowValue"><?php echo $merkleroot; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Version:</td>
                <td class="tableRowValue"><?php echo $version; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Size:</td>
                <td class="tableRowValue"><?php $size = format_size($size); echo $size; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Nonce:</td>
                <td class="tableRowValue"><?php echo $nonce; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Bits:</td>
                <td class="tableRowValue"><?php echo $bits; ?></td>
            </tr>			
            <tr>
                <td class="tableRowLabel">Difficulty:</td>
                <td class="tableRowValue"><?php echo $difficulty; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">No of Transactions:</td>
                <td class="tableRowValue"><?php echo $transactions; ?></td>
            </tr>
            <tr>
                <td class="tableRowLabel">Total Value:</td>
                <td class="tableRowValue"><?php echo number_format($tvalue, 8, '.', ','); ?> MLC</td>
            </tr>			
            <tr>
                <td class="tableRowLabel">Total Fees:</td>
                <td class="tableRowValue"><?php echo number_format($tfee, 8, '.', ','); ?> MLC</td>
            </tr>
            <tr>
                <td class="tableRowLabel">Block Reward</td>
                <td class="tableRowValue"><?php echo number_format($reward, 8, '.', ','); ?> MLC</td>
            </tr>
        </tbody></table>
    </div>
</div>
</div></div>
	</section>
    <section>
        <div id="transactions-region"><div><div class="row">
    <div class="large-12 columns">
        <h3>Transactions
            <small>transactions included in this block</small>
        </h3>
        <table class="fullwidth hover">
            <colgroup>
                <col width="100%">
                <col class="hide-for-small" width="0%">
                <col class="hide-for-small" width="0%">
                <col width="0%">
            </colgroup>
            <thead>
            <tr><th class="transactionHash">Transaction Hash</th>
            <th class="transactionDate hide-for-small">Datetime</th>
            <th class="transactedAmount hide-for-small">Fee</th>
            <th class="transactedAmount">Transacted Amount</th>
            </tr></thead>
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
</div>
