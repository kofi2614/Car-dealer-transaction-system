<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title>Welcome to Burdell's Ramblin' Wrecks</title>
    <link rel="stylesheet" type="text/css" href="theme.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
    <script type="text/javascript" src="jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="datatables.js"></script>
  </head>
  <body>
    <a href="reports_main.php">Back to All Reports</a><br>
    <h2>VIEW AVERAGE TIME IN INVENTORY</h2>
    <?php
        $sql_query = "SELECT
        vt.vehicle_type_name AS Vehicle_Type,
        IFNULL(ROUND(AVG(DATEDIFF(v.sold_date,v.purchase_date)),2),'N/A') AS Average_Time_In_Inventory
      FROM
        Has_Type ht INNER JOIN Vehicle v ON ht.vin=v.vin
        RIGHT JOIN Vehicle_Type vt ON vt.vehicle_type_name=ht.vehicle_type_name
      WHERE Sold_date IS NOT NULL #is a sold vehicle
      GROUP BY vt.vehicle_type_name;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'average_time_in_inventory');
              } else {
              echo "<br><h3>No Inventory Record</h3>";
              }
    ?>
  </body>
  <script type="text/javascript">
      $(document).ready(function() {
            $("#average_time_in_inventory").DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });
  </script>
</html>
