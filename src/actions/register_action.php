<?php
// ./actions/register_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

// TODO: Register a new user
if ($conn != true) {/* database NOT connected */
	console.log("connected")
	die()
}
// if (/* passwords DONT match */) {
// 	die()
// }
if ($mysql_user == "SELECT username FROM users") { /* username IS taken */
	/* Do (opposite of) register */
	console.log('nice')

	die()
}
?>
