<?php
   include('lib/session.php');
   include('lib/header.php');
   include('lib/html_table.php');
?>

<!DOCTYPE html>
<html>
  <body>
    <h2>SEARCH VEHICLE</h2>
    <?php
       $errorMsgsearch="";
       //global variables;
       $vehicle_type_select="";
       $manufacturer_select="";
       $model_year_select="";
       $vehicle_color_select="";
       $keyword_select="";
       //show how many cars aviliable
       $sql_nv = "SELECT count(v.vin) AS num FROM Vehicle v
                 WHERE v.vin NOT IN
                (
                 SELECT DISTINCT
                 v.vin
                 FROM Vehicle v
                 JOIN Parts p
                 ON v.vin=p.vin
                 WHERE part_status !='installed'
                 AND sold_date IS NULL

                 UNION

                 SELECT DISTINCT
                 v.vin
                 FROM Vehicle v
                 WHERE sold_date IS NOT NULL
               )";
         $result_nv = $db->query($sql_nv);
         $row = mysqli_fetch_array($result_nv,MYSQLI_ASSOC);
         $num_avilable= $row['num'];
         echo"<h3>Total Vehicles Avilable: <mark>" . $num_avilable . "</mark></h3>";

       //show how many car with pending parts
       if ($login_usertype == 'Owner' || $login_usertype == 'Manager' || $login_usertype == 'Inven_clerk') {
         $sql_pp = "SELECT count(DISTINCT vin) AS parts_pending_count FROM
    		    (
    				SELECT v.vin, v.vehicle_description, t.vehicle_type_name, v.model_year, m.manufacturer_name, v.model_name, v.mileage, c.vehicle_color, v.sold_date,
    					CASE WHEN EXISTS (SELECT * FROM Parts WHERE v.vin = Parts.vin AND Parts.part_status IN ('ordered','received')) THEN 'T' #cars parts needed
    					ELSE 'F'
    				END AS pending_parts
    				FROM Vehicle v
    				INNER JOIN
    				Has_Type t
    				ON v.vin = t.vin
    				INNER JOIN
    				Manufacturered_By m
    				ON v.vin = m.vin
    				INNER JOIN
    				Has_Color c
    				ON v.vin = c.vin
    		    ) a
    	    WHERE 1=1
    			AND a.vin LIKE '%'
    			AND a.pending_parts LIKE 'T'
    	    AND a.sold_date is NULL";
           $result_pp = $db->query($sql_pp);
           $row = mysqli_fetch_array($result_pp,MYSQLI_ASSOC);
           $parts_pending= $row['parts_pending_count'];
           echo"<h3>Vehicle with Parts Pending: <mark>" . $parts_pending . "</mark></h3>";
       }
       //show how many car sold
       if ($login_usertype == 'Owner' || $login_usertype == 'Manager') {
         $sql_sv = "SELECT count(DISTINCT vin) AS sold_count FROM
            (
            SELECT v.vin, v.vehicle_description, t.vehicle_type_name, v.model_year, m.manufacturer_name, v.model_name, v.mileage, c.vehicle_color, v.sold_date,
              CASE WHEN EXISTS (SELECT * FROM Parts WHERE v.vin = Parts.vin AND Parts.part_status IN ('ordered','received')) THEN 'T' #cars parts needed
              ELSE 'F'
            END AS pending_parts
            FROM Vehicle v
            INNER JOIN
            Has_Type t
            ON v.vin = t.vin
            INNER JOIN
            Manufacturered_By m
            ON v.vin = m.vin
            INNER JOIN
            Has_Color c
            ON v.vin = c.vin
            ) a
          WHERE 1=1
          AND a.vin LIKE '%'
          AND a.pending_parts LIKE '%'
          AND a.sold_date is NOT NULL";
           $result_sv = $db->query($sql_sv);
           $row = mysqli_fetch_array($result_sv,MYSQLI_ASSOC);
           $sold_count = $row['sold_count'];
           echo"<h3>Total Sold Vehicle: <mark>" . $sold_count . "</mark></h3>";
       }


       #vehicle type
       $res_vehicle_type=mysqli_query($db,"SELECT * FROM Vehicle_Type");
       echo "<form id='row' method='POST'>";
       echo "Select Vehicle Type:&nbsp";
       echo "<select name='vehicle_type'  id='vehicle_type'>";
       echo "<option> ALL </option>";
       while ($row=mysqli_fetch_array($res_vehicle_type))
            {
              echo "<option>$row[vehicle_type_name]</option>";
            }
       echo "</select>";

       #vehicle manufacturer
       $res_model_year=mysqli_query($db,"SELECT * FROM Manufacturer");
       echo "<br>Select Manufacturer:&nbsp";
       echo "<select class='selectsearch' name='manufacturer' id='manufacturer' >";
       echo "<option> ALL </option>";
       while ($row=mysqli_fetch_array($res_model_year))
            {
              echo "<option>$row[manufacturer_name]</option>";
            }
       echo "</select>";

       #vehicle model year, display only the aviliable options
       $res_model_year=mysqli_query($db,
       "SELECT DISTINCT model_year FROM Vehicle a ORDER BY model_year DESC");
       echo "<br>Select Model Year:&nbsp";
       echo "<select name='model_year' id='model_year'>";
       echo "<option> ALL </option>";
       while ($row=mysqli_fetch_array($res_model_year))
            {
              echo "<option>$row[model_year]</option>";
            }
       echo "</select>";

       #vehicle color
       $res_color=mysqli_query($db,"SELECT * FROM Color");
       echo "<br>Select Color:&nbsp";
       echo "<select name='vehicle_color'  id='vehicle_color'>";
       echo "<option> ALL </option>";
       while ($row=mysqli_fetch_array($res_color))
            {
              echo "<option>$row[vehicle_color]</option>";
            }
       echo "</select>";

      //keyword
      echo "<br><label for='keyword'>Keyword:&nbsp </label><input type='test' value='' id='keyword' name='keyword' / >";

      //VIN search box for all login users
      if ($login_usertype != 'PublicUser') {
      echo "<br><label for='vin'>VIN:&nbsp </label><input type='test' value='' id='vin' name='vin' / >";
      }

      if ($login_usertype == 'Owner' || $login_usertype == 'Manager') {
      echo "<br>Select if Sold:&nbsp";
      echo "<select name='if_sold'  id='if_sold'>";
      echo "<option>ALL</option>";
      echo "<option>T</option>";
      echo "<option>F</option>";
      echo "</select>";
      }

      //search button
      echo "<br>        " . "<input type='submit'value='Search' name='submit' />";
      echo "</form>";

       if(isset($_POST['submit']))
       {
           if ($_POST['vehicle_type']!='ALL') {
             $vehicle_type_select=$_POST['vehicle_type'];
           } else {
             $vehicle_type_select='%';
           };

           if ($_POST['manufacturer']!='ALL') {
             $manufacturer_select=$_POST['manufacturer'];
           } else {
             $manufacturer_select='%';
           };

           if ($_POST['model_year']!='ALL') {
             $model_year_select=$_POST['model_year'];
           } else {
             $model_year_select='%';
           };

           if ($_POST['vehicle_color']!='ALL') {
             $vehicle_color_select=$_POST['vehicle_color'];
           } else {
             $vehicle_color_select='%';
           };

           if ($_POST['keyword']!='') {
             $keyword_select=$_POST['keyword'];
           } else {
             $keyword_select='%';
           };

           if ($login_usertype != 'PublicUser') {
               if ($_POST['vin']!='') {
                 $vin_select=$_POST['vin'];
               } else {
                 $vin_select='%';
               };
             } else {
               $vin_select='%';
             }

          if ($login_usertype == 'Inven_clerk' || $login_usertype == 'Manager' || $login_usertype == 'Owner' ) {
            $pending_parts = '%';
          } else {
            $pending_parts = 'F';
          }

          if ($login_usertype == 'Owner' || $login_usertype == 'Manager') {
              if ($_POST['if_sold']!='ALL') {
                $if_sold=$_POST['if_sold'];
              } else {
                $if_sold='%';
              };

              $if_sold_column = ", a.if_sold ";
          } else  {
                $if_sold='F';
              $if_sold_column = " ";
          }

          echo "<br>";
          echo "<br>";

          if ($login_usertype == 'Inven_clerk' || $login_usertype == 'Manager' || $login_usertype == 'Owner' ) {
            $pending_parts_column=", a.pending_parts as parts_p ";
          } else {
            $pending_parts_column=" ";
          }

    //search vehicle base on the input
    $sql_sv = "SELECT a.vin, a.vehicle_type_name as type, a.model_year as year, a.manufacturer_name as manufacturer, a.model_name as model, GROUP_CONCAT(a.vehicle_color SEPARATOR ',') AS colors, a.mileage, price_for_sale as price" . $pending_parts_column . $if_sold_column . "FROM
              (
      				SELECT v.vin, v.vehicle_description, t.vehicle_type_name, v.model_year, m.manufacturer_name, v.model_name, v.mileage, v.price_purchase, ROUND((v.price_purchase*1.25  + COALESCE(SUM(p.part_cost)*1.1,0) ),2) AS price_for_sale, c.vehicle_color, v.sold_date,
      					CASE WHEN EXISTS (SELECT * FROM Parts WHERE v.vin = Parts.vin AND Parts.part_status IN ('ordered','received')) THEN 'T' #cars parts needed
      					ELSE 'F'
      				END AS pending_parts,
  						CASE WHEN v.sold_date is NULL THEN 'F' ELSE 'T' END AS IF_SOLD
      				FROM Vehicle v
      				INNER JOIN
      				Has_Type t
      				ON v.vin = t.vin
      				INNER JOIN
      				Manufacturered_By m
      				ON v.vin = m.vin
      				INNER JOIN
      				Has_Color c
      				ON v.vin = c.vin
                      LEFT JOIN
                      Parts p
                      ON v.vin = p.vin
                      GROUP BY v.vin, model_year, t.vehicle_type_name, v.model_year, model_name, m.manufacturer_name, mileage, v.vehicle_description, vehicle_color
      		    ) a
      	    WHERE 1=1
            AND a.vehicle_type_name LIKE '$vehicle_type_select'
            AND a.manufacturer_name LIKE '$manufacturer_select'
            AND a.model_year LIKE '$model_year_select'
            AND a.vin LIKE '$vin_select'
            AND (
      			a.manufacturer_name LIKE '%$keyword_select%'
      			OR
      			a.model_year LIKE '%$keyword_select%'
      			OR
      			a.model_name LIKE '%$keyword_select%'
      			OR
            a.vehicle_type_name LIKE '%$keyword_select%'
            OR
      			a.vehicle_description LIKE '%$keyword_select%'
      	     )
    		  AND a.pending_parts LIKE '$pending_parts'
    	    AND a.if_sold LIKE '$if_sold'
    	    GROUP BY a.vin, a.vehicle_type_name, a.model_year, a.manufacturer_name, a.model_name, a.mileage
          HAVING colors LIKE '%$vehicle_color_select%'
    	    ORDER BY a.vin ASC";

    $result_sv = $db->query($sql_sv);
    $count = mysqli_num_rows($result_sv);
    echo $db->error;

        if ($count > 0) {
            //Initialize array variable
              $dbdata = array();
            //Fetch into associative array
              while ( $row = $result_sv->fetch_assoc())  {
              $dbdata[]=$row;
              }
              echo html_table($dbdata,'vehicle_search_result');
        } elseif ($count == 0) {
        echo "<br><h2>Sorry, it look like we don't have that in stock!</h2>";
            }
  } else {
        echo "<br><h2>Please search vehicle using the above conditions</h2>";
}
    ?>
  </body>
  <script type="text/javascript">
    $(document).ready(function() {
          $('#vehicle_search_result').DataTable();
    });
    var table = $ ("#vehicle_search_result").DataTable();

    $('#vehicle_search_result tbody'). on('click', 'tr', function() {
      var data = table.row(this).data ();
      var new_url = 'vehicle_detail.php?vin=' + data[0];
      location.href = new_url;
    });

  </script>
</html>
