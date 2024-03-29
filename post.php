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

  // sql to display all warehouses for storing products
  $sql = "SELECT * FROM warehouses";
  // run the query
  $result = $conn->query($sql);

  // function to display the warehouse HTML content
    // concatenates the HTML string to output
  function displayWarehouse($id, $street_address, $city, $state, $zip) {
    echo
      "<div class = 'warehouse-container'>
        <div class = 'warehouse-address'>" . $street_address . "</div>,
        <div class = 'warehouse-city'>" . $city . "</div>,
        <div class = 'warehouse-state'>" . $state . "</div>,
        <div class = 'warehouse-zip-code'>" . $zip . "</div>
        <input name='warehouseCheckbox-id-" . $id . "' type='checkbox' class='product-warehouse-checkbox'>
        <div class='hidden warehouse-quantity-faux'>
          <p>Quantity:</p>
          <input name='warehouseQuantity-id-" . $id . "' type='number' class='warehouse-product-quantityInput'>
        </div>
      </div>
      <br>";
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

      <div class='main'>
        <form class='newPost' action="post_db.php">
          <h1> Create a new Post</h1>
          <br>
          Image (link):
          <br>
          <input type="text" name="Image">
          <br>
          <br>
          Title :
          <br>
          <input type="text" name="Title">
          <br><br>
          Description :
          <br>
          <input type = "text" name = "Description">
          <br><br>
          Price :
          <br>
          <input type="number" step="0.01" name = "Price">
          <br><br><br>

          Warehouse Locations:
          <br>
          <br>
          <label class="container">
            <div class = "all-warehouses">

              <?php
                // ensure query returned results
                if ($result->num_rows > 0) {
                    // iterate through all warehouse rows from query
                    while($row = $result->fetch_assoc()) {
                      // call method to display warehouses specified data
                      displayWarehouse($row["id"], $row["street_address"], $row["city"], $row["state"], $row["zip"]);
                    }
                } else {
                    echo "0 results";
                }
              ?>

            </div>
          </label>

          <br>
          <a href="warehouse.html">
            <input type='button' value="Add Warehouses" class='submitButton'>
          </a>
          <br><br>

          <div class='totalProductQuantityDisplay'>
            Total Product Quantity: <h3 class='totalProductQuantity'>0</h3>
          </div>
          <br><br>
          <input type='submit' value="Create Post" class='submitButton createPostSubmit'>
          <br><br>

        </form>
      </div>


    </body>
</html>

<?php
// close the db connection
$conn->close();
?>
