<?php
  $url = 'post.php';
  $servername = "classmysql.engr.oregonstate.edu";
  $username = "cs340_zaragozu";
  $password = "3243";
  $dbname = "cs340_zaragozu";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql =
    "INSERT INTO warehouses (street_address, city, state, zip)
    VALUES ('" . $_GET['address'] . "', '" . $_GET['city'] . "', '" . $_GET['state'] . "', '" . $_GET['zipCode'] . "')";


  if (!($conn->query($sql) === TRUE)) {
      die('Could not create warehouse');
  }

  $conn->close;
  header( "Location: $url" );
?>
