<?php
  session_start();
  include('connect.php');
  $nameErr=$passErr=$captchaErr="";
  if(isset($_POST['signin'])) {
    $flag = 1;
    if(empty($_POST['uname_ex']))  {
      $nameErr = "Username cannot be blank";
      $flag = 0;
    }
    if(empty($_POST['upass_ex']))  {
      $passErr = "Password cannot be blank";
      $flag = 0;
    }
    if($flag)  {
        $uName = $_POST['uname_ex'];
        $uPass = $_POST['upass_ex'];
        //echo $uName,$uPass;
        $sqlquery = "SELECT * FROM users WHERE user_name='$uName'";
        //echo $sqlquery;
        $result = mysqli_query($dbcon,$sqlquery);
        //$result = $dbcon->query($sqlquery);
        if($result) {
          if(mysqli_num_rows($result)==0)
            $nameErr = "Username does not exist";
          else {
            $row = mysqli_fetch_array($result);
            if($row['user_pass'] == $uPass) {
              //echo 'Access Granted';//store in session variables and redirect
              $_SESSION['user'] = $uName;
              $_SESSION['level'] = $row['user_level'];
              $_SESSION['mod'] = $row['mod_status'];
              //$_SESSION['loggedin'] = true;
              echo "<script type='text/javascript'>location.href='board.php'</script>";
            }
            else
              $passErr = "Password does not match";
          }
        }
        else {
          //echo mysqli_error($dbcon);
          die("Error extracting from database");
        }
    }
    mysqli_close($dbcon);
  }
?>


<html>
  <head>
    <title>PostIt - Sign in</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="startpage.css"/>
  </head>
  <body>
    <header>
      <h1>PostIt</h1>
      <h3>A place to put up your thoughts</h3>
    </header>
    <br/><br/>
      <center>
        <div id="signIn" style="height:19em">
          <form action="login.php" method="post">
            <h2>Username</h2>
            <input type="text" name="uname_ex" maxlength="20" placeholder="Enter your username"/><br/>
            <span class="error"><?php echo $nameErr; ?></span>
            <h2>Password</h2>
            <input type="password" name="upass_ex" maxlength="20" placeholder="Enter your password"/><br/>
            <span class="error"><?php echo $passErr; ?></span>
            <br/><br/>
           <input type="submit" name="signin" value="Sign In"/>
          </form>
        </div>
        <br/>
        <h3 id="signchange">Not a registered user? Click here to sign up.</h3>
      </center>
      <script type="text/javascript">
        document.getElementById('signchange').addEventListener("click",showSignUp);
        function showSignUp() {
          location.href = "signup.php";
        }

      </script>
  </body>
</html>
