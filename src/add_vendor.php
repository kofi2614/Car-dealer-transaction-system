<?php
include('lib/session.php');
include('lib/header.php');
?>
<!DOCTYPE html>
<html>
  <body>
    <div class="header">
      <h2>Add Vendor</h2>
      <?php

          $vin = $_GET["vin"];
          $vin_input = $vin;

          echo "<form method=\"POST\">";
          echo "<label for='vendor_name'>Vendor Name:&nbsp </label><input type='text' value='' id='keyword' name='vendor_name' / >";
          echo "<br><label for='phone_number'>Phone Number:&nbsp </label><input type='text' value='' id='keyword' name='phone_number' / >";
          echo "<br><label for='street'>Street:&nbsp </label><input type='text' value='' id='keyword' name='street' / >";
          echo "<br><label for='city'>City:&nbsp </label><input type='text' value='' id='keyword' name='city' / >";
          echo "<br><label for='state'>State:&nbsp </label><input type='text' value='' id='keyword' name='state' / >";
          echo "<br><label for='postal_code'>Postal Code:&nbsp </label><input type='text' value='' id='keyword' name='postal_code' / >";
          echo "<br><input type='submit' value='Save' name='save' >";
          echo "</form>";

          if(isset($_POST['save']))
          {
            $vendor_select_input = $_POST['vendor_name'];
            $phone_number_input = $_POST['phone_number'];
            $street_input = $_POST['street'];
            $city_input = $_POST['city'];
            $state_input = $_POST['state'];
            $postal_code_input = $_POST['postal_code'];
            $sql_vd_1 = "INSERT INTO Vendor (vendor_name, phone_number, street, city, state, postal_code)
                         VALUES ('$vendor_select_input', '$phone_number_input','$street_input', '$city_input', '$state_input', '$postal_code_input')";
            if ($db->query($sql_vd_1) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: vendor already exists.";
            };
                unset($_POST['save']);
          }

          echo "<h3><a href='search_vendor.php?vin=$vin_input'>Back to Search Vendor</a></h3>";

      ?>

  </body>
</html>
