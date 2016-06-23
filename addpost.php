<?php
    session_start();
    $user = $_SESSION['user'];
    $level = $_SESSION['level'];
    if(!$user) {
      echo 'You must be logged in to continue<br><br>';
      echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
    }
    elseif ($level == "Visitor") {
      echo "You don't have the required access level. Sorry :(";
    }
    else {
      include('connect.php');
      if(isset($_POST['add']))  {
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        $addpost = $dbcon->prepare("INSERT INTO posts (post_by,post_time,post_topic,post_content) VALUES (?,now(),?,?)");
        if($addpost)  {
          $addpost->bind_param("sss",$user,$topic,$content);
        }
        else {
          die("Error preparing statement");
        }
        $result = $addpost->execute();
        if($result) {
          echo "Post added<br>";
        }
        else {
          die("Error inserting into database");
        }
      }
?>
    <html>
      <head>
        <title>PostIt - Add a post</title>
        <meta charset="utf-8">
        <style>
          body {
            font-family: calibri,sans-serif;
            color: darkslategray;
            text-align: center;
          }
          h1,h2,h3  {
            font-weight: 400;
          }
        </style>
      </head>
      <body>

      </body>
        <h1>PostIt - Add your post</h1>
        <a href="board.php" style="text-decoration:none">Go to bulletin board</a><br/>
        <a href="logout.php" style="text-decoration:none">Logout</a>
        <br/>
        <form action="addpost.php" method="post">
          <h3>Topic</h3>
          <input type="text" name="topic" size="50" maxlength="30" placeholder="Enter the topic for your post" required/>
          <h3>Content</h3>
          <textarea cols="50" rows="15" name="content" placeholder="Write your post here..." required></textarea><br/><br/>
          <input type="submit" name="add" value="Add Post"/>
        </form>
      </body>
    </html>

<?php
    }
?>
