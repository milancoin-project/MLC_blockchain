<?php
require_once("dbconfig.php");

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

if ($engine == "blockexplorer"){
	if(isset($_GET['dateblocks'])) {
        	$dateblocks = cleandata($_GET['dateblocks']);
	} elseif(isset($_POST['dateblocks'])) {
        	$dateblocks = cleandata($_POST['dateblocks']);
	} else {
        	$dateblocks = date('Y-m-d');
	}

	if(isset($_GET['block'])) {
		$blockhash = cleandata($_GET['block']);
		$engine = "block";
	} elseif(isset($_POST['block'])) {
		$blockhash = cleandata($_POST['block']);
		$engine = "block";
	} else {
		$blockhash = NULL;
	}
}

if ($engine == "search"){
	if(isset($_GET['query'])) {
		$field = cleandata($_GET['query']);
	} elseif(isset($_POST['query'])) {
        $field = cleandata($_POST['query']);
	} else {
        header('Location: /');
	}
	
    redirect_search($field);
}

switch($engine) {
    case 'blockexplorer':
        $page = 'blockexplorer';
        break;
    case 'voteexplorer':
        $page = 'voteexplorer';
        break;
    case 'nodeexplorer':
        $page = 'nodeexplorer';
        break;
    case 'onlinewallet':
        $page = 'onlinewallet';
        break;
    case 'graphs':
        $page = 'graphs';
        break;		
    case 'support':
        $page = 'support';
        break;
    case '404':
        $page = '404';
        break;
    default:
		$page = 'home';
		break;
}

include('modules/header.php');
include('includes/'.$page.'.inc.php');
include('modules/footer.php');

$mysqli->close();

function format_size($size) {
	$mod = 1024;
	$units = explode(' ','Bytes KBytes MBytes GBytes TBytes PBytes');

	for ($i = 0; $size > $mod; $i++) { $size /= $mod; }
    return round($size, 2) . ' ' . $units[$i];
}

function cleandata($field){
	$data = filter_var($field, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_FLAG_STRIP_LOW);
	return $data;
}
 
function menudate($date) {

	$html = '
            <div class="pagination-centered">
                <ul class="pagination" style="height: auto;">
                        <li class="arrow unavailable">&lt;&lt;</li>
                        <li><a href="?engine=blockexplorer&dateblocks=' . date("Y-m-d") . '" class="internal">Today</a></li>
                        <li><a href="?engine=blockexplorer&dateblocks=' . date("Y-m-d", strtotime("-1 day")) . '" class="internal">Yesterday</a></li>
                        <li><a href="?engine=blockexplorer&dateblocks=' . date("Y-m-d", strtotime("-2 day")) . '" class="internal">' . strftime("%A",strtotime("-2 day")) . '</a></li>
                        <li><a href="?engine=blockexplorer&dateblocks=' . date("Y-m-d", strtotime("-3 day")) . '" class="internal">' . strftime("%A",strtotime("-3 day")) . '</a></li>
                        <li><a href="?engine=blockexplorer&dateblocks=' . date("Y-m-d", strtotime("-4 day")) . '" class="internal">' . strftime("%A",strtotime("-4 day")) . '</a></li>
                        <li class="arrow unavailable">&gt;&gt;</a></li>
                </ul>
			</div>';	

	return $html;
}

function remove0x($string)
{
        if(substr($string,0,2)=="0x"||substr($string,0,2)=="0X")
        {
                $string=substr($string,2);
        }
        return $string;
}

function redirect_search($input) {
	global $mysqli;
	
    $input = trim($input);
	
    if(!preg_match("/^[0-9A-HJ-NP-Za-km-z]+$/", $input)) {
      header('Location: ?engine=404'); 
	}
	
    // block number
    if(preg_match("/^[0-9]+$/", $input))
    {
		$result = $mysqli->query("SELECT `hash` FROM `blocks` WHERE `height` = '".$input."' LIMIT 1");
		
		if ($result->num_rows >0) {
			$result->close();
			header('Location: /block-height/'.$input);
			break;
		}
    }
    // size limits
    if(strlen($input) < 1 || strlen($input) > 130) {
		header('Location: ?engine=404');
	}

    // address
    if(strlen($input) < 36 && !preg_match("/0/", $input)) {
		$result = $mysqli->query("SELECT `address` FROM `keys` WHERE `address` = '".$input."'");
		
		if ($result->num_rows >0) {
			$result->close();
			header('Location: /address/'.$input);
			break;
		}
    }

    // hex only from here
    $input = strtolower(remove0x($input));

    // block hash
	$result = $mysqli->query("SELECT `hash` FROM `blocks` WHERE `hash` = '".$input."' LIMIT 1");
		
	if ($result->num_rows >0) {
		$result->close();
		header('Location: /block/'.$input); 
		break;
	}		
	
    // tx hash
	$result = $mysqli->query("SELECT `hash` FROM `transactions` WHERE `hash` = '".$input."' LIMIT 1");
		
	if ($result->num_rows >0) {
		$result->close();
		header('Location: /tx/'.$input);
		break;
	}		
	
    // hash160
	$result = $mysqli->query("SELECT `address` FROM `keys` WHERE `hash160` = '".$input."' LIMIT 1");
		
	if ($result->num_rows >0) {
		$row = mysqli_fetch_assoc($result);
		header('Location: /address/'.$row['address']);
		$result->close();
		break;
	}		
	
    // unseen address/hash160

    if(strlen($input) == 40 && preg_match("/[0-9a-f]{4,130}/", $input)) {
        if($address) { 
			header('Location: /address/'.$input);
			break;
		}
	}
	
	header('Location: ?engine=404');
}
?>
