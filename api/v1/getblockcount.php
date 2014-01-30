<?php

function apiquery($field = FALSE) {
	global $mysqli;

	$stmt = $mysqli->prepare("SELECT `height` FROM `blocks` ORDER BY `height` DESC LIMIT 1");
	$stmt->execute();
	$stmt->bind_result($height);
	$stmt->fetch();
	$stmt->close();

	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Pragma: no-cache');
	header('Content-type: text/plain');
	echo $height;
}
?>