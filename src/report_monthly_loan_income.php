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
    <h2>VIEW MONTHLY LOAN INCOME</h2>
     <?php
        $sql_query_1 = "CREATE TABLE IF NOT EXISTS month_list (month varchar(2) NOT NULL, PRIMARY KEY (month));
        DELETE FROM month_list;
        INSERT INTO month_list
          VALUES ('01'),('02'),('03'),('04'),('05'),('06'),('07'),('08'),('09'),('10'),('11'),('12');

        CREATE TABLE IF NOT EXISTS year_list (year varchar(4)  NOT NULL, PRIMARY KEY (year));
        DELETE FROM year_list;
        INSERT INTO year_list
          VALUES(EXTRACT(YEAR FROM CURDATE())),(EXTRACT(YEAR FROM CURDATE())-1);

        CREATE TABLE IF NOT EXISTS past_12_months (yearmonth varchar(6) NOT NULL) AS
          (
          SELECT CONCAT(year,month) AS yearmonth
          FROM year_list JOIN month_list
          WHERE
            CONCAT(year,month)>=EXTRACT(YEAR_MONTH FROM DATE_SUB(CURDATE(), INTERVAL 1 YEAR))
            AND CONCAT(year,month)<=EXTRACT(YEAR_MONTH FROM CURDATE())
          ORDER BY CONCAT(year,month) DESC LIMIT 12
          );
        DROP TABLE month_list,year_list;";

        $sql_query_2="SELECT yearmonth AS `Year_Month`,IFNULL(ROUND(SUM(monthly_payment),2),0) AS Total_Monthly_Payment,IFNULL(ROUND(SUM(0.01*monthly_payment),2),0) AS `Mr. Burdell's Share`
        FROM
          past_12_months m LEFT JOIN
          (SELECT
            vin,EXTRACT(YEAR_MONTH FROM DATE_ADD(start_month,INTERVAL 1 MONTH)) AS loan_income_start,
            EXTRACT(YEAR_MONTH FROM DATE_ADD(start_month, INTERVAL loan_term MONTH)) AS loan_income_end,
            monthly_payment
          FROM Loan) l
          ON loan_income_start<=yearmonth AND loan_income_end>=yearmonth
        GROUP BY yearmonth
        ORDER BY yearmonth DESC;";

        $sql_query_3="DROP TABLE past_12_months;";

        mysqli_multi_query($db,$sql_query_1);
        while (mysqli_next_result($db)){mysqli_next_result($db);} #skip meaningless returns.
        $result_set = mysqli_query($db,$sql_query_2);
        $count = mysqli_num_rows($result_set);
        echo $db->error;
        if ($count > 0) {
                  //Initialize array variable
                    $dbdata = array();
                  //Fetch into associative array
                    while ( $row = $result_set->fetch_assoc())  {
                    $dbdata[]=$row;
                    }

                    echo html_table($dbdata,'monthly_loan_income');
              } else {
              echo "<br><h3>No Seller History</h3>";
              }
        mysqli_query($db,$sql_query_3);
    ?>
    </body>
    <script type="text/javascript">
      $(document).ready(function() {
            $('#monthly_loan_income').DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });
  </script>
</html>
