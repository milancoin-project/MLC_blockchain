<?php

function apiquery($field) {
	global $mysqli;
	
	$result = array();
	$txrows = "";
	$totalvalue = 0;
	$received_txs = 0;
	$received_mlc = 0;
	$sent_txs = 0;
	$sent_mlc = 0;

	$stmt = $mysqli->prepare("SELECT `keys`.`hash160`, `keys`.`address`, `keys`.`firstseen`, `blocks`.`height`, `blocks`.`time` FROM `keys` LEFT JOIN `blocks` ON (`blocks`.`hash` = `keys`.`firstseen`) WHERE `address` LIKE ? LIMIT 1");
	$stmt->bind_param('s', $field);
	$stmt->execute();
	$stmt->bind_result($hash160, $address, $firstseen, $height, $time);
	$stmt->fetch();
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

		$txrows .= '
				<tr>
					<td class="blocksHash">
						<a href="/block-height/' . $blocknum . '" class="internal transactionLink">' . $blocknum . '</a></td>
					<td class="transactionHash">
						<a href="/tx/' . $tx . '" class="internal transactionLink">' . $tx . '</a>
					</td>
					<td class="hide-for-small transactedDate">' . gmdate("M j Y g:i:s A", $time2) . '</td>
					<td class="transactedAmount"><img class="transactionDirection" src="/blockpath/img/' . $type .'.png" /></td>
					<td class="transactedAmount" style="text-align: right;">' . number_format($value, 8, '.', ',') . ' MLC</td>
				</tr>';	
	}
	
	$balance = bcsub($received_mlc, $sent_mlc, 8);
	
	$result = array(
					"address" => $address,
					"hash160" => $hash160,
					"firstseen" => $firstseen,
					"height" => $height,
					"time" => $time,
					"tx_in" => $received_txs,
					"mlc_in" => $received_mlc,
					"tx_out" => $sent_txs,
					"mlc_out" => $sent_mlc,
					"balance" => $balance
					);

	header('Content-type: text/javascript');
	echo pretty_json(json_encode($result));
}
?>