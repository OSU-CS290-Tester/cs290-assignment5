<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>CS 340 PHP-MySQL Assignment</title>
	<style>
		table, th, td {
	    	border: 1px solid black;
		}
		.error {color: red;}
	</style>
	<script>
function validateForm() {
    var name = document.forms["addForm"]["name"].value;
    if (name == null || name == "") {
        alert("Name must be filled out");
        return false;
    }
}
</script>
<h2> Video Store Inventory </h2>
</head>

<body>

<?php
include 'storedInfo.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "solomreb-db", $myPassword,"solomreb-db");
if (!$mysqli || $mysqli->connect_errno){
    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }


function displayVideos($mysqli, $query){
	echo "<br><table>
	    <col width='40%'> <col width='20%'> <col width='10%'> <col width='10%'> <col width='10%'><col width='10%'>
  		<tr><th>Movie</th>
  		<th>Category</th>
  		<th>Length</th>
  		<th>Rental Status</th>
  		<th></th><th></th></tr>";

	if (!($stmt = mysqli_query($mysqli, $query))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}


	while ($row = mysqli_fetch_array($stmt)) {
		echo "<tr>" ;
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['category'] . "</td>";
		echo "<td>" . $row['length'] . "</td>";
		echo "<td>" . $row['rented'] . "</td>";
		
		echo "<td><form method=\"GET\" action=\"checkin.php\">";
		echo "<input type=\"hidden\" name=\"nameid\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"Check In/Out\">";
		echo "</form> </td>";
		
		echo "<td><form method=\"GET\" action=\"delete.php\">";
		echo "<input type=\"hidden\" name=\"nameid\" value=\"".$row['id']."\">";
		echo "<input type=\"submit\" value=\"delete\">";
		echo "</form> </td>";
		echo "</tr>";
	}
	echo "</table><br>";
}

?>

<form  name="addForm" method="get" action="add.php" onsubmit="return validateForm()">
 <fieldset>
  <legend>Add Video:</legend>
	Name: <input type="text" name="name" id="nameInput">
	Category: <input type="text" name="category" id=categoryInput>
	Length: <input type="number" min="0" name="length" id=lengthInput>
    <input type="submit" value="Add">
 </fieldset>
<br>	
</form>

<form method='get' >
 <fieldset>
  <legend>Filter by Category:</legend>
	<?php 
	$categories = mysqli_query($mysqli,'SELECT DISTINCT category FROM videos');
	
	echo "<select name='category'>";
		while ($item = mysqli_fetch_array($categories)) {
		if ($item['category'] == '')
			break;
		echo "<option value='".$item['category']."'>".$item['category']."</option>";
	}
	echo "<option value='all'>All Movies</option>";
	echo "</select>";
	?>
	
    <input type="submit" value="Filter">
 </fieldset>
</form>
<?php 
if (isset($_GET['category'])){
	$filter = $_GET['category'];
	if ($filter == 'all'){
		displayVideos($mysqli, "SELECT * FROM videos"); 
	}
	else {
		displayVideos($mysqli, "SELECT * FROM videos WHERE category = '$filter'");
	}
}
else{
	displayVideos($mysqli, "SELECT * FROM videos"); 
}?>
<form method="get" action="deleteAll.php">
 <fieldset>
  
    <input type="submit" value="Delete All Videos" style="color:red">
 </fieldset>
	
</form>

</body>
</html>