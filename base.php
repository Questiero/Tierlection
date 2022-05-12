<?php 

  // URL of the host
  $dbhost = "mysql-questiero.alwaysdata.net"; 
  
  // Name of the database
  $dbname = "questiero_tierlection";
  
  // User name
  $dbuser = "questiero_tl";
  
  // Password (not used here)
  $dbpass = "tierlection";
 
  try {
    $connection = new PDO('mysql:host='.$dbhost.';dbname='.$dbname, $dbuser, $dbpass);
  } catch(PDOException $e) {
    echo $e->getMessage();
  }

?>