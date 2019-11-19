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

  $sql = "SELECT * FROM products";
  $result = $conn->query($sql);


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
          if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
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
$conn->close();
?>
