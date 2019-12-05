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

  // sql to select all products with a quantity > 0
  $sql = "SELECT * FROM products WHERE product_quantity > 0";
  // run the query
  $result = $conn->query($sql);

  // function to display the fraud posts HTML content, specific to each product's info
    // concatenates the HTML string to output
  function displayFraudPost($id, $img, $heading, $description, $quantity, $price, $validationCode) {
    echo  "<div class='fraud-post product-id-" . $id . "'>
              <img src='" . $img . "' class='fraud-post-img fraud-post-element'>
              <div class='fraud-post-text-button-container'>
                <div class='fraud-post-text fraud-post-element'>
                  <div class='fraud-post-header'>
                    <h2 class='fraud-title'>" . $heading . "</h2>

                    <div class='edit-fraud-post-btn se-" . $validationCode . "-cret'><p class='editButtonText'>edit/delete</p></div>

                  </div>
                  <p class='fraud-description'>" . $description . "</p>
                  <div class='fraud-numbers'>
                    <h3 class='fraud-quantity'>Quanity: " .$quantity . "</h3>
                    <h3 class='fraud-price'>Price: $" . $price . "</h3>
                  </div>
                </div>
                <div class='orderButton'><p class='orderButtonText'>Order Now!</p></div>
              </div>
            </div>";
  }
?>
<html>
    <head>
        <meta charset = "utf-8">
        <title>FraudBay - Home</title>
        <link rel="stylesheet" href="style.css" media="screen">
        <script src="index.js" charset="utf-8" defer></script>
    </head>

    <body>
      <header>
        <h1 class='site-title'>FraudBay</h1>
        <input type="text" class="header-search-input" placeholder="Search for post...">
        <h3 class='site-moto'>where frauders play</h3>
      </header>

      <div class='fraud-post-container'>

        <?php
        // ensure that product query returned results
          if ($result->num_rows > 0) {
              // iterate through all the query results
              while($row = $result->fetch_assoc()) {
                // call method to display fraud posts with specified data
                displayFraudPost($row["id"], $row["product_image"], $row["product_name"], $row["product_description"], $row["product_quantity"], $row["product_price"], $row["validation_code"]);
              }
          } else {
              echo "0 results";
          }
         ?>

      </div>

      <div class='editModal hidden'>
        <div class='modalHeader'>Enter Post Validation Code</div>
        <div class='modalBody'>
          <h3>Validation Code:<h3>
          <input type="text" class="validationInput" placeholder="Enter validation code...">
        </div>
        <div class='modalFooter'>
          <div class='validateButton'>Validate</div>
          <div class='cancelButton'>Cancel</div>
        </div>
      </div>

      <a href="post.php">
        <button type="button" id="sell-something-button">
            <p>+</p>
        </button>
      </a>

    </body>
</html>

<?php
// close the db connection
$conn->close();
?>
