<?php
  session_start();
  $user = $_SESSION['user'];
  $level = $_SESSION['level'];
  if($user) {
    echo "Hi ".$user."<br>";
    echo "<a href='logout.php'>Logout</a>";
    include('connect.php');
?>
  <html>
    <head>
      <title>PostIt Board</title>
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
        .myPost {
          display: inline-block;
          width: 50vw;
          padding: 1.5em;
          margin: 1.5em;
          background-color: #B3FFE6;
          color: darkslategray;
          border-radius: 10px 0px 10px 0px;
        }
        .postWriter {
          color : #00008B;
        }
        .postTopic  {
          color: #FF4500;
        }
        .postTime {
          color: #800080;
        }
      </style>
    </head>
    <body>
      <h1>Welcome to PostIt</h1>
      <form id="myForm" method="post">
        <input type="hidden" name="postId" />
      </form>
<?php
      //check about Admin rights later
      if($level == 'Editor' || $level == "Admin")  {
        echo '<button type="button" onclick="location.href=\'addpost.php\'">Add a post</button>';
      }
      if($level == "Admin") {
        echo '<button type="button" onclick="location.href=\'panel.php\'">Admin Panel</button>';
      }
      if($level == "Admin" && isset($_POST['postId'])) {
        $delpost = $dbcon->prepare("DELETE FROM posts WHERE Id=?");
        if($delpost)  {
          $delpost->bind_param("i",$_POST['postId']);
        }
        else {
          die("Error preparing statement");
        }
        $delresult = $delpost->execute();
        if(!$delresult)  {
          die("Error deleting post");
        }
      }
      echo '<br>';
      $viewposts = "SELECT * FROM posts";
      $result = mysqli_query($dbcon,$viewposts);
      if($result) {
        if(mysqli_num_rows($result)==0) {
          echo "There are no posts on this page yet";
        }
        else {
          if($level == "Admin") {
            while($row = mysqli_fetch_array($result)) {
              echo "<div class='myPost'><span class='postWriter'>".$row['post_by']."</span> wrote a post about <span class='postTopic'>".$row['post_topic']."</span> on <span class='postTime'>".$row['post_time']."</span><br/><br/>".$row['post_content']."</div>";
              echo "<button style='color:red' onclick='delPost(".$row['Id'].")'>Delete post &times;</button><br>";
            }
          }
          else {
            while($row = mysqli_fetch_array($result)) {
              echo "<div class='myPost'><span class='postWriter'>".$row['post_by']."</span> wrote a post about <span class='postTopic'>".$row['post_topic']."</span> on <span class='postTime'>".$row['post_time']."</span><br/><br/>".$row['post_content']."</div>";
              echo "<br>";
            }
          }
        }
      }
      else {
        die("Error extracting from database");
      }
  }
  else {
    echo 'You must be logged in to continue<br><br>';
    echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
  }
?>
  <script type="text/javascript">
    function delPost(postId)  {
      var cnf = window.confirm("Are you sure you want to delete this post??");
      if(cnf) {
        document.getElementById('myForm').elements[0].value = postId;
        document.getElementById('myForm').submit();
      }
    }
  </script>
  </body>
</html>
