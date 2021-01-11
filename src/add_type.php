<?php
include('lib/session.php');
include('lib/header.php');
include('lib/html_table.php');
?>
<!DOCTYPE html>
<html>
  <body>

      <h2>Add Vehicle Type</h2>

  <div>
    <?php
       $errorMsgsearch="";
      //keyword
      echo "<form method=\"POST\">";
      echo "<br><label for='keyword'>New Vehicle Type:&nbsp </label><input type='test' value='' id='keyword' name='keyword' / >";
      //search button
      echo "<br>        " . "<input type=\"submit\"value=\"Add\" name=\"submit\" />";
      echo "</form>";

  ?>
<br>
<br>
    <?php
    //create funcation to translate array data to html table


    if(isset($_POST['submit']))
    {
        $type_input=$_POST['keyword'];

        $sql_vd_1 = "INSERT INTO Vehicle_Type (vehicle_type_name)
                     VALUES ('$type_input')";
        if($db->query($sql_vd_1) === TRUE){
          echo "New type added successfully";
        } else {
            echo "Type Already exists";
    };
  }
  ?>
</div>
<div class="go back">
  <br>
  <br>
  <a href="add_vehicle.php" style="text-decoration: none; color: #024A7C">Back to Add Vehicle </a>
</div>
  </body>
  <script type="text/javascript">
    $(document).ready(function() {
          $('#queryResult').DataTable();
    });
    var table = $ ("#queryResult").DataTable();

    $('#queryResult tbody'). on('click', 'tr', function() {
      var data = table.row(this).data ();
      var new_url = 'vehicle_detail.php?vin=' + data[0];
      location.href = new_url;
    });

  </script>
</html>
