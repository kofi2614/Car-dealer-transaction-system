<?php
   include('config.php');
   session_start();

   if(isset($_SESSION['login_user'])){
        $user_check = $_SESSION['login_user'];

        $ses_sql = mysqli_query($db,
         "SELECT g.username, g.user_type
         FROM (
           SELECT  username, 'Sales' as user_type FROM Salesperson
           UNION
           SELECT  username, 'Owner' as user_type FROM Owner
           UNION
           SELECT  username, 'Inven_clerk' as user_type FROM Inventory_Clerk
           UNION
           SELECT  username, 'Manager' as user_type FROM Manager
         ) g
         WHERE g.username = '$user_check' "
        );

        $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
        $login_session = $row['username'];
        $login_usertype = $row['user_type'];

   } else {
        $user_check = 'PublicUser';

        $login_session = 'User Not Login';
        $login_usertype = 'PublicUser';
   }

/*
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
      die();
   }
*/
?>
