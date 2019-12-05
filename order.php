<!DOCTYPE html>
<?php
// DB connection credentials
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

// sql to select product info for product being ordered
$sql = "SELECT * FROM products WHERE id = " . $_GET['productId'];
// run the query
$result = $conn->query($sql);
// obtain the query results
$row = $result->fetch_assoc();

// function to display the warehouse information storing the product
  // concatenates the HTML string to output
function displayWarehouses($num, $addr, $city, $state, $zip, $quant) {
  echo "
  <h3>Warehouse #" . $num . "</h3>
  <ul>
    <li>Street Address: <i>'" . $addr . "'</i></li>
    <li>City: <i>'" . $city . "'</i></li>
    <li>State: <i>'" . $state . "'</i></li>
    <li>Zip: <i>'" . $zip . "'</i></li>
    <li>Product Quantity Stored: <i>'" . $quant . "'</i></li>
  </ul>
  ";
}
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
          <div class='fraud-post-order product-id-<?php echo $_GET['productId'] ?>'>
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
        <form class='orderFormTotal' action="review.php">
          <div class='customerForm'>
            <h2>Customer Form</h2>
            <br><br>
            First Name :
            <br>
            <input type="text" name="firstname">
            <br>
            Last Name :
            <br>
            <input type = "text" name = "lastname">
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
            <input type="number" class='orderQuantity' name="quantity">
            <br><br>
            <input type='text' name='product_id' class='hidden product_id_input'>
            <input type="submit" value="Submit" class='submitButton'>
          </div>
        </form>

        <div class='warehousesPartitionContainer'>
          <h2>Product-Warehouse Storage Info</h2>

          <?php
            // count var to keep track of total warehouses being used to store
            $num = 1;
            // sql to select all the warehouse id's storing the specific product
            $sql = "SELECT * FROM product_warehouse WHERE product_id = " . $_GET['productId'] . " AND quantity_stored > 0";
            // run the query
            $result = $conn->query($sql);
            // ensure there are results
            if ($result->num_rows > 0) {
              // iterate through all warehouses from query results
              while($row = $result->fetch_assoc()) {
                // query to retrieve warehouse info from warehouse id
                $sql = "SELECT * FROM warehouses WHERE id = " . $row['warehouse_id'];
                // run the query
                $result2 = $conn->query($sql);
                // retrieve the query results
                $row2 = $result2->fetch_assoc();
                // call method to display warehouses with specified data
                displayWarehouses($num, $row2['street_address'], $row2['city'], $row2['state'], $row2['zip'], $row['quantity_stored']);
                // increment count
                $num++;
              }
            }
            else {
                echo "0 results in product_warehouse";
            }
          ?>
          <br>
        </div>


      </div>
    </body>
</html>

<?php
// close the db connection
$conn->close();
?>
