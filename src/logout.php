<?php
   session_start();

   if(session_destroy()) {
      header("Location: search_vehicle.php");
   }
?>
