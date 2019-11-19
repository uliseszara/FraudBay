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

  $sql = "SELECT * FROM warehouses";
  $result = $conn->query($sql);


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
      </div>";
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
          <label class="container">
            <div class = "all-warehouses">

              <?php
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                      displayWarehouse($row["id"], $row["street_address"], $row["city"], $row["state"], $row["zip"]);
                    }
                } else {
                    echo "0 results";
                }
              ?>

            </div>
          </label>
          <br>
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

      <div class='codeModal hidden'>
        <div class='codeModalHeader'>Save Your Validation Code!!!</div>
        <div class='codeModalBody'>
          <h4 class='codeModalBodyText'>Validation Code:</h4>
          <h3 class='codeModalBodyCode'>1234x_1</h3>
        </div>
        <div class='codeModalFooter'>
          <p class='codeModalDescription'>This validation code allows you to edite/delete your post!</p>
        </div>
        <div class='codeModalContinueButton'>Continue</div>
      </div>

    </body>
</html>

<?php
$conn->close();
?>
