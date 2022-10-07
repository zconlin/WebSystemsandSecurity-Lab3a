<?php error_reporting(-1);
      session_start();
    if (!isset($_SESSION['logged_in'])) {
      header("Location: views/login.php");
    }  
    echo var_dump($_SESSION["id"], $_SESSION["username"], $_SESSION["logged_in"]);
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Add an appropriate title in this tag -->
  <title>Lab 1</title>

  <!-- Links to stylesheets -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
  <link rel="icon" type="image/x-icon" href="favicon.ico">

</head>

<body>


  <!-- Your visible elements -->
  <!-- Navigation bar -->
  <nav>
    <a href="https://www.byu.edu">Visit byu.edu!</a>
    <form action="actions/logout_action.php">
    <input class="logout-button" type="submit" value="Logout" required><br></form>
  </nav>

  <h1 font-family: Sofia, sans-serif>To Do List</h1>

  <!-- Checkboxes -->
  <input onclick="readTasks()" type="checkbox" class="toggle-switch" id="cb-sort" name="cb-sort"/><label for="cb-sort">Sort by date</label>
  <input onclick="readTasks()" type="checkbox" class="toggle-switch" id="ft-sort" name="ft-sort"/><label for="ft-sort">Filter completed tasks</label>

  <!-- List of tasks -->
  <ul class="tasklist" id="taskList"></ul>

  <!-- Input Form -->
  <form class="form-create-task" onsubmit="createTask(event)" onclick="(readStorage)">
    <input type="text" id="taskName" name="taskName" required oninput="saveText()"><br>
    <input type="date" id="date" name="date" required><br>
    <input class="create-button" type="submit" value="Create Task" required><br>
    <!-- <input class="create-button" type="submit" value="Create Task" onclick="localStorage.removeItem('area');area.value=''" required><br> -->
  </form>

</script>
  <!-- Links to scripts -->
  <script src="js/script.js"></script>
</body>

</html>