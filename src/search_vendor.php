<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
          table tr:not(:first-child){
            cursor: pointer;
            transition: all .25s ease-in-out;
          }
          .selected{background-color: red; font-weight: bold;color:#fff;}
    </style>
  </head>
  <body>
      <h2>Search Vendor</h2>
    <?php
       $vin = $_GET["vin"];
       $vin_input = $vin;
       $errorMsgsearch="";

       echo "<form method=\"POST\">";
       echo "<label for='keyword'>Keyword:&nbsp </label><input type='test' value='' id='keyword' name='keyword' / >";
       echo "&nbsp&nbsp" . "<input type='submit' value='Submit' name='submit' >";
       echo "</form>";
      echo "<p>Can not find vendor? <a href='add_vendor.php?vin=$vin_input'>Add Vendor</a></p>";

       if(isset($_POST['submit']))
       {
          $keyword_select=$_POST['keyword'];

         //search vendor base on the input
          $sql_sv = "SELECT v.vendor_name, v.phone_number, v.street, v.city, v.state, v.postal_code
                    FROM Vendor v
                    WHERE v.vendor_name LIKE '%$keyword_select%'
                    OR v.phone_number LIKE '$keyword_select'
                    OR v.street LIKE '%$keyword_select%'
                    OR v.city LIKE '%$keyword_select%'
                    OR v.state LIKE '%$keyword_select%'
                    OR v.postal_code LIKE '$keyword_select'
                    ORDER BY v.vendor_name ASC";
          $result_sv = $db->query($sql_sv);
          echo $db->error;
          $count = mysqli_num_rows($result_sv);

        if ($count > 0) {
          echo "<h2>Vendor</h2>";
          $dbdata = array();
          //Fetch into associative array
          while ( $row = $result_sv->fetch_assoc())  {
          $dbdata[]=$row;
          }
          echo html_table($dbdata,'vendor');

        };

        if ($count == 0) {
          echo "<br><h3>No Vendor found. Please add the vendor.</h3>";

        }
        unset($_POST['submit']);
        unset($keyword_select);
      };

      ?>

  </body>
  <script type="text/javascript">
    $(document).ready(function() {
          $('#vendor').DataTable();
    });
    var table = $ ("#vendor").DataTable();

    $('#vendor tbody'). on('click', 'tr', function() {
      var data = table.row(this).data ();
      var myvar = "<?php echo $vin_input; ?>";
      var new_url = 'order_parts.php?vin='+ myvar+'&vendor_name=' + data[0];
      location.href = new_url;
    });

  </script>
</html>
