<!DOCTYPE html>

<?php
  // DB connection credentials
  $servername = "classmysql.engr.oregonstate.edu";
  $username = "cs340_zaragozu";
  $password = "3243";
  $dbname = "cs340_zaragozu";
  // the random string
  $rand = substr(md5(microtime()),rand(0,26),6);
  $sum = 0;

  // establish connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // test connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // query to insert into the products table using info from form in post.php
  $sql = "
  INSERT INTO products (product_name, product_description, product_price, product_image, validation_code, product_quantity)
  VALUES ('" .  $_GET['Title'] . "', '" .  $_GET['Description'] . "', '" .  $_GET['Price'] . "', '" .  $_GET['Image'] . "', '" .  $rand . "', " . $sum . ")";

  // run the query and do a fail-safe check
  if ($conn->query($sql) != TRUE) {
    // die if the post could not be created
    echo "sql: " . $sql;
    die('   ||||  error: could not create post');
  } else {
    // obtain the newly inserted product ID
    $newProductId = $conn->insert_id;
  }

  // iterate through the command line arguments
  foreach($_GET as $key => $value) {
    // only interested in the args of warehouses
    if (strpos($key, 'warehouseCheckbox') !== false) {
      // obtain the warehouse id
      $pieces = explode("-", $key);
      $warehouseId = end($pieces);
      // obtain the quantity that will be stored in this warehouse
      $getString = 'warehouseQuantity-id-' . $warehouseId;
      $quantity = $_GET[$getString];
      // accumulate the sum
      $sum += $quantity;
      // sql to insert into M:M product_warehouse table
      $sql = "
      INSERT INTO product_warehouse (product_id, warehouse_id, quantity_stored)
      VALUES (
        " . $newProductId . ",
        " . $warehouseId . ",
        " . $quantity . "
      )";
      if ($conn->query($sql) != TRUE) {
        // die if the post could not be created
        echo "sql: " . $sql;
        die('   ||||  error: could not insert into M:M relationship');
      }
    }
  }

  // sql to update the product quantity with the total sum of products over all warehouses
  $sql = "
    UPDATE products
    SET product_quantity = " . $sum . "
    WHERE id = " . $newProductId;

  // run query and ensure it was able to execute
  if ($conn->query($sql) != TRUE) {
    die('could not update the product quantity');
  }

?>

<html>
    <head>
        <meta charset = "utf-8">
        <title>FraudBay - Post</title>
        <link rel="stylesheet" href="style.css" media="screen">
        <script src="index.js" charset="utf-8" defer></script>
    </head>

    <body>
      <header>
        <a href="index.php">
          <h1 class='site-title'>FraudBay</h1>
        </a>
        <h3 class='site-moto site-title'>where frauders play</h3>
      </header>

      <div class='codeModal'>
        <div class='codeModalHeader'>Save Your Validation Code!!!</div>
        <div class='codeModalBody'>
          <h4 class='codeModalBodyText'>Validation Code:</h4>
          <h3 class='codeModalBodyCode'><?php echo $rand ?></h3>
        </div>
        <div class='codeModalFooter'>
          <p class='codeModalDescription'>This validation code allows you to edite/delete your post!</p>
        </div>
        <a href='index.php'>
          <div class='codeModalContinueButton'>Continue</div>
        </a>
      </div>

    </body>
</html>

<?php
// close the db connection
$conn->close();
?>
