<?php
//Alex Dana
//cse383 final project - fall 2014
//
session_start();
$user="";
$score=0;

// return user to login if user is not logged in
if (isset($_SESSION['user']))
	$user = $_SESSION['user'];
else {
	header("Location: login.php");
}

$targetcost = $_SESSION['targetcost'];
$numItems = 0;
$cost = 0;

// goes through the 5 items displayed and tallies up cost of
// the 3 items the user selected
for ($i=0;$i<5;$i++) {
	if (isset($_REQUEST["item$i"])) {
		$cost = $cost + $_SESSION["itemcost$i"];
		$numItems++;
	}
}

// log if number of items selected wasn't 3
if ($numItems < 2) {
	error_log("\nuser: " . $user . " time: " . date('Y-m-d') . 
		" error: error retrieving data from db", 
		3, "finalprojecterrors.log");
}

// set score
$score = abs($targetcost - $cost);

// set up socket connection to campbell server
// code based on http://php.net/manual/en/function.socket-connect.php
$host = "ceclnx01.cec.miamioh.edu";
$port = "4000";
$timeout = 15;  //timeout in seconds
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("Unable to create socket\n"); // create socket
socket_set_nonblock($socket) or die("Unable to set nonblock on socket\n");
$time = time();
while (!@socket_connect($socket, $host, $port)) {
	$err = socket_last_error($socket);
    if ($err == 115 || $err == 114) {
	    if ((time() - $time) >= $timeout) {
	        socket_close($socket);
    	    die("Connection timed out.\n");
			error_log("\nuser: " . $user . " time: " . date('Y-m-d') . " error: socket timed out", 3, "finalprojecterrors.log");
        }
        sleep(1);
    	continue;
    }
	die(socket_strerror($err) . "\n");
}
// send score to server
$msg = "danaaj" . " " . $score . " " . $user . "\r\n";
socket_write($socket, $msg, strlen($msg));

?>
<!doctype html
<html>
<head>
<title>Danaaj Final Project</title>
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
</head>
<body>
<div id="page" class="container">
<div id="header">
<h2>Danaaj Final Project</h2>
</div>

<div id="links">
<a href='login.php'>Login</a><br>
<a href='http://ceclnx01.cec.miamioh.edu/~campbest/scores.php'>Scores</a>
<a href='index.php'>Play again</a><br>
</div>

<div id="results">
<h1>Results!</h1>
<p>
Welcome <?php echo $user;?>
<br>
<?php if ($numItems != 3 ):	?>
	Sorry - you did not select 3 items
<?else:?>
	Your total of the selected items is <?print $cost;?>
<br>
You scored a <?print $score;?>!
<?php endif;?>
</p>
</div>

<div id="footer">
Danaaj cse383 final project
</div>
</div>
</body>
</html>
