<?php
//Alex Dana
//cse383 final project - fall 2014
//

session_start();
$user="";

// return user to login if user isn't logged in
if (isset($_SESSION['user']))
	$user = $_SESSION['user'];
else {
	header("Location: login.php");
}

$item=array();
$itemurl=array();
$itemcost=array();
$cost = 0;
function getItems() {
	global $item;
	global $itemurl;
	global $cost;
	global $itemcost;
	// variables used to access db
    $servername = "localhost";
    $username = "cse383";
    $password = "cse383isfun";
    $dbname = "cse383";

	// create connection to db
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
	// query items table in db for itemname, itemurl, and cost
	// picks 5 at random
    $sql = "SELECT * from items order by rand() limit 5";
    $result = $conn->query($sql);

	// add data from 5 items selected to global arrays
	$numitemsadded = 0;
    if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc() and $numitemsadded < 5) {
			//echo "itemName: " . $row["itemName"]. " - itemURL: " . $row["itemURL"]. " " . $row["cost"]. "<br>";
			$item[$numitemsadded] = $row["itemName"];
			$itemurl[$numitemsadded] = $row["itemURL"];
			$itemcost[$numitemsadded] = $row["cost"];
			$numitemsadded++;
		}
	} else {
		echo "0 results";
	}
	$conn->close();
	
	// log if less than 5 items retrieved from db
	if ($numitemsadded < 4) {
		error_log("\nuser: " . $user . " time: " . date('Y-m-d') . 
			" error: error retrieving data from db", 
			3, "finalprojecterrors.log");
	}

	// fill itemcost array with costs of the 5 random items selected
	for ($i=0;$i<5;$i++) {
		$_SESSION["itemcost$i"] = $itemcost[$i];
	}

	// pick 3 random items and sum their cost
	$cost = 0;						// used to keep track of random 3 items cost
	$costcounter = 0;			 	// number of items added to total cost so far
	$itemschosensofar = array();	// array that holds which items have been chosen so far so they aren't chosen again
	while ($costcounter < 3) {
		$randomitem = rand(0, 4); 	// pick a random item 0, 1, 2, 3, or 4
		if (!in_array($randomitem, $itemschosensofar)) { 	// if not already chosen
			$itemschosensofar[$costcounter] = $randomitem;			// add item to chosen
			$cost += $itemcost[$randomitem]; 						// increment cost
			$costcounter++;											// increment items chosen so far
		}		
	}
	// set targetcost session var to cost
	$_SESSION['targetcost'] = $cost;
	
	// log if less than 3 items retrieved from db
	if ($numitemsadded < 2) {
		error_log("\nuser: " . $user . " time: " . date('Y-m-d') . 
			" error: error retrieving data from db", 
			3, "finalprojecterrors.log");
	}
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
<a href='http://ceclnx01.cec.miamioh.edu/~campbest/scores.php'>Scores</a>
</div>

<div id="results">
Welcome <?php echo $user;

getItems();
?>

<br>Please select from the following list 3 items that you think add up to <?php print $cost;?>

<form method='post' action="play.php" onsubmit="return checkIfThreeBoxesSelected()">
<table>
<?php
print "<tr>";
for ($i=0;$i<5;$i++) {
	print "<td><img src='" . $itemurl[$i] . "'><br>" . $item[$i] . "<input type='checkbox' name='item$i'></td>";
	if (($i==2)) 
		print "</tr><tr>";
}
?>
</tr>
</table>
<input type="hidden" name="cmd" value="play">
<input type='Submit'>
</form>
</div>

<div id="footer">
Danaaj cse383 final project
</div>

</div>
<script>

// javascript function to count number of boxes selected by user
// code based on http://stackoverflow.com/questions/22938341/count-the-number-of-checked-checkboxes-in-html
function checkboxes() {
  var inputElems = document.getElementsByTagName("input"),
  count = 0;
  for (var i=0; i<inputElems.length; i++) {       
    if (inputElems[i].type == "checkbox" && inputElems[i].checked == true){
      count++;
    }
  }
  return count;
}

// javascript function to warn user they haven't selected 3 boxes
function checkIfThreeBoxesSelected() {
  if(checkboxes() !== 3) {
    alert("Make sure you selected 3 boxes");
    return false;
  } 
}
</script>
</body>
</html>
