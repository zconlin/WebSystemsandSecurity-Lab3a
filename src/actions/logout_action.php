<?php
session_start();
//./actions/logout_action.php

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

$stmt = $conn->prepare("UPDATE `user` SET `logged_in` = 0 WHERE id = ?");
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();
$stmt->close();

// TODO: Log the user out

unset($_SESSION["username"]);
unset($_SESSION["logged_in"]);
unset($_SESSION["id"]);

header("Location: ../views/login.php");
die();
   
?>
