<?php
  session_start();
  include('connect.php');
  $nameErr1=$passErr1=$passErr2=$captchaErr="";

  if (isset($_POST['signup'])) {
    $flag = 1;
    if(empty($_POST['uname_new']))  {
      $nameErr1 = "Username cannot be blank";
      $flag = 0;
    }
    if(empty($_POST['upass_new']))  {
      $passErr1 = "Password cannot be blank";
      $flag = 0;
    }
    if(empty($_POST['upass_new1']))  {
      $passErr2 = "This field cannot be left blank";
      $flag = 0;
    }
    elseif ($_POST['upass_new1']!=$_POST['upass_new']) {
      $passErr2 = "Passwords do not match!";
      $flag = 0;
    }
    $url = "https://www.google.com/recaptcha/api/siteverify";
    $privatekey = "Your-private-key";
    $response = file_get_contents($url."?secret=".$privatekey."&response=".$_POST['g-recaptcha-response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
    $data = json_decode($response);
    if (!(isset($data->success) && $data->success==true)) {
      $captchaErr = "Captcha Error!!Try again.";
      $flag = 0;
    }
    if ($flag) {
      $uNameNew = $_POST['uname_new'];
      $uPassNew = $_POST['upass_new'];
      $sqlinsert = $dbcon->prepare("INSERT INTO users (user_name,user_pass,user_time) VALUES (?,?,now())");
      if($sqlinsert)  {
        $sqlinsert->bind_param("ss",$uNameNew,$uPassNew);
      }
      else {
        die("Error preparing statement");
      }
      $result = $sqlinsert->execute();
      if($result) {
        //echo 'Account created';
        $_SESSION['user'] = $uNameNew;
        $_SESSION['level'] = "Visitor";
        //$_SESSION['loggedin'] = true;
        echo "<script type='text/javascript'>location.href='board.php'</script>";
      }
      else {
        die("Error inserting into database");
      }
    }
    mysqli_close($dbcon);
  }
?>

<html>
  <head>
    <title>PostIt - Sign up</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="startpage.css"/>
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>
  <body>
    <header>
      <h1>PostIt</h1>
      <h3>A place to put up your thoughts</h3>
    </header>
    <br/><br/>
      <center>
        <div id="signUp" style="height:29em">
          <form action="signup.php" method="post">
            <h2>Username</h2>
            <input type="text" name="uname_new" maxlength="20" placeholder="Enter your username"/><br/>
            <span class="error"><?php echo $nameErr1; ?></span>
            <h2>Password</h2>
            <input type="password" name="upass_new" maxlength="20" placeholder="Enter your password"/><br/>
            <span class="error"><?php echo $passErr1; ?></span>
            <h2>Re-enter password</h2>
            <input type="password" name="upass_new1" maxlength="20" placeholder="Enter your password again"/><br/>
            <span class="error"><?php echo $passErr2; ?></span>
            <br/>
            <div class="g-recaptcha" data-sitekey="Your-public-key"></div>
            <span class="error"><?php echo $captchaErr; ?></span>
            <br/>
            <input type="submit" name="signup" value="Sign Up"/>
          </form>
        </div>
        <br/>
        <h3 id="signchange">Already a registered user? Click here to sign in.</h3>
      </center>
      <script type="text/javascript">
        document.getElementById('signchange').addEventListener("click",showSignIn);
        function showSignIn() {
          location.href = "login.php";
        }

      </script>
  </body>
</html>
