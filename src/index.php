<?php
include 'storedInfo.php';


$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "solomreb-db", $myPassword,"solomreb-db");
if (!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

if (isset($_GET["operation"])){
	if ($_GET["operation"] == 'Add'){
		$stmt = $mysqli->prepare("INSERT INTO videos(name, category, length, rented)
		 VALUES (?, ?, ?, ?)");
		$stmt->bind_param('ssii', $name, $category, $length, $rented);
		$name = $_GET["name"];
		$category = $_GET["category"];
		$length = $_GET["length"];
		$rented = 1;

		//execute prepared statement
		$stmt->execute();

		printf("%d Row inserted.\n", $stmt->affected_rows);
	}
}

displayVideos($mysqli);

function displayVideos($mysqli){
	echo "<table><tr><th>ID</th><th>Movie</th><th>Category</th><th>Length</th><th>Rental Status</th></tr>";

	if (!($stmt = $mysqli->prepare("SELECT * FROM videos"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}

	$out_id = NULL;
	$out_name = NULL;
	$out_category = NULL;
	$out_length = NULL;
	$out_rented = NULL;
	if (!$stmt->bind_result($out_id, $out_name, $out_category, $out_length, $out_rented)) {
		echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	while ($stmt->fetch()) {
		printf("<tr><td> %s </td> <td> %s </td> <td> %s </td><td> %s </td> <td> %i </td></tr>\n",
		 $out_id, $out_name, $out_category, $out_length, $out_rented);
	}
	echo "</table>";
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>CS 340 PHP-MySQL Assignment</title>
	<style>
		table, th, td {
	    border: 1px solid black;
		}
	</style>
	<script src="videos.js"></script>
</head>

<body>
<?php

//create connection

?>
<form method="get">
    <h2>Add a video</h2><br>
	<h3>name:</h3><input type="text" name="name">
	<h3>category:</h3>
        <select name=category>
            <option value="all">All Movies</option>
        </select>
    <h3>length:</h3><input type="number" min="0" name="length">
    
    <input type="submit" value="Add" name="operation">
	
</form>

</body>
</html>