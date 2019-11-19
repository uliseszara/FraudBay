<!DOCTYPE html>
<?php
$servername = "classmysql.engr.oregonstate.edu";
$username = "cs340_zaragozu";
$password = "3243";
$dbname = "cs340_zaragozu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM products WHERE id = " . $_GET['productId'];
$result = $conn->query($sql);
$row = $result->fetch_assoc();
 ?>

<html>
    <head>
        <meta charset = "utf-8">
        <title>FraudBay - Order</title>
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

      <div class='main'>
        <div class='fraud-post-container-order'>
          <div class='fraud-post-order'>
            <img src='<?php echo $row['product_image'] ?>' class='fraud-post-img fraud-post-element'>
            <div class='fraud-post-text-button-container-order'>
              <div class='fraud-post-text-order fraud-post-element-order'>
                <h2 class='fraud-title-order'><?php echo $row['product_name'] ?></h2>
                <p class='fraud-description-order'><?php echo $row['product_description'] ?></p>
                <div class='fraud-numbers'>
                  <h3 class='fraud-quantity'>Quanity: <?php echo $row['product_quantity'] ?></h3>
                  <h3 class='fraud-price'>Price: $<?php echo $row['product_price'] ?></h3>
                </div>
                <h2 class='orderingText'>Currently Ordering - Fill form below & Submit</h2>
              </div>
            </div>
          </div>
        </div>
        <form class='orderFormTotal' action="review.html">
          <div class='customerForm'>
            <h2>Customer Form</h2>
            <br><br>
            First Name :
            <br>
            <input type="text" name="firstname">
            <br>
            Last Name :
            <br>
            <input type = "text" name = "password">
            <br>
            Street Address :
            <br>
            <input type = "text" name = "street_address">
            <br>
            City :
            <br>
            <input type = "text" name = "city">
            <br>
            State :
            <br>
            <input type = "text" name = "state">
            <br>
            Zip Code :
            <br>
            <input type = "text" name = "zip_code">
            <br><br>
          </div>

          <div class='orderForm'>
            <h2>Order Form</h2>
            <br><br>
            Desired Quantity :
            <br>
            <input type="number" name="quantity">
            <br><br>
            <input type="submit" value="Submit" class='submitButton'>
          </div>
        </form>


      </div>
    </body>
</html>

<?php
$conn->close();
?>
