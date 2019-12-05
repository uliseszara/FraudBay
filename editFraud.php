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

// query to obtain the product entry that is to be edited from URL args
$sql = "SELECT * FROM products WHERE id = " . $_GET['productId'];

// run the query and obtain results
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// $row variable contains DB attribute fields and will be output below in <?php> blocks
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

      <!-- Build URL for deleting case, and pass ProductId and delete field as args in URL -->
      <a href="editFraud_db.php?productId=<?php echo $_GET['productId'] ?>&delete=TRUE"<div class='delete-post-button'><p>Delete Post</p></div></a>


      <p class='originalText'>Original</p>
      <p class='newText'>New</p>
      <form class='editForm' action='editFraud_db.php'>
        <div class='formElement editTitle'>
          <p class='editHeader'>Post Title:</p>
          <div class='originalTitle'>
            <?php echo $row['product_name'] ?>
          </div>
          <input type="text" class="newTitleInput" name='newName' placeholder="Enter New Title...">
        </div>

        <div class='formElement editDescription'>
          <p class='editHeader'>Post Description:</p>
          <div class='originalDescription'>
            <?php echo $row['product_description'] ?>
          </div>
          <input type="text" class="newDescriptionInput" name='newDescription' placeholder="Enter New Description...">
        </div>

        <div class='formElement editPrice'>
          <p class='editHeader'>Post Price:</p>
          <div class='originalPrice'>
            <?php echo $row['product_price'] ?>
          </div>
          <input type="number" step='0.01' class="newPriceInput" name='newPrice' placeholder="Enter New Price...">
          <input type="text" name='productId' class='hidden' value='<?php echo $_GET['productId'] ?>'>
          <input type="text" name='delete' class='hidden' value='FALSE'>
        </div>


        <div class='formElement editImage'>
          <p class='editHeader'>Post Image:</p>
          <div class='ogImg'><img src='<?php echo $row['product_image'] ?>' class='fraud-post-img'></div>
          <input type="text" class="newImageInput" name='newImage' placeholder="Enter New Image URL...">
        </div>

        <!-- Submit form to editFraud_db.php file with changes to product post -->
        <input type='submit' value='Save Edits' class='save-edits-button'>
      </form>


    </body>
</html>
<?php
// close the db connection
$conn->close();
?>
