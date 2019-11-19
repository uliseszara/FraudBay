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
        <meta charset="utf-8">
        <title>FraudBay - Edit</title>
        <link rel="stylesheet" href="style.css" media="screen">
        <script src="index.js" charset="utf-8" defer></script>
    </head>
    <body>
      <header>
        <a href="index.php">
          <h1 class='site-title'>FraudBay</h1>
        </a>
        <h3 class="site-moto site-title">where frauders play</h3>
      </header>

      <a href='index.html'><div class='delete-post-button'><p>Delete Post</p></div></a>


      <p class='originalText'>Original</p>
      <p class='newText'>New</p>
      <div class='editForm'>
        <div class='formElement editTitle'>
          <p class='editHeader'>Post Title:</p>
          <div class='originalTitle'>
            <?php echo $row['product_name'] ?>
          </div>
          <input type="text" class="newTitleInput" placeholder="Enter New Title...">
        </div>

        <div class='formElement editDescription'>
          <p class='editHeader'>Post Description:</p>
          <div class='originalDescription'>
            <?php echo $row['product_description'] ?>
          </div>
          <input type="text" class="newDescriptionInput" placeholder="Enter New Description...">
        </div>

        <div class='formElement editPrice'>
          <p class='editHeader'>Post Price:</p>
          <div class='originalPrice'>
            <?php echo $row['product_price'] ?>
          </div>
          <input type="number" class="newPriceInput" placeholder="Enter New Price...">
        </div>

        <div class='formElement editQuantity'>
          <p class='editHeader'>Post Quantity:</p>
          <div class='originalQuantity'>
            <?php echo $row['product_quantity'] ?>
          </div>
          <input type="number" class="newQuantityInput" placeholder="Enter New Quantity...">
        </div>

        <div class='formElement editImage'>
          <p class='editHeader'>Post Image:</p>
          <div class='ogImg'><img src='<?php echo $row['product_image'] ?>' class='fraud-post-img'></div>
          <input type="submit" value="New Image" class='imgEditUpload'>
        </div>
      </div>

      <a href='index.html'><div class='save-edits-button'><p>Save Edits</p></div></a>

    </body>
</html>
