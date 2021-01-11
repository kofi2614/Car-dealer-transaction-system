<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title>Burdell's Ramblin' Wrecks</title>
  <link rel="stylesheet" type="text/css" href="theme.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
  <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="datatables.js"></script>
</head>
  <body>
    <div class="header">
      <h1>Add Loan</h1>
      <hr>
    </div>

    <?php
      #vehicle type

      echo "<form method=\"POST\">";
      echo "<br><label for='start_year'>Start Year:&nbsp </label><input type='text' value='' id='keyword' name='start_year' / >";
      echo "<br><label for='start_month'>Start Month:&nbsp </label><input type='text' value='' id='keyword' name='start_month' / >";
      echo "<br><label for='loan_term'>Loan Term:&nbsp </label><input type='test' value='' id='keyword' name='loan_term' / >";
      echo "<br><label for='monthly_payment'>Month Payment:&nbsp </label><input type='test' value='' id='keyword' name='monthly_payment' / >";
      echo "<br><label for='downpayment'>Downpayment:&nbsp </label><input type='test' value='' id='keyword' name='downpayment' / >";
      echo "<br><label for='interest_rate'>Interest Rate:&nbsp </label><input type='test' value='' id='keyword' name='interest_rate' / >";

      //search button
      echo "<br>        " . "<input type=\"submit\"value=\"Confirm\" name=\"submit\" />";
      echo "</form>";

       if(isset($_POST['submit']))
       {
        // $vin_input = $_POST['VIN'];
        $start_year_input = $_POST['start_year'];
        $start_month_input = $_POST['start_month'];
        $loan_term_input = $_POST['loan_term'];
        $monthly_payment_input = $_POST['monthly_payment'];
        $downpayment_input = $_POST['downpayment'];
        $interest_rate_input = $_POST['interest_rate'];
        $vin_input = '0T5ZIYO5EKW808246';
        $customer_id_input = 159;

        // echo $price_sold_input;
        $sql_vd = "INSERT INTO Loan (vin, start_month, loan_term, monthly_payment,
          interest_rate, downpayment, customer_id)
                   VALUES ('$vin_input',
                          TIMESTAMP(MAKEDATE($start_year_input, $start_month_input)),
                          '$loan_term_input',
                          '$monthly_payment_input', '$interest_rate_input',
                          '$downpayment_input', '$customer_id_input');
                  ";

          // $db->query($sql_vd);
          if ($db->query($sql_vd) === TRUE) {
              echo "Loan Information has been updated";
          } else {
              echo "Error: " . $sql_vd . "<br>" . $db->error;
                 }
        unset($_POST['submit']);

      };

    echo"<h3><a href='welcome.php'>Back to Search</a></h3>";
    ?>


   </body>
   <script type="text/javascript">
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
