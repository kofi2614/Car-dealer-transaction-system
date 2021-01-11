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
    <h2>VIEW PARTS STATISTICS</h2>

    <?php
        $sql_query = "SELECT
        ven.vendor_name AS Vendor_Name,
        COUNT(*) AS Number_Of_Parts_Supplied,
        ROUND(SUM(p.part_cost),2) AS Total_Dollar_Amount_Spent_On_Parts
      FROM
        Parts_Order po INNER JOIN Parts p ON po.burdells_purchase_order_number=p.burdells_purchase_order_number
        INNER JOIN Vendor ven ON po.vendor_name=ven.vendor_name
      GROUP BY ven.vendor_name
      ORDER BY Total_Dollar_Amount_Spent_On_Parts DESC;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'parts_statistics');
              } else {
              echo "<br><h3>No Parts Statistics Available</h3>";
              }



    ?>


    </body>
    <script type="text/javascript">
      $(document).ready(function() {
            $('#parts_statistics').DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });

  </script>
</html>
