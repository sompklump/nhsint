<?php
//Declare credentials
$servername = "localhost";
$username = "root";
$password = "password";
$db = "NHS_PNC";

//Create connection
$conn = mysqli_connect($servername, $username, $password, $db);
$error = null;
//Check connection
if(!$conn){
	die("Connection database failed: " . $conn->connect_error);
}
//echo "Connection established!";
?>
