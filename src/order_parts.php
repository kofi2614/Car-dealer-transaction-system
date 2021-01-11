<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
      <h2>Order Parts</h2>
      <?php

          $vin = $_GET["vin"];
          $vin_input = $vin;
          //echo "<form method=\"POST\">";
          echo "VIN: $vin_input";
          # burdells_purchase_order_number
          $res_burdells_purchase_order_number=mysqli_query($db,"SELECT * FROM Parts_Order WHERE vin = '$vin_input'");
          echo "<form name='part_order' id='part_order' method='POST'>";
          echo "Burdell's Purchase Order Number:&nbsp";
          echo "<select name='burdells_purchase_order_number'  id='burdells_purchase_order_number'>";
          while ($row=mysqli_fetch_array($res_burdells_purchase_order_number))
              {
                echo "<option>$row[burdells_purchase_order_number]</option>";
              }
          echo "<option>New Purchase Order Number</option>";
          echo "</select>";
          echo "<br><label for='part_number'>Part Number:&nbsp </label><input type='text' value='' id='part_number' name='part_number' / >";
          echo "<br><label for='part_cost'>Part Cost:&nbsp </label><input type='text' value='' id='part_cost' name='part_cost' / >";
          echo "<br><label for='description_of_the_part'>Description of the Part:&nbsp </label><input type='text' value='' id='description_of_the_part' name='description_of_the_part' / >";
          echo "<br><input type='submit' value='Complete' name='submit' >";
          echo "</form>";

          if(isset($_POST['submit']))
          {
            $vendor_name_input = $_GET["vendor_name"];
            if ($_POST['burdells_purchase_order_number']!='New Purchase Order Number') {
              $burdells_purchase_order_number_input = $_POST['burdells_purchase_order_number'];
            } else {
              $sql = "INSERT INTO Parts_Order (vin, burdells_purchase_order_number, vendor_name)
                      SELECT '$vin_input',CONCAT('$vin_input','-',RIGHT(CONCAT('00',IFNULL(MAX(RIGHT(po.burdells_purchase_order_number,3)),0)+1),3)),'$vendor_name_input'
                      FROM Parts_Order po
                      WHERE po.vin = '$vin_input'";
              $sql_1 = "SELECT MAX(burdells_purchase_order_number)
                        FROM Parts_Order
                        WHERE vin = '$vin_input'";
              if ($db->query($sql) === TRUE) {
                echo "Parts order has been added to the vendor successfully.";
              }

              $result = $db->query($sql_1);
              $burdells_purchase_order_number_input = mysqli_fetch_array($result)[0];

          };

            $part_number_input = $_POST['part_number'];
            $part_cost_input = $_POST['part_cost'];
            $part_status_input = "ordered";
            $description_of_the_part_input = $_POST['description_of_the_part'];
            $sql_vd_1 = "INSERT INTO Parts (vin, burdells_purchase_order_number, part_number, part_cost, part_status, description_of_the_part)
                         VALUES('$vin_input', '$burdells_purchase_order_number_input', '$part_number_input','$part_cost_input', '$part_status_input',
                         '$description_of_the_part_input')";

            if ($db->query($sql_vd_1) === TRUE ) {
                echo "<br>Parts information has been added successfully.";
            } else {
                echo "Invalid Input";
            };
                unset($_POST['complete']);
          }
          echo "<h3><a href='search_vendor.php?vin=$vin_input'>Back to Search Vendor</a></h3>";
          echo "<h3><a href='vehicle_detail.php?vin=$vin_input'>Back to Vehicle Detail</a></h3>";

      ?>

  </body>
  <script type="text/javascript">
  $("#part_order").submit(function(e) {
    if (
      document.getElementById('part_number').value == '' ||
      document.getElementById('part_cost').value == '' ||
      document.getElementById('description_of_the_part').value == ''
    ) {
      alert ("Please Fill in All Fields!");
             return false;
    } else {
             return true;
    }
  });

    $(document).ready(function() {
          $('table').DataTable({
            "paging":   false,
            "ordering": false,
            "info":     false,
            "searching": false
          });
  });
  </script>
</html>
