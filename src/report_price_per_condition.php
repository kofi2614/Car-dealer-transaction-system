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
    <h2>VIEW PRICE PER CONDITION</h2>
    <?php
        $sql_query = "SELECT t1.Vehicle_Type,Excellent,Very_Good,Good,Fair
        FROM
          (SELECT vt.vehicle_type_name AS Vehicle_Type,IFNULL(ROUND(AVG(v.price_purchase),2),0) AS Excellent
          FROM
            Has_Type ht INNER JOIN Vehicle v ON ht.vin=v.vin AND v.vehicle_condition='Excellent'
            RIGHT JOIN Vehicle_Type vt ON vt.vehicle_type_name=ht.vehicle_type_name
          GROUP BY vt.vehicle_type_name) t1 INNER JOIN
          (SELECT vt.vehicle_type_name AS Vehicle_Type,IFNULL(ROUND(AVG(v.price_purchase),2),0) AS Very_Good
          FROM
            Has_Type ht INNER JOIN Vehicle v ON ht.vin=v.vin AND v.vehicle_condition='Very Good'
            RIGHT JOIN Vehicle_Type vt ON vt.vehicle_type_name=ht.vehicle_type_name
          GROUP BY vt.vehicle_type_name) t2 ON t1.Vehicle_Type=t2.Vehicle_Type INNER JOIN
          (SELECT vt.vehicle_type_name AS Vehicle_Type,IFNULL(ROUND(AVG(v.price_purchase),2),0) AS Good
          FROM
            Has_Type ht INNER JOIN Vehicle v ON ht.vin=v.vin AND v.vehicle_condition='Good'
            RIGHT JOIN Vehicle_Type vt ON vt.vehicle_type_name=ht.vehicle_type_name
          GROUP BY vt.vehicle_type_name) t3 ON t2.Vehicle_Type=t3.Vehicle_Type INNER JOIN
          (SELECT vt.vehicle_type_name AS Vehicle_Type,IFNULL(ROUND(AVG(v.price_purchase),2),0) AS Fair
          FROM
            Has_Type ht INNER JOIN Vehicle v ON ht.vin=v.vin AND v.vehicle_condition='Fair'
            RIGHT JOIN Vehicle_Type vt ON vt.vehicle_type_name=ht.vehicle_type_name
          GROUP BY vt.vehicle_type_name) t4 ON t3.Vehicle_Type=t4.Vehicle_Type
        ORDER BY t1.Vehicle_Type;";
        $result_set = $db->query($sql_query);
        $count = mysqli_num_rows($result_set);

        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'price_per_condition');
              } else {
              echo "<br><h3>No Record Found</h3>";
              }



    ?>


    </body>
    <script type="text/javascript">
      $(document).ready(function() {
            $('#price_per_condition').DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });

  </script>
</html>
