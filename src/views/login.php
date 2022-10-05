<?php
   ob_start();
   session_start();
   echo var_dump($_SESSION["id"], $_SESSION["username"], $_SESSION["logged_in"]);
   echo $_SESSION["usernameLoginError"];
   unset($_SESSION["usernameLoginError"]);
   echo $_SESSION["passwordLoginError"];
   unset($_SESSION["passwordLoginError"]);
?>

<html lang = "en">
   
   <head>
      <title>Login</title>  
   </head>
   <body>
      <h2>Enter Username and Password</h2>       
      <div>
      <form action="/actions/login_action.php" method="post">
            <input type = "text" name = "usernameLogin" placeholder = "username" required autofocus></br>
            <input type = "password" name = "password" placeholder = "password" required></br>
            <button name = "login">Login</button>
         </form>
         <!-- Click here to <a href = "../actions/login_action.php" title = "Login"> login. -->
         Click here to <a href = "../actions/logout_action.php" title = "Logout"> logout.
      </div>      
   </body>
</html>