<?php
    session_start();
    $user = $_SESSION['user'];
    $level = $_SESSION['level'];
    if(!$user) {
      //echo 'You must be logged in to continue<br><br>';
      //echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
      header("Location: login.php");
    }
    elseif ($level != "Admin") {
      echo "You don't have the required access level. Sorry :(";
    }
    else {
      include('connect.php');

      //for discarding the post
      if(isset($_POST['remId']) && !empty($_POST['remId'])) {
        $remresult = mysqli_query($dbcon,"DELETE FROM mods WHERE Id=".$_POST['remId']);
        if ($remresult) {
          echo "Post discarded<br>";
        }
        else {
          die("Error discarding post");
        }
      }
      elseif (isset($_POST['allowId'])) {   //for allowing the post
        $insertresult = mysqli_query($dbcon,"INSERT INTO posts (post_by,post_time,post_topic,post_content) SELECT post_by,post_time,post_topic,post_content FROM mods WHERE Id=".$_POST['allowId']);
        $allowresult = mysqli_query($dbcon,"DELETE FROM mods WHERE Id=".$_POST['allowId']);
        if ($allowresult && $insertresult) {
          echo "Post added to bulletin board<br>";
        }
        else {
          die("Error allowing post");
        }
      }
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
          .myPost {
            display: inline-block;
            width: 50vw;
            padding: 1.5em;
            margin: 1.5em;
            background-color: rgba(0,0,0,0.7);
            color: white;
            border-radius: 20px 0px 20px 0px;
          }
          .myPost:hover {
            box-shadow: 10px 10px 10px black;
          }
          .postWriter {
            color : #7FFFD4;
          }
          .postTopic  {
            color: #A6FF4D;
          }
          .postTime {
            color: #FFFF4D;
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
          <input type="hidden" name="editUserLevel" />
        </form>
        <form id="changePost" method="post" action="panel.php">
          <input type="hidden" name="remId" />
          <input type="hidden" name="allowId" />
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
        <br/><br/>
        <h2>Moderated Posts Section</h2>
        <br/>
        <?php
          $view_mod_posts = "SELECT * FROM mods";
          $mod_result = mysqli_query($dbcon,$view_mod_posts);
          if($mod_result) {
            if(mysqli_num_rows($mod_result)==0)
              echo "<br>There are no moderated posts yet.<br>";
            else {
              while($row = mysqli_fetch_array($mod_result)) {
                echo "<div class='myPost'><span class='postWriter'>".$row['post_by']."</span> wrote a post about <span class='postTopic'>".$row['post_topic']."</span> on <span class='postTime'>".$row['post_time']."</span><br/><br/>".$row['post_content']."</div><br/>";
                echo "<button style='color:red' onclick='discardPost(".$row['Id'].")'>Discard post &times;</button>";
                echo "<button style='color:green' onclick='allowPost(".$row['Id'].")'>Allow post &check;</button><br>";
              }
            }
          }
          else {
            die("Error extracting from database");
          }
         ?>
      </center>
      <script type="text/javascript">
        //function to pass username to editLevel.php
        function changeLevel(x) {
          var rIndex = x.parentNode.parentNode.rowIndex;
          var name = document.getElementsByTagName('table')[0].rows[rIndex].cells[0].innerHTML;
          var level = document.getElementsByTagName('table')[0].rows[rIndex].cells[1].innerHTML;
          document.getElementById('editForm').elements[0].value = name;
          document.getElementById('editForm').elements[1].value = level;
          document.getElementById('editForm').submit();
        }

        //function to pass the id of the post to be discarded
        function discardPost(id)  {
          var cnf = window.confirm("Are you sure you want to discard this post?");
          if(cnf) {
            document.getElementById('changePost').elements[0].value = id;
            document.getElementById('changePost').submit();
          }
        }
        //function to pass the id of the post to be allowed
        function allowPost(id)  {
          var cnf = window.confirm("Are you sure you want to allow this post?");
          if(cnf) {
            document.getElementById('changePost').elements[1].value = id;
            document.getElementById('changePost').submit();
          }
        }
      </script>
      </body>
    </html>

<?php
    }
?>
