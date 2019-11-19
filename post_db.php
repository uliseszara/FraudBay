<!DOCTYPE html>

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
$conn->close();
?>
