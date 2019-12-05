<?php
  // DB connection credentials
  $url = 'post.php';
  $servername = "classmysql.engr.oregonstate.edu";
  $username = "cs340_zaragozu";
  $password = "3243";
  $dbname = "cs340_zaragozu";

  // establish connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // test connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // query to insert into the warehouses table using info from form in warehouse.html
  $sql =
    "INSERT INTO warehouses (street_address, city, state, zip)
    VALUES ('" . $_GET['address'] . "', '" . $_GET['city'] . "', '" . $_GET['state'] . "', '" . $_GET['zipCode'] . "')";

  // run the query and exit if fails
  if (!($conn->query($sql) === TRUE)) {
      die('Could not create warehouse');
  }

  // close the DB connection and redirect URL
  $conn->close();
  header( "Location: $url" );
?>
