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
    <style>
.red {
  background-color: red !important;
}
  </style>
  </head>
  <body>
    <a href="reports_main.php">Back to All Reports</a><br>
    <h2>VIEW SELLER HISTORY</h2>
    <?php
        $sql_query = "SELECT Seller_Name,Number_Of_Vehicles_Sold,Average_Purchase_Price,Average_Number_Of_Parts_Ordered,Average_Cost_Of_Parts
        FROM
        (
        SELECT
          IFNULL(bc.tax_identification_number,ic.drivers_license_number) AS ID,IFNULL(bc.business_name,CONCAT(ic.first_name,' ',ic.last_name)) AS Seller_Name,
          COUNT(DISTINCT v.vin) AS Number_Of_Vehicles_Sold,
          ROUND(AVG(v.price_purchase),2) AS Average_Purchase_Price,
          ROUND(AVG(v_parts.no_of_parts),2) AS Average_Number_Of_Parts_Ordered,
          ROUND(AVG(v_parts.cost_of_parts),2) AS Average_Cost_Of_Parts
        FROM
          Vehicle v INNER JOIN Customer c ON v.seller_customer_id=c.customer_id
          LEFT JOIN Individual_Person_Customer ic ON c.customer_id=ic.customer_id
          LEFT JOIN Business_Customer bc ON c.customer_id=bc.customer_id
          INNER JOIN
          (SELECT
            v.vin,COUNT(*) AS no_of_parts,SUM(IFNULL(p.part_cost,0)) AS cost_of_parts
          FROM
                Vehicle v LEFT JOIN Parts p ON v.vin=p.vin
            GROUP BY v.vin) v_parts
          ON v.vin=v_parts.vin
        GROUP BY IFNULL(bc.tax_identification_number,ic.drivers_license_number),IFNULL(bc.business_name,CONCAT(ic.first_name,' ',ic.last_name))
        ) src
        ORDER BY Number_Of_Vehicles_Sold DESC,Seller_Name;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'seller_history');
              } else {
              echo "<br><h3>No Seller History</h3>";
              }
    ?>


    </body>
    <script type="text/javascript">
      var table = $(document).ready(function() {
            $('#seller_history').DataTable({
              "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
              "paging":   true,
              "ordering": false,
              "info": false,
              "searching": true,
              "createdRow": function( row, data, dataIndex ) {
                if ( data[3] >=5 || data[4]>=500) {
                  $(row).addClass('red');
                  }
              }
            });
      });

  </script>
</html>
