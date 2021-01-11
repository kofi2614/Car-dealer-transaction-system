<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
  <body>
    <div>
      <h2>Search Customer</h2>
      <a href="add_individual.php" style="text-decoration: none; color: #024A7C">Add Individual Customer </a>
      <br><a href="add_business.php" style="text-decoration: none; color: #024A7C">Add Business Customer </a>
    <?php
       $errorMsgsearch="";
       //global variables;

      //keyword
      echo "<form method='POST'>";
      echo "<label for='keyword'>Keyword:&nbsp </label><input type='test' value='' id='keyword' name='keyword' / >";
      //search button
      echo "<br>        " . "<input type='submit'value='Search' name='submit' />";
      echo "</form>";


    if(isset($_POST['submit']))
    {
        $keyword_select=$_POST['keyword'];

        $sql_vd_1 = "SELECT a.customer_id, a.first_name, a.last_name,
                            a.drivers_license_number
                     FROM Individual_Person_Customer a
                     WHERE a.first_name LIKE '%$keyword_select%'
                     OR a.last_name LIKE '%$keyword_select%'
                     OR a.customer_id LIKE '$keyword_select'";

        $sql_vd_2 = "SELECT a.customer_id, a.business_name, a.primary_contact_title, a.primary_contact_first_name,
                     a.primary_contact_last_name,a.tax_identification_number
                    FROM Business_Customer a
                    WHERE a.business_name LIKE '%$keyword_select%'
                    OR a.primary_contact_first_name LIKE '%$keyword_select%'
                    OR a.primary_contact_last_name LIKE '%$keyword_select%'
                    OR a.customer_id LIKE '$keyword_select'";
        $result_1 = $db->query($sql_vd_1);
        $result_2 = $db->query($sql_vd_2);
        echo $db->error;
        // echo "Error: " . $sql_vd_1 . "<br>" . $db->error;
        // echo '<br>';
        // echo "Error: " . $sql_vd_2 . "<br>" . $db->error;
        $count_1 = mysqli_num_rows($result_1);
        $count_2 = mysqli_num_rows($result_2);
        if ($count_1 > 0) {
            //Initialize array variable

            echo "<h2>Individual Customer</h2>";
            $dbdata = array();
          //Fetch into associative array
            while ( $row = $result_1->fetch_assoc())  {
            $dbdata[]=$row;
            }

            echo html_table($dbdata, 'individual_cust_search_result');

        };

        if ($count_2 > 0) {
          echo "<h2>Business Customer</h2>";
          $dbdata = array();
        //Fetch into associative array
          while ( $row = $result_2->fetch_assoc())  {
          $dbdata[]=$row;
          }

          echo html_table($dbdata, 'busine_cust_search_result');

        };

        if ($count_1 == 0 AND $count_2 == 0) {
          echo "<br><h3>Customer does't exist</h3>";
        };
        unset($_POST['submit']);
        unset($keyword_select);
        };


  ?>
</div>
<div class="go back">
  <br>
  <br>
  <?php
    if  ($login_usertype == "Inven_clerk") {
      echo "<a href='add_vehicle.php' style='text-decoration: none; color: #024A7C'>Back to Add Vehicle </a>";
    } 
    echo "<br><a href='search_vehicle.php' style='text-decoration: none; color: #024A7C'>Back to Search Vehicle </a>";
   ?>
</div>
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

    // $('#queryResult tbody'). on('click', 'tr', function() {
    //   var data = table.row(this).data ();
    //   var new_url = 'vehicle_detail.php?vin=' + data[0];
    //   location.href = new_url;
    // });

    // $(document).ready(function() {
    //       $('#individual_cust_search_result').DataTable();
    // });
    // var table = $ ("#individual_cust_search_result").DataTable();
    //
    // $('#individual_cust_search_result tbody'). on('click', 'tr', function() {
    //   var data = table.row(this).data ();
    //   var new_url = 'add_vehicle.php?cust_id=' + data[0];
    //   location.href = new_url;
    // });
    //
    // $(document).ready(function() {
    //       $('#busine_cust_search_result').DataTable();
    // });
    // var table = $ ("#busine_cust_search_result").DataTable();
    //
    // $('#busine_cust_search_result tbody'). on('click', 'tr', function() {
    //   var data = table.row(this).data ();
    //   var new_url = 'add_vehicle.php?cust_id=' + data[0];
    //   location.href = new_url;
    // });

  </script>
</html>
