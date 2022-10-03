<html>
<head>
	<title>Registration Form</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>
<body>

    <?php if (isset($_POST['form_submitted'])): ?> 

        <h2>Thank You <?php echo $_POST['firstname']; ?> </h2>

        <p>You have been registered as
            <?php echo $_POST['username']; ?>
        </p>

        <p>Go <a href="/registration_form.php">back</a> to the form</p>

        <?php else: ?>

            <h2>Registration Form</h2>

            <form action="registration_form.php" method="POST">

                Username:
                <input type="text" name="username"> <br>
                
                Password:
                <input type="password" name="password"> <br>

                Confirm Password:
                <input type="password" name="confirmPassword"> <br>        

			<input type="hidden" name="form_submitted" value="1" />

                <input type="submit" value="Submit">

            </form>

      <?php endif; ?> 
</body> 
</html>