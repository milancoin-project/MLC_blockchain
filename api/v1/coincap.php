<?php
require_once("dbconfig.php");

$result = array();

$mysqli = new mysqli($db_server, $db_user, $db_passwd, $db_name);

if (mysqli_connect_errno()) {
        echo "DBMS Connection Error: " . mysqli_connect_error();
        exit();
}


$stmt = $mysqli->prepare("SELECT `height`, `hash`, `difficulty` FROM `blocks` ORDER BY `height` DESC LIMIT 1");
$stmt->execute();
$stmt->bind_result($height, $hash, $difficulty);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("SELECT `value` AS value FROM `inputs` WHERE `type` = 'Generation' AND `block` = '$hash' LIMIT 1");
$stmt->execute();
$stmt->bind_result($reward);
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare("SELECT SUM(`value`) AS value FROM `inputs` WHERE `type` = 'Generation'");
$stmt->execute();
$stmt->bind_result($totalcoin);
$stmt->fetch();
$stmt->close();

$totalcoin = $totalcoin + 733662.98511719; // Genesys Block
$totalcoin = $totalcoin + ($totalcoin / 100 * 1.2); // Salary Staff

$result = array("memorycoin" => array( "block_height" => $height, "block_reward" => $reward, "difficulty" => $difficulty, "total_minted" => $totalcoin ));

//echo json_encode($result);
$f = fopen("/var/www/blockchain/api/v1/coincap.json", "w");
fwrite($f, json_encode($result));
fclose($f); 

$mysqli->close();
?>
