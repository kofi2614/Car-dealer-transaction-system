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
    <h2>VIEW MONTHLY SALES SUMMARY</h2>
    <?php
        $sql_query = "SELECT
        EXTRACT(YEAR_MONTH FROM v.sold_date) AS `Year_Month`,
        COUNT(*) AS Vehicles_Sold,
        SUM(v.price_sold) AS Total_Income,
        SUM(v.price_sold-v.price_purchase-
        CASE WHEN v_p.cost_of_parts IS NULL THEN 0
                    ELSE v_p.cost_of_parts
                    END
        ) AS Total_Net_Income
      FROM
        Vehicle v LEFT JOIN
        (SELECT v.vin,SUM(p.part_cost) AS cost_of_parts
          FROM Vehicle v INNER JOIN Parts_Order po ON v.vin=po.vin
             INNER JOIN Parts p ON p.vin=po.vin AND p.burdells_purchase_order_number=po.burdells_purchase_order_number
           GROUP BY v.vin) v_p
        ON v.vin=v_p.vin WHERE v.sold_date IS NOT NULL
      GROUP BY EXTRACT(YEAR_MONTH FROM v.sold_date)
      ORDER BY 1 DESC;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'monthly_sales_summary');
              } else {
              echo "<br><h3>No Sales History Available</h3>";
              }



    ?>


    </body>
    <script type="text/javascript">
    $(document).ready(function() {
          $('#monthly_sales_summary').DataTable({
            "lengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "paging":   true,
            "ordering": false,
            "info": false,
            "searching": true
          });
    });



    $('#monthly_sales_summary'). on('click', 'tr', function() {
      var data = $ ("#monthly_sales_summary").DataTable().row(this).data ();
      var new_url = 'report_monthly_sales_detail.php?year_month=' + data[0];
      location.href = new_url;
    });

  </script>
</html>
