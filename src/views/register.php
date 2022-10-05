<?php
session_start();

echo $_SESSION["passwordError"];
unset($_SESSION["passwordError"]);
echo $_SESSION["usernameError"];
unset($_SESSION["usernameError"]);
?>

<h2>Register New User</h2>
<form method="post" action="/actions/register_action.php">
    Username:
    <input type="text" name = "usernameRegister" required><br>
    Password:
    <input type="password" name = "passwordRegister" required><br>
    Confirm Password:
    <input type="password" name = "passwordConfirm" required><br>
    <input type="hidden" name="form_submitted" value="1" />
    <input type="submit" value="Submit">
</form>
