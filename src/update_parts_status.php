<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
      <h2>Update Parts Status</h2>
      <?php

          $vin = $_GET["vin"];
          $burdells_purchase_order_number = $_GET["burdells_purchase_order_number"];
          $part_number = $_GET["part_number"];
          //echo "VIN: $vin<br>";
          //echo "Burdells Purchase Order Number: $burdells_purchase_order_number<br>";
          echo "Part Number: $part_number";
          //show the current part information
          $sql_pd = "SELECT part_number, description_of_the_part, po.vendor_name, p.burdells_purchase_order_number, part_cost, part_status
                    FROM Vehicle v
                    JOIN Parts p ON v.vin = p.vin
                    JOIN Parts_Order po ON p.vin = po.vin
                    AND p.burdells_purchase_order_number = po.burdells_purchase_order_number
                    WHERE v.vin = '$vin'
                    AND p.part_number = '$part_number'
                    ";
            $result_pd = $db->query($sql_pd);
            $count = mysqli_num_rows($result_pd);

            if ($count > 0) {
                      //Initialize array variable
                        $dbdata = array();
                      //Fetch into associative array
                        while ( $row = $result_pd->fetch_assoc())  {
                        $dbdata[]=$row;
                        }

                        echo html_table($dbdata,'parts_details_individual');
                  } else {
                  echo "<h3>There is no part record for this VIN</h3>";
                  }

          //
          $sql = "SELECT part_status
                  FROM Parts
                  WHERE vin = '$vin' AND burdells_purchase_order_number = '$burdells_purchase_order_number'
                  AND part_number = '$part_number'";
          $result = $db->query($sql);
          $status = mysqli_fetch_array($result)[0];
          echo "<br>Current Status:$status";
          # part_status
          echo "<form id='row' method='POST'>";
          echo "Update Part Status:&nbsp";
          echo "<select name='part_status'  id='part_status'>";
          if ($status=='ordered') {
          echo "<option value = 'ordered' disabled> ordered </option>";
          echo "<option value = 'received'>received </option>";
          echo "<option value = 'installed' disabled> installed </option>";
        } elseif ($status=='received') {
          echo "<option value = 'ordered' disabled> ordered </option>";
          echo "<option value = 'received' disabled>received </option>";
          echo "<option value = 'installed'> installed </option>";
        } else {
          echo "<option value = 'ordered' disabled> ordered </option>";
          echo "<option value = 'received' disabled>received </option>";
          echo "<option value = 'installed' disabled> installed </option>";
        }
        echo "</select>";
        echo "&nbsp&nbsp<input type='submit' value='update' name='update' >";
        echo "</form>";

          if(isset($_POST['update']))
          {
            $part_status_input = $_POST['part_status'];
            $sql_vd_1 = "UPDATE Parts p
                         SET part_status = '$part_status_input'
                         WHERE p.vin = '$vin' AND p.burdells_purchase_order_number = '$burdells_purchase_order_number' AND p.part_number = '$part_number'";
            if ($db->query($sql_vd_1) === TRUE) {
                echo "Parts status has been updated";
            } else {
                echo "Error: " . $sql_vd_1 . "<br>" . $db->error;
            };
                unset($_POST['confirm']);
          }

          echo "<h3><a href='vehicle_detail.php?vin=$vin'>Back to Vehicle Detail</a></h3>";

      ?>

  </body>
  <script type="text/javascript">
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
