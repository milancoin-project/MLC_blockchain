<?php
require_once("dbconfig.php");
require_once("functions.php");

$mysqli = new mysqli("p:" . $db_server, $db_user, $db_passwd, $db_name);

if (mysqli_connect_errno()) {
        echo "DBMS Connection Error: " . mysqli_connect_error();
        exit();
}

if(isset($_GET['engine'])) {
	$engine = cleandata($_GET['engine']);
} elseif(isset($_POST['engine'])) {
	$engine = cleandata($_POST['engine']);
} else {
	$engine = NULL;
}

if(isset($_GET['query'])) {
	$field = cleandata($_GET['query']);
} elseif(isset($_POST['query'])) {
    $field = cleandata($_POST['query']);
} else {
    $field = NULL;
}

require_once($engine.".php");

apiquery($field);

$mysqli->close();
?>