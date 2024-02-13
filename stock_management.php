<?php

// Connect to the database
@include 'config.php';

session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {
  // Get the product name and stock level from the form
  $product_name = mysqli_real_escape_string($db, $_POST['product_name']);
  $stock_level = mysqli_real_escape_string($db, $_POST['stock_level']);
  
  // Check if the stock level is below the reorder level
  $reorder_level = 10; // Set the reorder level to 10
  if ($stock_level < $reorder_level) {
    // Send an email notification
    mail("restaurant_manager@example.com", "Reorder Alert", "The stock level for product $product_name is low. Please reorder.");
  }
  
  // Update the stock level in the database
  $sql = "UPDATE products SET stock_level='$stock_level' WHERE name='$product_name'";
  mysqli_query($db, $sql);
}

// Retrieve the current stock levels from the database
$sql = "SELECT * FROM products";
$result = mysqli_query($db, $sql);

?>

<!-- Display the stock management form -->
<form method="post" action="stock_management.php">
  <label for="product_name">Product:</label><br>
  <select name="product_name">
    <?php while ($row = mysqli_fetch_array($result)) { ?>
      <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
    <?php } ?>
  </select><br>
  <label for="stock_level">Stock Level:</label><br>
  <input type="text" name="stock_level" placeholder="Enter stock level"><br><br>
  <input type="submit" name="submit" value="Update">
</form>
