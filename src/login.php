<?php
   include("lib/config.php");
   session_start();
   $errorMsg="";
   $errorMsgsearch="";
    //user login
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form

      $myusername = mysqli_real_escape_string($db,$_POST['username']);
      $mypassword = mysqli_real_escape_string($db,$_POST['password']);

      $sql = "SELECT g.username FROM (
      SELECT password, username FROM Salesperson
      UNION
      SELECT password, username  FROM Owner
      UNION
      SELECT password, username  FROM Inventory_Clerk
      UNION
      SELECT password, username  FROM Manager
      ) g
      WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($db,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $count = mysqli_num_rows($result);

      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count == 1) {
         session_start("myusername");
         $_SESSION['login_user'] = $myusername;
         header("location: search_vehicle.php");
      }else {
         $errorMsg = "Your Username or Password is invalid";
      }
   }
?>


<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Welcome to Burdell's Ramblin' Wrecks</title>
    <link rel="stylesheet" type="text/css" href="css/theme.css">
  </head>
  <body>
    <div class="header">
      <h1>Welcome to Burdell's Ramblin' Wrecks</h1>
      <hr>
  </div>
    <form name="input" action="" method="post">
      <label for="username">Username: </label><input type="username" value="" id="username" name="username" / > <br>
      <label for="password">Password: </label><input type="password" value="" id="password" name="password" />
      <div class="error"><?= $errorMsg ?></div>
      <input type="submit" value="Login" name="sub" />
    </form>
  </body>
</html>
