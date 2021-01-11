<?php
include('lib/session.php');
include('lib/header.php');
?>
<!DOCTYPE html>
<html>
  <body>

      <h2>Add Business Customer</h2>
      <?php
            echo "<form method=\"POST\">";
            // echo "<br><label for='customer_id'>Customer Id:&nbsp </label><input type='text' value='' id='keyword' name='cust_id' / >";
            echo "<br><label for='street'>Street:&nbsp </label><input type='text' value='' id='keyword' name='street' / >";
            echo "<br><label for='city'>City:&nbsp </label><input type='text' value='' id='keyword' name='city' / >";
            echo "<br><label for='state'>State:&nbsp </label><input type='text' value='' id='keyword' name='State' / >";
            echo "<br><label for='postal_code'>Postal Code:&nbsp </label><input type='text' value='' id='keyword' name='postal_code' / >";
            echo "<br><label for='phone_number'>Phone Number:&nbsp </label><input type='text' value='' id='keyword' name='phone_number' / >";
            echo "<br><label for='email_address'>Email Address:&nbsp </label><input type='text' value='' id='keyword' name='email_address' / >";
            echo "<br><label for='tax_identification_number'>Tax Identification Number:&nbsp </label><input type='test' value='' id='keyword' name='tax_identification_number' / >";
            echo "<br><label for='business_name'>Business Name:&nbsp </label><input type='test' value='' id='keyword' name='business_name' / >";
            echo "<br><label for='primary_contact_first_name'>Primary Contact First Name:&nbsp </label><input type='test' value='' id='keyword' name='primary_contact_first_name' / >";
            echo "<br><label for='primary_contact_last_name'>Primary Contact Last Name:&nbsp </label><input type='test' value='' id='keyword' name='primary_contact_last_name' / >";
            echo "<br><label for='primary_contact_title'>Primary Contact Title:&nbsp </label><input type='test' value='' id='keyword' name='primary_contact_title' / >";

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
              $tax_id_input = $_POST['tax_identification_number'];
              $business_name_input = $_POST['business_name'];
              $primary_concact_first_name_input = $_POST['primary_contact_first_name'];
              $primary_concact_last_name_input = $_POST['primary_contact_last_name'];
              $primary_concact_title_input = $_POST['primary_contact_title'];
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
                $sql_vd_2 = "INSERT INTO Business_Customer (tax_identification_number,
                  business_name, primary_contact_first_name, primary_contact_last_name,
                   primary_contact_title, customer_id)
                             VALUES ('$tax_id_input', '$business_name_input',
                               '$primary_concact_first_name_input',
                               '$primary_concact_last_name_input', '$primary_concact_title_input',
                               '$cust_id_input');
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
                unset($cust_id_input);

          }


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
