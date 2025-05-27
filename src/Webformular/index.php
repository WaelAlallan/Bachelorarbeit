<?php
session_start();

if (!isset($_SESSION['loggedin'])) {    # if this session variable isn't set, this means the user isn't loggedin
   header('location: ./Login/login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
   <title>Homepage</title>
   <link href="./layout/style.css" rel="stylesheet" type="text/css">
</head>

<body>
   <div class="header">
      <h2>Home Page</h2>
   </div>
   <div class="content">

      <div class="nav_item">
         <h3> <a href="./Krankmeldung/krankmeldung.php">Krankmeldung</a></h3>
      </div>
      <div class="nav_item">
         <h3><a href="./Gesundmeldung/gesundmeldung.php">Gesundmeldung</a></h3>
      </div>
      <div class="nav_item">
         <h3><a href="./Urlaubsantrag/urlaubsantrag.php">Urlaubsantrag</a></h3>
      </div>
      <div class="nav_item">
         <h3><a href="./Verwaltung/verwaltung.php">Verwaltung</a></h3>
      </div>
   </div>

</body>

</html>