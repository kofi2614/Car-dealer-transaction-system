<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="js/select2.min.js"></script>
  </head>
  <body>
    <div class="header">
      <h2>Add Vehicle</h2>
    </div>
    <div class="Add/Search Customer">
      <a href="search_cust.php" style="text-decoration: none; color: #024A7C">Search Customer </a>
      <!-- <br><a href="add_individual.php" style="text-decoration: none; color: #024A7C">Add Individual Customer </a>
      <br><a href="add_business.php" style="text-decoration: none; color: #024A7C">Add Business Customer </a> -->
      <br>
    </div>


    <?php

      $sql_inv = "SELECT t.username, t.inventory_staff_id FROM
                  (
                  SELECT username, inventory_staff_id
                  FROM Inventory_Clerk
                  UNION
                  SELECT username, inventory_staff_id
                  FROM Owner
                  ) t
                  WHERE t.username = '$login_session';";
      $result_inv = $db->query($sql_inv);
      $inventory_id =  mysqli_fetch_row($result_inv);
      $inventory_id = $inventory_id[1];
      //echo"$inventory_id";

      #vehicle type
      $res_vehicle_type=mysqli_query($db,"SELECT * FROM Vehicle_Type");
      echo "<form name = 'add_vehicle' id='add_vehicle' method='POST'>";
      echo "Select Vehicle Type:&nbsp";
      echo "<select name='vehicle_type'  id='vehicle_type'>";


      //echo "<option selected=true disabled> - </option>";
      while ($row=mysqli_fetch_array($res_vehicle_type))
           {
             echo "<option>$row[vehicle_type_name]</option>";
           }
      echo "</select>";

      // echo "&nbsp Add Vehicle Type";
      echo "&nbsp<a href='add_type.php' style='text-decoration: none; '>Add Vehicle Type </a>";

      #vehicle manufacturer
      $res_model_year=mysqli_query($db,"SELECT * FROM Manufacturer");
      echo "<br>Select Manufacturer:&nbsp";
      echo "<select class='selectsearch' name='manufacturer' id='manufacturer' >";
      // echo "<option> ALL </option>";

      while ($row=mysqli_fetch_array($res_model_year))
           {
             echo "<option>$row[manufacturer_name]</option>";
           }

      echo "</select>";

      echo "&nbsp<a href='add_manu.php' style='text-decoration: none; color=#024A7C '>Add Manufacturer </a><br>";

      echo "<br>";

      #select colors
      $color_list=mysqli_query($db,"SELECT * FROM Color");
      echo "<label for='emp-id' class='form-input-label'>Color: </label>";
      echo "<select class='js-example-basic-multiple' name='color[]' multiple = 'multiple' >";
      while ($row=mysqli_fetch_array($color_list))
           {
             echo "<option>$row[vehicle_color]</option>";
           }
      echo "</select>";

      // echo gettype(date("Y"));
      echo "<br><label for='VIN'>VIN:&nbsp </label><input type='test' value='' id='VIN' name='VIN' / >";
      echo "<br><label for='vehicle_description'>Vehicle Description:&nbsp </label><input type='test' value='' id='vehicle_description' name='vehicle_description' / >";
      echo "<br><label for='model_name'>Model Name:&nbsp </label><input type='test' value='' id='model_name' name='model_name' / >";
      echo "<br><label for='model_year'>Model Year:&nbsp </label><input type='test' value='' id='model_year' name='model_year' / >";
      //echo "<br><label for='vehicle_condition'>Vehicle Condition:&nbsp </label><input type='test' value='' id='keyword' name='vehicle_condition' / >";
      //Change vechiclde condition to a drop down list
      echo "<br>Vehicle Condition:&nbsp";
      echo "<select name='vehicle_condition'  id='vehicle_condition'>";
      echo "<option>Excellent</option>";
      echo "<option>Very Good</option>";
      echo "<option>Good</option>";
      echo "<option>Fair</option>";
      echo "</select>";

      echo "<br><label for='mileage'>Mileage:&nbsp </label><input type='test' value='' id='mileage' name='mileage' / >";
      echo "<br><label for='price_purchase'>Purchase Price:&nbsp </label><input type='test' value='' id='price_purchase' name='price_purchase' / >";
      echo "<br><label for='purchase_date'>Purchase Date:&nbsp </label><input type='date' value='' id='purchase_date' name='purchase_date' / >";
      echo "<br><label for='cust_id'>Seller Cust Id:&nbsp </label><input type='test' value='' id='cust_id' name='cust_id' / >";
      //search button
      echo "<br>        " . "<input type='submit'value='Add Vehicle' name='submit' />";
      echo "</form>";


       if(isset($_POST['submit']))
       {
        $vehicle_type_input=$_POST['vehicle_type'];
        $manufacturer_input=$_POST['manufacturer'];
        $vin_input = $_POST['VIN'];
        $vehicle_description_input = $_POST['vehicle_description'];
        $model_name_input = $_POST['model_name'];
        $model_year_input = $_POST['model_year'];
        $vehicle_condition_input = $_POST['vehicle_condition'];
        $mileage_input = $_POST['mileage'];
        $price_purchase_input = $_POST['price_purchase'];
        $purchase_date_input = $_POST['purchase_date'];
        $cust_id_input = $_POST['cust_id'];

        $color_input = $_POST['color'];
        // $cust_id_input = $_POST["cust_id"];

        // echo "<br>".$vehicle_type_input;
        // echo "<br>".$manufacturer_input;
        // echo "<br>".$vin_input;
        // echo "<br>".$vehicle_description_input;
        // echo "<br>".$model_name_input;
        // echo "<br>".$model_year_input;
        // echo "<br>".$vehicle_condition_input;
        // echo "<br>".$mileage_input;
        // echo "<br>".$price_purchase_input;
        // echo "<br>".$purchase_date_input;
        $sql_vd_1 = "INSERT INTO Vehicle (vin, vehicle_description, model_name,
          vehicle_condition, mileage, buyer_customer_id,
          seller_customer_id, price_sold, sold_date, price_purchase, purchase_date, model_year)
                   VALUES ('$vin_input', '$vehicle_description_input', '$model_name_input',
                     '$vehicle_condition_input','$mileage_input',
                     NULL,'$cust_id_input',NULL, NULL, '$price_purchase_input', '$purchase_date_input', '$model_year_input');

                     INSERT INTO Has_Type (vehicle_type_name, vin)
                     VALUES ('$vehicle_type_input','$vin_input');

                     INSERT INTO Manufacturered_By (manufacturer_name, vin)
                     VALUES ('$manufacturer_input','$vin_input');

                     INSERT INTO Checked_In_By (inventory_staff_id, vin)
                     VALUES ('$inventory_id','$vin_input');
                  ";
          $sql_vd_2 = "";
          foreach ($color_input as $colors)
               {
                 $sql_vd_2.= "INSERT INTO Has_Color (vehicle_color, vin)
                             VALUES ('$colors', '$vin_input');";
               };
          $sql = $sql_vd_1.$sql_vd_2;

        if ($model_year_input>(date("Y")+1)) {
          echo "Invalid Model Year";
        } else {
          if ($db->multi_query($sql)) {
              echo "New record created successfully";
              // foreach ($color_input as $colors)
              //      {
              //        $sql_vd_2 = "INSERT INTO Has_Color (vehicle_color, vin)
              //                    VALUES ('$colors', '$vin_input');";
              //         $db->query($sql_vd_2);
              //      };
               // $sql_vd_3 = "INSERT INTO Has_Type (vehicle_type_name, vin)
               //              VALUES ('$vehicle_type_input','$vin_input');";
               // $sql_vd_4 = "INSERT INTO Manufacturered_By (manufacturer_name, vin)
               //              VALUES ('$manufacturer_input','$vin_input');";
               // $sql_vd_5 = "INSERT INTO Checked_In_By (inventory_staff_id, vin)
               //              VALUES ('$inventory_id','$vin_input');";
               // $sql_vd_5 = ""
               // $db->query($sql_vd_3);
               // $db->query($sql_vd_4);
               // $db->query($sql_vd_5);
               // echo $db->error;
          } else {
              echo "Error: " . $sql_vd_1 . "<br>" . $db->error;
                 }
        unset($_POST['submit']);

  }
};
    echo"<h3><a href='search_vehicle.php'>Back to Search</a></h3>";
    ?>


   </body>

   <script type="text/javascript">
   $("#add_vehicle").submit(function(e) {
     if (
       document.getElementById('vehicle_type').value == '' ||
       document.getElementById('manufacturer').value == '' ||
       document.getElementById('VIN').value == '' ||
       document.getElementById('vehicle_description').value == '' ||
       document.getElementById('model_name').value == '' ||
       document.getElementById('model_year').value == '' ||
       document.getElementById('vehicle_condition').value == '' ||
       document.getElementById('mileage').value == '' ||
       document.getElementById('price_purchase').value == '' ||
       document.getElementById('purchase_date').value == '' ||
       document.getElementById('cust_id').value == ''
     ) {
       alert ("Please Fill in All Fields!");
              return false;
     } else {
              return true;
     }
   });


   $(document).ready(function() {

 $(".js-example-basic-multiple").select2({
   // placeholder: "Select Color",
   // position:'absolute',
   width : 400
 }).on('change', function(e) {
   if($(this).val() && $(this).val().length) {
     $(this).next('.select2-container')
       .find('li.select2-search--inline input.select2-search__field').attr('placeholder', 'Select items');
   }
 });
});
     $(document).ready(function() {
           $('#queryResult').DataTable({
             "paging":   false,
             "ordering": false,
             "info":     false,
             "searching": false
           });
   });


   </script>
</html>
