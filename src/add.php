<?php
include 'storedInfo.php';
header("Location: index.php");

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "solomreb-db", $myPassword,"solomreb-db");
if (!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

$stmt = $mysqli->prepare("INSERT INTO videos(name, category, length)
 VALUES (?, ?, ?)");
$stmt->bind_param('ssi', $name, $category, $length);
$name = $_GET["name"];
$category = $_GET["category"];
$length = $_GET["length"];

//execute prepared statement
$stmt->execute();

printf("%d Row inserted.\n", $stmt->affected_rows);

?>