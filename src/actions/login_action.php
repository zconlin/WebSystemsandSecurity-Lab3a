<?php
session_start();
// ./actions/login_action.php

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

$stmt = $conn->prepare("SELECT username FROM user");
$stmt->execute();
$stmt->bind_result($nameValue);
$nameIsTrue = false;
while($row = $stmt->fetch()){
	if($nameValue == $_POST["usernameLogin"]){
		$nameIsTrue = true;
	}
}
$stmt->close();

if(!$nameIsTrue){
	$_SESSION["usernameLoginError"] = "No account found";
	header("Location: /views/login.php");
	die();
}

$username = $_POST["usernameLogin"];
$stmt = $conn->prepare("SELECT `password` FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($checkPassword);
$stmt->fetch();
if(!(password_verify($_POST["password"], $checkPassword))){
	$_SESSION["passwordLoginError"] = "Incorrect Password";
	header("Location: ../index.php");
	die();
}
$stmt->close();

$stmt = $conn->prepare("UPDATE `user` SET `logged_in` = 1 WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->close();

$_SESSION["logged_in"] = 'yes';
$_SESSION["username"] = $username;
$stmt = $conn->prepare("SELECT `id` FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id);
$stmt->fetch();
$_SESSION["id"] = $id;
$stmt->close();
header("Location: ../index.php");
die();
?>
