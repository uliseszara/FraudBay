<?php
  $servername = "classmysql.engr.oregonstate.edu";
  $username = "cs340_zaragozu";
  $password = "3243";
  $dbname = "cs340_zaragozu";
  $rand = substr(md5(microtime()),rand(0,26),6);
  $sum = 0;

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  
  $sql = "
  INSERT INTO products (product_name, product_description, product_price, product_image, validation_code, product_quantity)
  VALUES ('" .  $_GET['Title'] . "', '" .  $_GET['Description'] . "', '" .  $_GET['Price'] . "', '" .  $_GET['Image'] . "', '" .  $rand . "', " . $sum . ")";

  if ($conn->query($sql) != TRUE) {
    die('could not create post');
  }


  foreach($_GET as $key => $value) {
    if (strpos($key, 'warehouseCheckbox') !== false) {
      $pieces = explode("-", $key);
      $warehouseId = end($pieces);
      $getString = 'warehouseQuantity-id-' . $warehouseId;
      $quantity = $_GET[$getString];
      $sum += $quantity;
      $sql = "
      INSERT INTO product_warehouse (product_id, warehouse_id, quantity_stored)
      VALUES (
        (SELECT id FROM products WHERE product_name LIKE '" .  $_GET['Title'] . "' AND product_description LIKE '" .  $_GET['Description'] . "'),
        (" . $warehouseId . "),
        " . $quantity . "
      )";
      if ($conn->query($sql) != TRUE) {
        die('could not insert M:M');
      }
    }
  }

  $sql = "
    UPDATE products
    SET product_quantity = " . $sum . "
    WHERE product_name LIKE '" .  $_GET['Title'] . "' AND product_description LIKE '" .  $_GET['Description'] . "'
  ";


  if ($conn->query($sql) != TRUE) {
    die('could not update');
  }

  $conn->close();

  header("location:javascript://history.go(-1)");
?>
