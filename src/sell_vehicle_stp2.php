<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
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

      if(isset($_GET["date"])){
      $sold_date_input = $_GET["date"];
      } else {
      $sold_date_input = "";}

      if(isset($_GET["need_loan"])){
      $need_loan_input = $_GET["need_loan"];
      } else {
      $need_loan_input = "No";}
      $sql_sal = "SELECT t.username, t.sales_staff_id FROM
                  (
                  SELECT username, sales_staff_id
                  FROM Salesperson
                  UNION
                  SELECT username, sales_staff_id
                  FROM Owner
                  ) t
                  WHERE t.username = '$login_session';";
      $result_sal = $db->query($sql_sal);
      $sales_id =  mysqli_fetch_row($result_sal);
      $sales_id = $sales_id[1];
      //echo $sales_id;


      echo"<h2>Sales Order Form Step 2</h2>";
      echo"<h2>Date:</h2><h3>$sold_date_input</h3>";
      echo"<h2>Vehicle:</h2>";

      //Query database for the vin
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

                    echo html_table($dbdata, 'selling_vehicle_result_2');
              } else {
              echo "<br><h3>Invaid VIN!</h3>";
              }
      echo"<h2>Customer:</h2>";
      //Query database for the cust_id
      $sql_ci = "SELECT t.* FROM
                (
                SELECT c.customer_id, bc.business_name AS name, 'Business' AS customer_type, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) AS address, c.phone_number FROM Business_Customer bc JOIN Customer c ON bc.customer_id = c.customer_id
                UNION
                SELECT c.customer_id, CONCAT(ic.first_name ,' ',ic.last_name)AS name, 'Individual' AS customer_type, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) AS address, c.phone_number FROM Individual_Person_Customer ic JOIN Customer c ON ic.customer_id = c.customer_id
                ) t
                WHERE t.customer_id = '$cust_id';
                ";
        $result_ci = $db->query($sql_ci);
        $count = mysqli_num_rows($result_ci);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_ci->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata, 'buying_customer_info');
              } else {
              echo "<br><h3>Invaid Cust_Id!</h3>";
              }

      $sql_vd_0 = "SELECT ROUND((v.price_purchase*1.25 + COALESCE(p.tot_part*1.1,0)), 2) AS price_for_sale
                   FROM Vehicle v
                   LEFT JOIN
                     (SELECT vin, SUM(part_cost) AS tot_part
                     FROM Parts GROUP BY vin) p
                   ON v.vin = p.vin
                   WHERE v.vin = '$vin' AND v.sold_date IS NULL;";
       $result_0=$db->query($sql_vd_0);
       $price_sold_input =  mysqli_fetch_row($result_0);
       $price_sold_input = $price_sold_input[0];
       echo "<h2> Transction Total: $price_sold_input</h2>";

      echo "<form method='POST'>";


      if ($need_loan_input =='Yes') {
      echo "<h2>Please Input Loan Info:</h2>";
      echo "<label for='start_month'>Start Month:&nbsp </label><input type='month' value='' id='start_month' name='start_month' / >";
      echo "<br><label for='loan_term'>Loan Term:&nbsp </label><input type='text' value='' id='loan_term' name='loan_term' / >";
      echo "<br><label for='montly_payment'>Montly Payment:&nbsp </label><input type='text' value='' id='monthly_payment' name='monthly_payment' / >";
      echo "<br><label for='interest_rate'>Interest Rate:&nbsp </label><input type='text' value='' id='interest_rate' name='interest_rate' / >";
      echo "<br><label for='downpayment'>Downpayment:&nbsp </label><input type='text' value='' id='downpayment' name='downpayment' / >";
      //search button
      }
      echo "<br><br>" . "<input type='submit'value='Confirm The Sale' name='submit' />";
      echo "</form>";


       if(isset($_POST['submit']))
       {
         if($need_loan_input =='No') {
         $sql_1 = "UPDATE Vehicle
                    SET sold_date = timestamp('$sold_date_input'), price_sold='$price_sold_input',
                    buyer_customer_id = '$cust_id'
                    WHERE vin='$vin';
                    INSERT INTO Sold_By (sales_staff_id, vin)
                                 VALUES ('$sales_id','$vin');
                   ";


           // $db->query($sql_vd);
           if ($db->multi_query($sql_1)) {

               echo "Vehicle status has been updated";
           } else {
               echo "Error: " . $sql_1 . "<br>" . $db->error;
             };
          } elseif($need_loan_input =='Yes') {
           // if(isset($_POST['start_month'])) {
             // echo $_POST['start_month'];
             // echo gettype($_POST['start_month']);




               $start_month_input = $_POST['start_month'];
               $loan_term_input = $_POST['loan_term'];
               $monthly_payment_input = $_POST['monthly_payment'];
               $downpayment_input = $_POST['downpayment'];
               $interest_rate_input = $_POST['interest_rate'];

               $sql_2 = "UPDATE Vehicle
                          SET sold_date = timestamp('$sold_date_input'), price_sold='$price_sold_input',
                          buyer_customer_id = '$cust_id'
                          WHERE vin='$vin';
                          INSERT INTO Sold_By (sales_staff_id, vin)
                                       VALUES ('$sales_id','$vin');
                          INSERT INTO Loan (vin, start_month, loan_term, monthly_payment,
                                            interest_rate, downpayment, customer_id)
                          VALUES ('$vin',
                                 TIMESTAMP(STR_TO_DATE(CONCAT('2019-11','-','01'), '%Y-%m-%d')),
                                 '$loan_term_input',
                                 '$monthly_payment_input', '$interest_rate_input',
                                 '$downpayment_input', '$cust_id');
                         ";

             // $db->query($sql_vd);
             if ($db->multi_query($sql_2)) {
                 echo "<br>Vehicle status has been updated";
                 echo "<br>Loan Information has been updated";
             } else {
                 echo "Error: " . $sql_2 . "<br>" . $db->error;
               };

         };
       unset($_POST['submit']);
    };
    echo"<h3><a href='sell_vehicle_stp1.php?vin=$vin'>Back to Previouc Step</a></h3>";
    echo"<h3><a href='vehicle_detail.php?vin=$vin'>Back to Vehicle Detail</a></h3>";
    echo"<h3><a href='search_vehicle.php'>Back to Search</a></h3>";
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
