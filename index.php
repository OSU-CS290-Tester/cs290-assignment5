<?php
include 'storedInfo.php';
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
</head>

<body>
<?php

//create connection
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "solomreb-db", $myPassword,"solomreb-db");
if (!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

if (!$mysqli->query("DROP TABLE IF EXISTS videos") ||
    !$mysqli->query("CREATE TABLE videos
(id int PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(255) NOT NULL UNIQUE,
category VARCHAR(255),
length INT UNSIGNED,
rented BOOLEAN NOT NULL default 0)") ||
    !$mysqli->query("INSERT INTO videos(id, name, category, length, rented)
     VALUES (1, 'frozen', 'family', 90, 0)")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!($stmt = $mysqli->prepare("SELECT * FROM videos"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

$out_id    = NULL;
$out_name = NULL;
$out_category = NULL;
$out_length = NULL;
$out_rented = NULL;
if (!$stmt->bind_result($out_id, $out_name, $out_category, $out_length, $out_rented)) {
    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

while ($stmt->fetch()) {
    printf("id = %s, name = %s , category = %s, length = %s, rented = %s\n",
     $out_id, $out_name, $out_category, $out_length, $out_rented);
}

?>
<form method="get">
    <h2>Add a video</h2><br>
	<h3>name:</h3><input type="text" name="name">
	<h3>category:</h3>
        <select name=catorgoy>
            <option value="action">Action</option>
            <option value="comedy">Comedy</option>
            <option value="drama">Drama</option>
            <option value="romance">Romance</option>
        </select>
    <h3>length:</h3><input type="number" min="0" name="name">
    <input type="button" value="Add" onclick=addVideo()>
	
</form>

</body>
</html?>