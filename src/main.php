<?php
   include('lib/session.php');
   include('lib/header.php');
?>

<!DOCTYPE html>
<html>
    <h2>Main Menu</h2>
    <h3><a href='search_vehicle.php'>Vehicle Search</a></h3>
    <?php
    if ($login_usertype == 'Inven_clerk' || $login_usertype == 'Owner') {
      echo"<h3><a href='add_vehicle.php'>Add Vehicle</a></h3>";
    }

    if ($login_usertype != 'PublicUser'){
    echo"<h3><a href='search_cust.php'>Search Customer</a></h3>";
}
    if ($login_usertype == 'Manager' || $login_usertype == 'Owner') {
    echo"<h3><a href='reports_main.php'>Management Reports</a></h3>";
  }
    ?>
  </body>
</html>
