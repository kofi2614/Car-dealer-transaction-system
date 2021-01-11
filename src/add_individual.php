<?php
include('lib/session.php');
include('lib/header.php');
?>
<!DOCTYPE html>
<html>
  <body>
    <div class="header">
      <h2>Add Individual Customer</h2>
      <?php

        echo "<form method=\"POST\">";
        // echo "<br><label for='customer_id'>Customer Id:&nbsp </label><input type='text' value='' id='keyword' name='cust_id' / >";
        echo "<br><label for='street'>Street:&nbsp </label><input type='text' value='' id='keyword' name='street' / >";
        echo "<br><label for='city'>City:&nbsp </label><input type='text' value='' id='keyword' name='city' / >";
        echo "<br><label for='state'>State:&nbsp </label><input type='text' value='' id='keyword' name='State' / >";
        echo "<br><label for='postal_code'>Postal Code:&nbsp </label><input type='text' value='' id='keyword' name='postal_code' / >";
        echo "<br><label for='phone_number'>Phone Number:&nbsp </label><input type='text' value='' id='keyword' name='phone_number' / >";
        echo "<br><label for='email_address'>Email Address:&nbsp </label><input type='text' value='' id='keyword' name='email_address' / >";
        echo "<br><label for='driver_license_number'>Driver License Number:&nbsp </label><input type='text' value='' id='keyword' name='driver_license_number' / >";
        echo "<br><label for='first_name'>First Name:&nbsp </label><input type='text' value='' id='keyword' name='first_name' / >";
        echo "<br><label for='last_name'>Last Name:&nbsp </label><input type='text' value='' id='keyword' name='last_name' / >";
        echo "<br>        " . "<input type=\"submit\"value=\"save\" name=\"save\" />";
        echo "</form>";
        if(isset($_POST['save']))
        {
            // $cust_id_input = $_POST['cust_id'];
            $street_input = $_POST['street'];
            $city_input = $_POST['city'];
            $state_input = $_POST['State'];
            $postal_code_input = $_POST['postal_code'];
            $phone_number_input = $_POST['phone_number'];
            $email_input = $_POST['email_address'];
            $driver_license_input = $_POST['driver_license_number'];
            $first_name_input = $_POST['first_name'];
            $last_name_input = $_POST['last_name'];

            $sql_vd_1 = "INSERT INTO Customer (street, city, state,
              postal_code, phone_number, email_address)
                         VALUES ('$street_input', '$city_input', '$state_input',
                           '$postal_code_input', '$phone_number_input', '$email_input')
                      ";
            if ($db->query($sql_vd_1)=== TRUE) {

            $sql_vd_0 = ("SELECT customer_id
                            FROM Customer
                            WHERE phone_number = '$phone_number_input'
                            AND email_address = '$email_input'");
            // echo "Error: " . $sql_vd_0 . "<br>" . $db->error;
            $result_0 = $db->query($sql_vd_0);
            $cust_id_input = mysqli_fetch_row($result_0);
            $cust_id_input = $cust_id_input[0];
            // echo "$cust_id_input";
            $sql_vd_2 = "INSERT INTO Individual_Person_Customer (drivers_license_number,
              first_name, last_name, customer_id)
                         VALUES ('$driver_license_input', '$first_name_input',
                           '$last_name_input', '$cust_id_input')
                      ";
            if ($db->query($sql_vd_2) === TRUE) {
                echo "New record created successfully";
                echo "<br>New Customer ID: $cust_id_input";
            } else {
                echo "<br><br>Error: " . $sql_vd_2 . "<br>" . $db->error;
              };
          } else {
            echo "Error: " . $sql_vd_1 . "<br>" . $db->error;
          }
            unset($_POST['save']);
        };





      ?>

    </div>
    <div class="go back">
      <br>
      <br>
      <a href="search_cust.php" style="text-decoration: none; color: #024A7C">Back to Search Customer </a>
      <br><a href="search_vehicle.php" style="text-decoration: none; color: #024A7C">Back to Search Vehicle </a>
    </div>
  </body>
</html>
