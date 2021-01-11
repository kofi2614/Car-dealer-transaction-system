<?php
include('lib/session.php');
include('lib/header.php');
include('lib/html_table.php');
?>
<!DOCTYPE html>
<html>

    <div class="header">
      <h2>Add Manufacturer</h2>

    </div>
  <div>
    <?php
       $errorMsgsearch="";
       //global variables;

      //keyword
      echo "<form method=\"POST\">";
      echo "<br><label for='keyword'>New Manufacturer:&nbsp </label><input type='test' value='' id='keyword' name='keyword' / >";
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
        $name_input=$_POST['keyword'];

        $sql_vd_1 = "INSERT INTO Manufacturer (manufacturer_name)
                     VALUES ('$name_input')";
        if($db->query($sql_vd_1) === TRUE){
          echo "New manufacturer added successfully";
        } else {
            echo "Manufacturer Already exists";
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
