<?php
//Alex Dana
//cse383 final project - fall 2014
//

session_start();
$user="";

// set user name
if (isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == "user") {
		$cmd= $_REQUEST['cmd'];
		$user = htmlspecialchars($_REQUEST['user']);
		$_SESSION['user'] = $user;
		header('location:index.php');
	}
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
</div>

<div id=results">
Please enter your username:

<div id="error1"></div>

<form method="post" onsubmit="return checkUserName()">
<input type="text" name="user" id="user">
<input type="submit" onclick="$user=escape()">
<input type="hidden" name="cmd" value="user">
</form>
</div>

<div id="footer">
Danaaj cse383 final project
</div>

</body>
<script>
// javascript to make sure user enter user name
function checkUserName() {
	if (document.getElementById('user').value === "") {
		document.getElementById('error1').innerHTML = "No user name entered";
	}
	return document.getElementById('user').value !== "";
}
</script>
</html>
