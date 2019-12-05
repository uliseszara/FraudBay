<!DOCTYPE html>
<?php
  // DB connection credentials
  $url = 'post.php';
  $servername = "classmysql.engr.oregonstate.edu";
  $username = "cs340_zaragozu";
  $password = "3243";
  $dbname = "cs340_zaragozu";

  //establish vars
  $customerId;
  $orderId;
  $warehouseIdArray = array();
  $rem = $_GET['quantity']; // the remaining amount needing to be shipped
  $finalWarehouseId;
  $finalAmountStored;
  // establish connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // test connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // query to insert new customer into table using form info from order.php
  $sql = "
    INSERT INTO customers (first_name, last_name, street_address, city, state, zip)
    VALUES ('" . $_GET['firstname'] . "', '" . $_GET['lastname'] . "', '" . $_GET['street_address'] . "', '" . $_GET['city'] . "', '" . $_GET['state'] . "', '" . $_GET['zip_code'] . "')
    ";

  // run the query and ensure it could execute
  if (!($conn->query($sql) === TRUE)) {
      die('Could not create customer');
  } else {
    // save the new customer id
    $customerId = $conn->insert_id;
  }

  // get the current date
  $dt1 = new DateTime();
  $dt2 = new DateTime();
  // calculate the shipping arrival date (+2weeks)
  $dt2->add(new DateInterval('P14D'));

  // query to insert into the orders table
  $sql = "
    INSERT INTO orders (order_date, delivery_date, quantity, customer_id, product_id)
    VALUES (DATE '" . $dt1->format('Y-m-d') . "', DATE '" . $dt2->format('Y-m-d') . "', " . $_GET['quantity'] . ", " . $customerId . ", " . $_GET['product_id'] . ")";

  // run the query
  if (!($conn->query($sql) === TRUE)) {
      die('Could not create order');
  } else {
    // save the new order id
    $orderId = $conn->insert_id;
  }


  // query to select all product_warehouses M:M entries that store the productId and have quantity_stored > 0
  $sql = "SELECT * FROM product_warehouse WHERE product_id = " . $_GET['product_id'] . " AND quantity_stored > 0";
  // run the query
  $result = $conn->query($sql);

    /*
     -- General PsuedoCode for code below --
     loop through query results and accumulate the quantity stored until user amount ordered is met
        store the warehouse ids when looping

     for each warehouse id, add to tables and update quantities

     update final product quantity
    */

  // --

  // iterate through query results
  if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        // decrement remaining amnt. needed to be shipped var. by quantity stored by warehouse
        $rem -= $row['quantity_stored'];
        // update vars for final iteration
        $finalWarehouseId = $row['warehouse_id'];
        $finalAmountStored = $row['quantity_stored'];
        // if products still need to be shipped
        if($rem > 0) {
          // create array entry with key as the current warehouse id, and value as the quantity of product it stores
          $temp = array($row['warehouse_id'] => $row['quantity_stored']);
          // add the array entry to array of entries
          $warehouseIdArray += $temp;
        } else {
          // no more products need to be stored, exit loop
          break;
        }
      }
      // math to calculate amount ordered
      $rem *= (-1);
      $quantityOrdered = $finalAmountStored - $rem;
      // create array entry with key as the current warehouse id, and value as the quantity of product it stores
      $temp = array($finalWarehouseId => strval($quantityOrdered));
      // add the array entry to array of entries
      $warehouseIdArray += $temp;
  } else {
    // query had no results
      die("error: 0 results in product_warehouse table");
  }

  // iterate through built array of entries to perform sql inserts to reflect the new order-warehouse M:M relationships for the product ordered
  foreach ($warehouseIdArray as $key => $value) {
    // query to insert to M:M order_warehouse table, the key of the array is the warehouse id and the value is the quantity shipped
    $sql = "
    INSERT INTO order_warehouse (order_id, warehouse_id, quantity_ordered)
    VALUES (" . $orderId . ", " . $key . ", " . $value . ")
    ";
    // run the query and exit if fails
    if (!($conn->query($sql) === TRUE)) {
      die('Could not insert into order_warehouse table');
    }
  }

  // iterate through built array of entries to perform sql updates to reflect product shipping
  foreach ($warehouseIdArray as $key => $value) {
    // query to update product_warehouse quantity by the amount of product it has shipped
    $sql = "UPDATE product_warehouse SET quantity_stored = quantity_stored - " . $value . " WHERE warehouse_id = " . $key . " AND product_id = " . $_GET['product_id'];
    // run the query and exit if failure
    if (!($conn->query($sql) === TRUE)) {
      die('Could not update the product_warehouse table');
    }
  }

  // query to update the products total quantity by the total amount ordered by user
  $sql = "UPDATE products SET product_quantity = product_quantity - " . $_GET['quantity'] . " WHERE id = " . $_GET['product_id'];
  // run the query and exit if fails
  if (!($conn->query($sql) === TRUE)) {
      die('Could not update product table');
  }

  // --

  // query to select order info for the newly created user order
  $sql = "
  SELECT * FROM orders WHERE id = " . $orderId . "
  ";
  // run query and obtain results
  $result = $conn->query($sql);
  $orderRow = $result->fetch_assoc();

  // query to select customer info for the user
  $sql = "
  SELECT * FROM customers WHERE id = " . $customerId . "
  ";
  // run query and obtain results
  $result = $conn->query($sql);
  $customerRow = $result->fetch_assoc();

  // query to select product info
  $sql = "
  SELECT * FROM products WHERE id = " . $_GET['product_id'] . "
  ";
  // run query and obtain results
  $result = $conn->query($sql);
  $productRow = $result->fetch_assoc();

  // query to select the order_warehouse M:M info
  $sql = "
  SELECT * FROM order_warehouse WHERE order_id = " . $orderId . "
  ";
  // run query and obtain results
  $result = $conn->query($sql);

  // function to display the warehouse HTML content
    // concatenates the HTML string to output
  function displayWarehouses($num, $addr, $city, $state, $zip, $quant) {
    echo "
    <h2>Warehouse #" . $num . "</h2>
    <ul class='summaryList'>
      <li>Street Address: <i>'" . $addr . "'</i></li>
      <li>City: <i>'" . $city . "'</i></li>
      <li>State: <i>'" . $state . "'</i></li>
      <li>Zip-Code: <i>'" . $zip . "'</i></li>
      <li>Quantity Shipping: <i>'" . $quant . "'</i></li>
    </ul>
    ";
  }

 ?>
<html>
    <head>
        <meta charset = "utf-8">
        <title>FraudBay - Review</title>
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
      <h2 class='summaryTitle'>Order Summary</h2>
      <div class='orderSummaryDisplay'>
        <h2>Order</h2>
        <ul class='summaryList'>
          <li>Order ID: <i><?php echo $orderId ?></i></li>
          <li>Order Date: <i><?php echo $orderRow['order_date'] ?></i></li>
          <li>Delivery Date: <i><?php echo $orderRow['delivery_date'] ?></i></li>
          <li>Quantity: <i><?php echo $orderRow['quantity'] ?></i></li>
          <li>Product Name: <i>'<?php echo $productRow['product_name'] ?>'</i></li>
        </ul>
        <?php
        // count var to keep track of total warehouses being used to store
        $num = 1;
        // iterate through all the M:M order_warehouse query results
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
              // query to retrieve warehouse info
              $sql = "SELECT * FROM warehouses WHERE id = " . $row['warehouse_id'];
              // run the query and obtain results
              $result2 = $conn->query($sql);
              $warehouseRow = $result2->fetch_assoc();
              // call method to display warehouses with specified data
              displayWarehouses($num, $warehouseRow['street_address'], $warehouseRow['city'], $warehouseRow['state'], $warehouseRow['zip'], $row['quantity_ordered']);
              // increment count
              $num++;
            }
        } else {
            die("error: 0 results in order_warehouse table after insert");
        }
        ?>
        <h2>Customer</h2>
        <ul class='summaryList'>
          <li>Name: <i>'<?php echo $customerRow['first_name'] . " " . $customerRow['last_name'] ?>'</i></li>
          <li>Street Address: <i>'<?php echo $customerRow['street_address'] ?>'</i></li>
          <li>City: <i>'<?php echo $customerRow['city'] ?>'</i></li>
          <li>State: <i>'<?php echo $customerRow['state'] ?>'</i></li>
          <li>Zip-Code: <i>'<?php echo $customerRow['zip'] ?>'</i></li>
        </ul>
        <br>
      </div>
      <a href='index.php'>
        <div class='keepShoppingButton'>
          <h3>Keep Frauding</h3>
        </div>
      </a>
    </body>
</html>
<?php
// close the db connection
$conn->close();
?>
