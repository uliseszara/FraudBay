<?php
// DB connection credentials
$servername = "classmysql.engr.oregonstate.edu";
$username = "cs340_zaragozu";
$password = "3243";
$dbname = "cs340_zaragozu";
$url = 'index.php';

// establish connection
$conn = new mysqli($servername, $username, $password, $dbname);

// test connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if($_GET['delete'] == 'TRUE') {
  // delete a product entry

  // NULL the orders-product relationship
  $sql = 'UPDATE orders SET product_id = NULL WHERE product_id = ' . $_GET['productId'];
  if ($conn->query($sql) != TRUE) {
      echo $sql;
      die(" ||| could not null from product_order relationship");
  }

  // delete from M:M relationship table product_warehouse
  $sql = 'DELETE FROM product_warehouse WHERE product_id = ' . $_GET['productId'];
  if ($conn->query($sql) != TRUE) {
      echo $sql;
      die(" ||| could not delete from product_warehouse");
  }

  // delete from products table
  $sql = 'DELETE FROM products WHERE id = ' . $_GET['productId'];
  // run the query
  if ($conn->query($sql) != TRUE) {
    echo $sql;
    die(" ||| could not delete from products");
  }
} else {
  // edit a product entry

  // iterate through all command line arguments
  foreach($_GET as $key => $value)
  {
    // build the sql string to execute
    $sql = "UPDATE products SET ";

    // if the command line arg is a product field to be updated
    if (strpos($key, 'new') !== false) {
      switch ($key) {
        // updating the product name
        case 'newName':
          // if the user chose to update this field
          if($value) {
            // build the sql string to update and execute the string
            $sql .= "product_name = '" . $_GET['newName'] . "' WHERE id = " . $_GET['productId'];
            if ($conn->query($sql) != TRUE) {
                die("could not update");
            }
          }
          break;
        // updating the product description
        case 'newDescription':
          // if the user chose to update this field
          if($value) {
            // build the sql string to update and execute the string
            $sql .= "product_description = '" . $_GET['newDescription'] . "' WHERE id = " . $_GET['productId'];
            if ($conn->query($sql) != TRUE) {
                die("could not update");
            }
          }
          break;
        // updating the product price
        case 'newPrice':
          // if the user chose to update this field
          if($value) {
            // build the sql string to update and execute the string
            $sql .= "product_price = " . $_GET['newPrice'] . " WHERE id = " . $_GET['productId'];
            if ($conn->query($sql) != TRUE) {
                die("could not update");
            }
          }
          break;
        // updating the product image URL
        case 'newImage':
          // if the user chose to update this field
          if($value) {
            // build the sql string to update and execute the string
            $sql .= "product_image = '" . $_GET['newImage'] . "' WHERE id = " . $_GET['productId'];
            if ($conn->query($sql) != TRUE) {
                die("could not update");
            }
          }
          break;
      }
    }
  }
}

// close the DB connection and redirect back to homepage to see new changes
$conn->close();
header( "Location: index.php" );
?>
