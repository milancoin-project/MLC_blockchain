<?php

if(isset($_GET['dateblocks'])) {
	$dateblocks = $_GET['dateblocks'];
	$page = "blockindex";
} elseif(isset($_POST['dateblocks'])) {
	$dateblocks = $_POST['dateblocks'];
	$page = "blockindex";
} else {
	$dateblocks = date('Y-m-d');
	$page = "blockindex";
}

if(isset($_GET['hash'])) {
	$blockhash = $_GET['hash'];
	$page = "blockhash";
} elseif(isset($_POST['hash'])) {
	$blockhash = $_POST['hash'];
	$page = "blockhash";
} else {
	$blockhash = NULL;
}

if(isset($_GET['blockid'])) {
	$blockid = $_GET['blockid'];
	$page = "blockhash";
} elseif(isset($_POST['blockid'])) {
	$blockid = $_POST['blockid'];
	$page = "blockhash";
} else {
	$blockid = NULL;
}

if(isset($_GET['tx'])) {
	$tx2hash = $_GET['tx'];
	$page = "blocktx";
} elseif(isset($_POST['tx'])) {
	$tx2hash = $_POST['tx'];
	$page = "blocktx";
} else {
	$tx2hash = NULL;
}

if(isset($_GET['address'])) {
	$address = $_GET['address'];
	$page = "blockaddress";
} elseif(isset($_POST['address'])) {
	$address = $_POST['address'];
	$page = "blockaddress";
} else {
	$address = NULL;
}

include('includes/'.$page.'.inc.php');
?>
