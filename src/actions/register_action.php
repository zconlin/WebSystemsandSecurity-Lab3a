<?php
session_start();
// ./actions/register_action.phpx

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
// prepare and bind

if($_POST['passwordRegister'] != $_POST['passwordConfirm']) {
	$_SESSION["passwordError"] = "Passwords don't match";
	header("Location: ../views/register.php");
	die("Passwords do not match");
}

$stmt = $conn->prepare("SELECT username FROM user");
$stmt->execute();
$stmt->bind_result($nameValue);
$nameIsTrue = false;
while($row = $stmt->fetch()){
	if($nameValue == $_POST["usernameRegister"]){
		$nameIsTrue = true;
	}
}
$stmt->close();

if($nameIsTrue){
	$_SESSION["usernameError"] = "Username already exists";
	header("Location: /views/register.php");
	die();
}

$username = $_POST["usernameRegister"];
$hashed = password_hash($_POST["passwordRegister"], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO `user`(`username`, `password`, `logged_in`) VALUES (?,?,'1')");
$stmt->bind_param("ss", $username, $hashed);
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
