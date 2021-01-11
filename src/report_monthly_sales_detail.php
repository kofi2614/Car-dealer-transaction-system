<?php
   include('lib/session.php');
   include('lib/header.php');
   include('lib/html_table.php');
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
    <a href="report_monthly_sales_summary.php">Back to Summary</a><br>
    <h2>VIEW MONTHLY SALES DETAIL</h2>
    <?php
        $Year_Month=$_GET['year_month']??'';
    ?>
    <?php
        $sql_query = "SELECT CONCAT(first_name,' ',last_name) AS Name,
        COUNT(*) AS Vehicles_Sold_This_Month,
        SUM(v.price_sold) AS Total_Sales_This_Month
      FROM
        Vehicle v INNER JOIN
        (SELECT first_name,last_name,vin
          FROM
            Sold_By INNER JOIN Sales_Management_Staff ON Sold_By.sales_staff_id=Sales_Management_Staff.sales_staff_id
            INNER JOIN
            (SELECT first_name,last_name,sales_staff_id FROM Salesperson
             UNION
             SELECT first_name,last_name,sales_staff_id FROM Owner) s_detail
            ON Sales_Management_Staff.sales_staff_id=s_detail.sales_staff_id) s_v
        ON v.vin=s_v.vin
      WHERE EXTRACT(YEAR_MONTH FROM v.sold_date)='$Year_Month' AND v.sold_date IS NOT NULL
      GROUP BY CONCAT(first_name,last_name)
      ORDER BY Vehicles_Sold_This_Month DESC,Total_Sales_This_Month DESC;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'monthly_sales_detail');
              } else {
              echo "<br><h3>Invalid Query.</h3>";
              }



    ?>


    </body>
    <script type="text/javascript">
      $(document).ready(function() {
            $('#monthly_sales_detail').DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });

  </script>
</html>
