<?php
    session_start();
    $user = $_SESSION['user'];
    $level = $_SESSION['level'];
    if(!$user) {
      echo 'You must be logged in to continue<br><br>';
      echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
    }
    elseif ($level != "Admin") {
      echo "You don't have the required access level. Sorry :(";
    }
    else {
      include('connect.php');
?>

    <html>
      <head>
        <title>PostIt - Admin Panel</title>
        <meta charset="utf-8">
        <style>
          body {
            font-family: calibri,sans-serif;
            color: darkslategray;
            text-align: center;
            background-image: linear-gradient(to right,darkslategray,lightgray,white);
          }
          th,td {
            padding: 0.5em;
            text-align: center;
          }
          th {
            background-color: #4B0082;
            color: azure;
          }
          tr:nth-child(odd) {
            background-color: #FFE4C4;
          }
          tr:nth-child(even)  {
            background-color: #99FFD6;
          }
          h1,h2,h3 {
            font-weight: 400;
          }
        </style>
      </head>
      <body>
        <h1>Welcome to PostIt Admin Panel, <?php echo $_SESSION['user']; ?>!</h1>
        <a href="board.php" style="text-decoration:none;color:red">Go to bulletin board</a><br/>
        <a href="logout.php" style="text-decoration:none;color:red">Logout</a>
        <br/>
        <form id='editForm' method="post" action="editLevel.php">
          <input type="hidden" name="editUser" />
        </form>
        <h2>Registered User Details</h2>
        <center>
        <table>
          <thead>
            <tr>
              <th>User Name</th><th>Access Level</th><th>Change access level</th>
            </tr>
          </thead>
          <tbody>
      <?php
        $sqlusers = "SELECT * FROM users";
        $result = mysqli_query($dbcon,$sqlusers);
        if ($result) {
          while($row = mysqli_fetch_array($result)) {
            if($row['user_level'] == "Admin") {
              echo '<tr><td>'.$row['user_name'].'</td><td>'.$row['user_level'].'</td><td>Access Denied</td></tr>';
            }
            else {
              echo '<tr><td>'.$row['user_name'].'</td><td>'.$row['user_level'].'</td><td><button type="button" onclick="changeLevel(this)">Edit access rights</button></td></tr>';
            }
          }
        }
      ?>
        </tbody>
        </table>
      </center>
      <script type="text/javascript">
        function changeLevel(x) {
          var rIndex = x.parentNode.parentNode.rowIndex;
          var name = document.getElementsByTagName('table')[0].rows[rIndex].cells[0].innerHTML;
          document.getElementById('editForm').elements[0].value = name;
          document.getElementById('editForm').submit();
        }
      </script>
      </body>
    </html>

<?php
    }
?>
