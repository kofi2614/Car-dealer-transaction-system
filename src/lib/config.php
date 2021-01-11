<?php
   define('DB_SERVER', '127.0.0.1:3306');
   define('DB_USERNAME', 'root');
   define('DB_PASSWORD', '');
   define('DB_DATABASE', 'CS6400_TEAM056');
   $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

   if(mysqli_connect_error())
   {
     echo "Connection Failed". mysqli_connect_error();
   }

?>
