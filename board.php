<?php
  session_start();
  $user = $_SESSION['user'];
  $level = $_SESSION['level'];
  if($user) {
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
          background-image: url('ink.jpg');
          background-size: cover;
          background-repeat: no-repeat;
        }
        h1{
          font-weight: 400;
          color: white;
          background-color: rgba(0,0,0,0.2);
          padding: 0.3em;
          display: inline-block;
        }
        a {
          color: white;
          background-color: rgba(0,0,0,0.2);
          padding: 0.3em;
          text-decoration: none;
        }
        a:hover {
          color:red;
        }
        .btn  {
          padding: 0.4em;
          border-radius: 5px;
          font-size: 1.1em;
          color:rgb(0,0,153);
          background-color: rgba(255,255,255,0.7);
          border: 1px solid rgb(0,0,153);
          margin: 1em;
        }
        .btn:hover  {
          box-shadow: 3px 3px 2px black;
          cursor: pointer;
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
      <h1>Welcome to PostIt, <?php echo $_SESSION['user']; ?>!</h1>
      <a href='logout.php'>Logout</a>
      <form id="myForm" method="post">
        <input type="hidden" name="postId" />
      </form>
<?php
      //check about Admin rights later
      if($level == 'Editor' || $level == "Admin")  {
        echo '<button type="button" class="btn" onclick="location.href=\'addpost.php\'">Add a post</button>';
      }
      if($level == "Admin") {
        echo '<button type="button" class="btn" onclick="location.href=\'panel.php\'">Admin Panel</button>';
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
