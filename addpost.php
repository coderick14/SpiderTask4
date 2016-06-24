<?php
    session_start();
    $user = $_SESSION['user'];
    $level = $_SESSION['level'];
    $mod = $_SESSION['mod'];
    if(!$user) {
      //echo 'You must be logged in to continue<br><br>';
      //echo '<button type="button" onclick="location.href=\'login.php\'">Click here to login</button>';
      header("Location: login.php");
    }
    elseif ($level == "Visitor") {
      echo "You don't have the required access level. Sorry :(";
    }
    else {
      include('connect.php');
      if(isset($_POST['add']))  {
        $topic = $_POST['topic'];
        $content = $_POST['content'];
        if ($level == "Editor" && $mod == "Yes") {
          $addpost = $dbcon->prepare("INSERT INTO mods (post_by,post_time,post_topic,post_content) VALUES (?,now(),?,?)");
        }
        else {
          $addpost = $dbcon->prepare("INSERT INTO posts (post_by,post_time,post_topic,post_content) VALUES (?,now(),?,?)");
        }
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
            background-image: url('key.jpg');
            background-size: cover;
            background-repeat: no-repeat;
          }
          h1,h3  {
            font-weight: 400;
            color: black;
            display: inline-block;
            background-color: rgba(255,255,255,0.5);
            padding: 0.3em;
          }
          a {
            text-decoration: none;
            color: red;
          }
          input[type=text],textarea  {
            text-align: center;
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 0.5em;
            border: 1px solid rgba(0,0,0,0.7);
            border-radius: 10px;
          }
        </style>
      </head>
      <body>
        <h1>PostIt - Add your post, <?php echo $_SESSION['user']; ?>!</h1><br/>
        <a href="board.php">Go to bulletin board</a><br/>
        <a href="logout.php">Logout</a>
        <br/>
        <form action="addpost.php" method="post">
          <h3>Topic</h3><br/>
          <input type="text" name="topic" size="50" maxlength="30" placeholder="Enter the topic for your post" required/><br/>
          <h3>Content</h3><br/>
          <textarea cols="50" rows="15" name="content" placeholder="Write your post here..." required></textarea><br/><br/>
          <input type="submit" name="add" value="Add Post"/>
        </form>
      </body>
    </html>

<?php
    }
?>
