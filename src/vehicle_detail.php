<?php
   include('lib/session.php');
   include('lib/header.php');
   include("lib/html_table.php");
?>
<!DOCTYPE html>
<html>
  <body>
    <?php
    $vin = $_GET["vin"];
    echo "<h2>VIN: $vin</h2>";
    echo "<h2>Vehicle Details</h2>";

    //Query database based on the vin from get
    $sql_vd = "SELECT t.vin, t.model_year, t.type, t.model_name, t.manufacturer, t.mileage, t.price_for_sale, GROUP_CONCAT(t.vehicle_color SEPARATOR ',') AS colors, t.description FROM
                (
                SELECT v.vin, model_year, vehicle_type_name as type, model_name, manufacturer_name as manufacturer, mileage, ROUND((price_purchase*1.25  + COALESCE(SUM(part_cost)*1.1,0)), 2) AS price_for_sale, hc.vehicle_color, vehicle_description as description
                FROM Vehicle v
                LEFT JOIN Manufacturered_By mb ON v.vin = mb.vin
                LEFT JOIN Has_Type ht ON v.vin = ht.vin
                LEFT JOIN Has_Color hc ON v.vin=hc.vin
                LEFT JOIN Parts p ON v.vin = p.vin
                WHERE v.vin= '$vin'
                GROUP BY v.vin, model_year, type, model_name, manufacturer, mileage, description, vehicle_color
                ) t";
      $result_vd = $db->query($sql_vd);
      $count = mysqli_num_rows($result_vd);

      if ($count > 0) {
                //Initialize array variable
                  $dbdata = array();
                //Fetch into associative array
                  while ( $row = $result_vd->fetch_assoc())  {
                  $dbdata[]=$row;
                  }

                  echo html_table($dbdata,'vehicle_detail_result');
            } else {
            echo "<br><h3>Invaid VIN!</h3>";
            }

    //more details for Inven_clerk
    if ($login_usertype == 'Inven_clerk' || $login_usertype == 'Manager' || $login_usertype == 'Owner') {
    echo "<h2>Parts Details</h2>";

    $sql_pd = "SELECT part_number, description_of_the_part, po.vendor_name, p.burdells_purchase_order_number, part_cost, part_status
              FROM Vehicle v
              JOIN Parts p ON v.vin = p.vin
              JOIN Parts_Order po ON p.vin = po.vin
              AND p.burdells_purchase_order_number = po.burdells_purchase_order_number
              WHERE v.vin = '$vin'
              ";
      $result_pd = $db->query($sql_pd);
      $count = mysqli_num_rows($result_pd);

      if ($count > 0) {
                //Initialize array variable
                  $dbdata = array();
                //Fetch into associative array
                  while ( $row = $result_pd->fetch_assoc())  {
                  $dbdata[]=$row;
                  }
                  if ($login_usertype == 'Manager') {
                  echo html_table($dbdata,'parts_details_result_manager');
                } else {
                  echo html_table($dbdata,'parts_details_result');
                }
            } else {
            echo "<h3>There is no part record for this VIN</h3>";
            }
    echo "<h2>Cost Breakdown</h2>";

    $sql_cost = "SELECT price_purchase, COALESCE(SUM(part_cost),0) as sum_parts from Vehicle v
                LEFT JOIN Parts p ON v.vin = p.vin
                LEFT JOIN Parts_Order po ON p.vin = po.vin
                AND p.burdells_purchase_order_number = po.burdells_purchase_order_number
                WHERE v.vin = '$vin'
                ";
    $result_cost = $db->query($sql_cost);
    $count = mysqli_num_rows($result_cost);

    if ($count > 0) {
              //Initialize array variable
                $dbdata = array();
              //Fetch into associative array
                while ( $row = $result_cost->fetch_assoc())  {
                $dbdata[]=$row;
                }

                echo html_table($dbdata,'cost_break_result');
          } else {
          echo "<br><h3>Invaid VIN!</h3>";
          }


    }

    if ($login_usertype == 'Manager' || $login_usertype == 'Owner') {
      echo "<h2>Inventory Staff</h2>";
      $sql_inv = "SELECT g.name as inventory_staff_name, DATE(v.purchase_date) as purchase_date FROM Vehicle v
                JOIN Checked_In_By c
                ON v.vin = c.vin
                JOIN
                (
                	   SELECT  CONCAT(first_name, ' ' , last_name) as name, inventory_staff_id FROM Owner
                	   UNION
                	   SELECT  CONCAT(first_name, ' ' , last_name) as name, inventory_staff_id FROM Inventory_Clerk
                	) g
                ON c.inventory_staff_id = g.inventory_staff_id
                WHERE v.vin = '$vin'
              ";
      $result_inv = $db->query($sql_inv);
      $count = mysqli_num_rows($result_inv);

    if ($count > 0) {
              //Initialize array variable
                $dbdata = array();
              //Fetch into associative array
                while ( $row = $result_inv->fetch_assoc())  {
                $dbdata[]=$row;
                }

                echo html_table($dbdata,'inventory_staff_result');
          } else {
          echo "<br><h3>Invaid VIN!</h3>";
          }

      echo "<h2>Seller Info</h2>";
      $sql_sd = "SELECT
      cust_info_s.name as seller_name,
      cust_info_s.address as seller_address,
      cust_info_s.phone_number as seller_phone_num
      	FROM
      		(
      			SELECT v.vin, model_year, model_name, mileage, vehicle_description, manufacturer_name, vehicle_type_name, GROUP_CONCAT(hc.vehicle_color SEPARATOR ',') AS vehicle_colors, price_purchase, purchase_date, price_sold, sold_date, buyer_customer_id, seller_customer_id
      			FROM Vehicle v
      			LEFT JOIN Manufacturered_By mb ON v.vin = mb.vin
      			LEFT JOIN Has_Type ht ON v.vin = ht.vin
      			LEFT JOIN Has_Color hc ON v.vin=hc.vin
      			LEFT JOIN Parts p ON v.vin = p.vin
      			GROUP BY v.vin, model_year, model_name, mileage, vehicle_description, manufacturer_name, vehicle_type_name, price_purchase, purchase_date, price_sold, sold_date, buyer_customer_id, seller_customer_id
      		) a
              INNER JOIN
              (
      		SELECT c.customer_id, bc.business_name as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Business_Customer bc JOIN Customer c on bc.customer_id = c.customer_id
      		UNION
      		SELECT c.customer_id, CONCAT(ic.first_name ,' ',ic.last_name)as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Individual_Person_Customer ic JOIN Customer c on ic.customer_id = c.customer_id
      		) cust_info_s
              ON a.seller_customer_id = cust_info_s.customer_id
              LEFT JOIN
              (
      		SELECT c.customer_id, bc.business_name as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Business_Customer bc JOIN Customer c on bc.customer_id = c.customer_id
      		UNION
      		SELECT c.customer_id, CONCAT(ic.first_name ,' ',ic.last_name)as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Individual_Person_Customer ic JOIN Customer c on ic.customer_id = c.customer_id
      		) cust_info_b
               ON a.buyer_customer_id = cust_info_b.customer_id
                    WHERE a.vin = '$vin'
                    ";
            $result_sd = $db->query($sql_sd);
            $count = mysqli_num_rows($result_sd);

          if ($count > 0) {
                    //Initialize array variable
                      $dbdata = array();
                    //Fetch into associative array
                      while ( $row = $result_sd->fetch_assoc())  {
                      $dbdata[]=$row;
                      }

                      echo html_table($dbdata,'sell_info_result');
                } else {
                echo "<br><h3>Invaid VIN!</h3>";
                }


        }
        if ($login_usertype == 'Manager' || $login_usertype == 'Owner' ||$login_usertype == 'Sales') {
          echo "<h2>Buyer Info</h2>";
          $sql_bd = "SELECT
                    cust_info_b.name as buyer_name,
                    cust_info_b.address as buyer_address,
                    cust_info_b.phone_number as buyer_phone_num
                    FROM Vehicle v
                      JOIN
                      (
                    SELECT c.customer_id, bc.business_name as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Business_Customer bc JOIN Customer c on bc.customer_id = c.customer_id
                    UNION
                    SELECT c.customer_id, CONCAT(ic.first_name ,' ',ic.last_name)as name, CONCAT(c.street,' ',c.city,' ',c.state,' ',c.postal_code) as address, c.phone_number FROM Individual_Person_Customer ic JOIN Customer c on ic.customer_id = c.customer_id
                    ) cust_info_b
                       ON v.buyer_customer_id = cust_info_b.customer_id
                    WHERE v.vin = '$vin'
                        ";
                $result_bd = $db->query($sql_bd);
                $count = mysqli_num_rows($result_bd);

              if ($count > 0) {
                        //Initialize array variable
                          $dbdata = array();
                        //Fetch into associative array
                          while ( $row = $result_bd->fetch_assoc())  {
                          $dbdata[]=$row;
                          }
                          echo html_table($dbdata,'buyer_info_result');
                    } else {
                    echo "<h3>This vehicle has not been sold yet</h3>";
                    }

            echo "<h2>Loan Info</h2>";
                $sql_l = "SELECT
                          DATE(l.start_month) as start_month,l.loan_term, l.monthly_payment, l.interest_rate, l.downpayment
                          FROM
                          Loan l
                          JOIN Vehicle v
                          ON v.vin=l.vin
                          WHERE v.buyer_customer_id=l.customer_id
                          AND v.vin = '$vin'
                          ";
                  $result_l = $db->query($sql_l);
                  $count = mysqli_num_rows($result_l);

                  if ($count > 0) {
                            //Initialize array variable
                              $dbdata = array();
                            //Fetch into associative array
                              while ( $row = $result_l->fetch_assoc())  {
                              $dbdata[]=$row;
                              }

                              echo html_table($dbdata,'loan_info_result');
                        } else {
                        echo "<h3>There is no loan record for this VIN</h3>";
                      }


            echo "<h2>Sales Staff</h2>";
            $sql_inv = "SELECT g.name as sales_staff_name, DATE(v.sold_date) as sold_date FROM Vehicle v
                      JOIN Sold_By c
                      ON v.vin = c.vin
                      JOIN
                      (
                      	   SELECT  CONCAT(first_name, ' ' , last_name) as name, sales_staff_id FROM Owner
                      	   UNION
                      	   SELECT  CONCAT(first_name, ' ' , last_name) as name, sales_staff_id FROM Salesperson
                      	) g
                      ON c.sales_staff_id = g.sales_staff_id
                      WHERE v.vin = '$vin'
                    ";
            $result_inv = $db->query($sql_inv);
            $count = mysqli_num_rows($result_inv);

          if ($count > 0) {
                    //Initialize array variable
                      $dbdata = array();
                    //Fetch into associative array
                      while ( $row = $result_inv->fetch_assoc())  {
                      $dbdata[]=$row;
                      }

                      echo html_table($dbdata,'sales_staff_result');
                } else {
                echo "<h3>This vehicle has not been sold yet</h3>";
                }
      }


    if ($login_usertype == 'Owner' ||$login_usertype == 'Sales') {
      $sql_forsale = "SELECT DISTINCT a.vin FROM
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
        			      AND a.pending_parts LIKE 'F'
            	      AND a.if_sold LIKE 'F'
                    AND a.vin = '$vin'
                    ";
                    $result_forsale = $db->query($sql_forsale);
                    $count = mysqli_num_rows($result_forsale);

                    if ($count == 1) {
  						            echo"<h3><a href='sell_vehicle_stp1.php?vin=$vin'>Sell This Vehicle</a></h3>";
                          } else {
                          echo "<h3>NO Sale Option for This Vehicle</h3>";
                          }
    }



    if ($login_usertype == 'Inven_clerk' || $login_usertype == 'Owner') {
      echo "<h3><a href='search_vendor.php?vin=$vin'>Add a Part Order</a></h3>";
    }

    echo"<h3><a href='search_vehicle.php'>Back to Search</a></h3>";
    ?>

  </body>
  <script type="text/javascript">
      $(document).ready(function() {
            $('#parts_details_result_manager,#vehicle_detail_result,#cost_break_result,#inventory_staff_result,#sell_info_result,#loan_info_result,#buyer_info_result,#sales_staff_result').DataTable({
              "paging":   false,
              "ordering": false,
              "info":     false,
              "searching": false
            });
      });
      var table = $ ("#parts_details_result").DataTable();

      $('#parts_details_result tbody'). on('click', 'tr', function() {
        var data = table.row(this).data ();
        var myvar = "<?php echo $vin; ?>";
        var new_url = 'update_parts_status.php?vin='+ myvar+'&part_number='+data[0]+'&burdells_purchase_order_number=' + data[3];
        location.href = new_url;
      });

  </script>
</html>
