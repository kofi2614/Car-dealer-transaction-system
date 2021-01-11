<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
      <h2>Sales Order Form Step 1</h2>
      <h3><a href='search_cust.php'>Search Customer</a></h3>
      <h2>Selling Vehicle</h2>
    <?php
      #vehicle type
      if(isset($_GET["vin"])){
      $vin = $_GET["vin"];
      } else {
        $vin='';}

      if(isset($_GET["cust_id"])){
      $cust_id = $_GET["cust_id"];
      } else {
        $cust_id = "";}

      //Query database based on the vin from get
      $sql_vd = "SELECT t.vin, t.model_year, t.type, t.model_name, t.manufacturer, t.mileage, t.price_for_sale, GROUP_CONCAT(t.vehicle_color SEPARATOR ',') AS colors, t.description FROM
                  (
                  SELECT v.vin, model_year, vehicle_type_name as type, model_name, manufacturer_name as manufacturer, mileage, ROUND((price_purchase*1.25  + COALESCE(SUM(part_cost)*1.1,0)), 2) AS price_for_sale, hc.vehicle_color, vehicle_description as description
                  FROM Vehicle v
                  LEFT JOIN Manufacturered_By mb ON v.vin = mb.vin
                  LEFT JOIN Has_Type ht ON v.vin = ht.vin
                  LEFT JOIN Has_Color hc ON v.vin=hc.vin
                  LEFT JOIN Parts p ON v.vin = p.vin
                  WHERE v.vin= '$vin'
                  GROUP BY v.vin, model_year, type, model_name, manufacturer, mileage, description, vehicle_color
                  ) t";
        $result_vd = $db->query($sql_vd);
        $count = mysqli_num_rows($result_vd);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_vd->fetch_assoc())  {
                    $dbdata[]=$row;
                    }




                    echo html_table($dbdata,'sell_vehicle_stp1');

              } else {
              echo "<br><h3>Invaid VIN!</h3>";
              }
      echo"<h2>To Customer</h2>";



      echo "<form method='POST'>";
      echo "<label for='cust_id'>Cust Id:&nbsp </label><input type='test' value='' id='keyword' name='cust_id' / >";
      echo "<br><label for='sold_date'>Selling Date:&nbsp </label><input type='date' value='' id='keyword' name='sold_date' / >";
      echo "<br>Need Loan:&nbsp";
      echo "<select name='need_loan'  id='need_loan'>";
      echo "<option>No</option>";
      echo "<option>Yes</option>";
      echo "</select>";
      //search button
      echo "<br>        " . "<input type='submit'value='Go to Step 2' name='submit' />";

      echo "</form>";

       if(isset($_POST['submit']))
       {
         $sold_date_input = $_POST['sold_date'];
         $cust_id_input = $_POST['cust_id'];
         $need_loan_input = $_POST['need_loan'];
         header("location: sell_vehicle_stp2.php?vin=$vin&cust_id=$cust_id_input&date=$sold_date_input&need_loan=$need_loan_input");
       };


    echo"<h3><a href='search_vehicle.php'>Back to Search</a></h3>";
    ?>


   </body>
   <script type="text/javascript">
     $(document).ready(function() {
           $('#sell_vehicle_stp1').DataTable({
             "paging":   false,
             "ordering": false,
             "info":     false,
             "searching": false
           });
   });
   </script>
</html>
