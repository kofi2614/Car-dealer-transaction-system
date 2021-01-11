
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <title>Burdell's Ramblin' Wrecks</title>
  <link rel="stylesheet" type="text/css" href="css/theme.css"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
  <link rel="stylesheet" type="text/css" href="css/select2.min.css"/>
  <script type="text/javascript" src="js/select2.min.js"></script>
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/datatables.js"></script>
</head>
<body>
      <h1> <a href="main.php" style="text-decoration: none; color: #024A7C">Burdell's Ramblin' Wrecks</a></h1>
      <div class="user">
        <?php
        if ($login_usertype == 'PublicUser') {
            echo "<p>" . $login_session . " | " . $login_usertype . " | " . "<a href='login.php'>Login</a></p>";
        } else {
            echo "<p>" . $login_session . " | " . $login_usertype . " | " . "<a href = 'logout.php'>Logout</a></p>";
        }
        ?>
      <hr>
      </div>
</body>
