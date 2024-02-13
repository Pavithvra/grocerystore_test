<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body class="admin">
   
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="box-container">

      <div class="box">
      <?php
         $total_pendings = 0;
         $order_status = $conn->prepare("SELECT * FROM `orders`");
         $order_status->execute();
         while($fetch_pendings = $order_status->fetch(PDO::FETCH_ASSOC)){
            $total_pendings += $fetch_pendings['total_price'];
         };
      ?>
      <h3>₹<?= $total_pendings; ?>/-</h3>
      <p>Total Orders</p>
      <a href="admin_orders.php" class="btn">See Orders</a>
      </div>

      <!--  -->

      <div class="box">
      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
      ?>
      <h3><?= $number_of_orders; ?></h3>
      <p>Orders Placed</p>
      <a href="admin_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $number_of_products = $select_products->rowCount();
      ?>
      <h3><?= $number_of_products; ?></h3>
      <p>Add Products</p>
      <a href="admin_products.php" class="btn">Add Products</a>
      </div>

      <div class="box">
      <?php
         $select_cat = $conn->prepare("SELECT * FROM `category`");
         $select_cat->execute();
         $number_of_categories = $select_cat->rowCount();
      ?>
      <h3><?= $number_of_categories; ?></h3>
      <p>Add Categories</p>
      <a href="admin_category.php" class="btn">Add Categories</a>
      </div>

      <div class="box">
         <?php
            $select_del = $conn->prepare("SELECT * FROM `delivery`");
            $select_del->execute();
            $number_of_del = $select_del->rowCount();
         ?>
         <h3><?= $number_of_del; ?></h3>
         <p>Add Delivery</p>
         <a href="admin_delivery.php" class="btn">Add Delivery</a>
      </div>

      <div class="box">
         <?php
            // show assigned delivery
            $select_assigned = $conn->prepare("SELECT * FROM `delivery` WHERE status = ?");
            // check if status is 0 or 1
            $select_assigned->execute(['1']);
            $number_of_assigned = $select_assigned->rowCount();
         ?>
         <h3><?= $number_of_assigned; ?></h3>
         <p>Assigned Delivery</p>
         <a href="admin_delivery.php" class="btn">See Delivery</a>
      </div>

      

   </div>

</section>


<script src="js/script.js"></script>

</body>
</html>