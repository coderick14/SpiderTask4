<?php
    session_start();
    $user = $_SESSION['user'];
    $level = $_SESSION['level'];
    if(isset($_POST['editUser'])) {
      $_SESSION['editUser'] = $_POST['editUser'];
    }
    if(!$user) {
      echo 'You must be logged in to continue<br><br>';
      echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
    }
    elseif ($level != "Admin") {
      echo "You don't have the required access to this page. Sorry. :(";
    }
    else {
        include('connect.php');
        if(isset($_POST['submit']) && !empty($_POST['access'])) {
          $access = $_POST['access'];
          $name = $_SESSION['editUser'];
          if($access != $level)
          {
            $updateLevel = $dbcon->prepare("UPDATE users SET user_level=? WHERE user_name=?");
            if($updateLevel)  {
              $updateLevel->bind_param("ss",$access,$name);
            }
            else {
              die("Error preparing statement");
            }
            $result = $updateLevel->execute();

            //$result = mysqli_query($dbcon,"UPDATE users SET user_level='$access' WHERE user_name='$name'");
            if($result) {
              echo "Access Level Modified";
            }
            else {
              die("Error updating database");
            }
          }
        }
?>

    <html>
      <head>
        <title>PostIt - Edit Access Rights</title>
        <meta charset="utf-8">
        <style>
          body {
            font-family: calibri,sans-serif;
            color: darkslategray;
            text-align: center;
          }
          h1,h2,h3 {
            font-weight: 400;
          }
        </style>
      </head>
      <body>
        <h1>Postit - Edit Access Rights</h1>
        <a href="panel.php" style="text-decoration:none">Go back to Admin Panel</a>
        <form action="editLevel.php" method="post">
          <h3>User Name</h3>
          <input type="text" name="username" value="<?php echo $_SESSION['editUser']; ?>" size="30" readonly/>
          <h3>Access Level</h3>
          <input type="radio" name="access" value="Visitor" checked/>Visitor<br/>
          <input type="radio" name="access" value="Editor" />Editor<br/>
          <input type="radio" name="access" value="Admin" />Admin<br/><br/>
          <input type="submit" name="submit" value="Change Access Level"/>
        </form>
      </body>
    </html>

<?php
  }
?>
