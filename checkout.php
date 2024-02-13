<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'order placed already!';
   }else{
      // randomly select delivery partner and change status to 1
      $select_delivery = $conn->prepare("SELECT * FROM `delivery` WHERE status = 0 ORDER BY RAND() LIMIT 1");
      $select_delivery->execute();
      $fetch_delivery = $select_delivery->fetch(PDO::FETCH_ASSOC);
      $delivery_id = $fetch_delivery['id'];
      $update_delivery = $conn->prepare("UPDATE `delivery` SET status = 1 WHERE id = ?");
      $update_delivery->execute([$delivery_id]);
      // get name of delivery partner of that id
      $select_delivery_name = $conn->prepare("SELECT * FROM `delivery` WHERE id = ?");
      $select_delivery_name->execute([$delivery_id]);
      $fetch_delivery_name = $select_delivery_name->fetch(PDO::FETCH_ASSOC);
      $delivery_name = $fetch_delivery_name['del_name'];
      $delivery_ph = $fetch_delivery_name['phNo'];
      // place order if delivery partner is available else print no delivery partners available message
      if($delivery_name == ''){
         $message[] = 'No delivery partners are available currently! Please wait!';
      }else {
         $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on, del_name, del_ph) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
         $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on, $delivery_name, $delivery_ph]);
         $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
         $delete_cart->execute([$user_id]);
         $message[] = 'order placed successfully!';}
   };

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= '₹'.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>₹<?= $cart_grand_total; ?>/-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>Place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Name :</span>
            <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>Phone Number :</span>
            <input type="text" name="number" value="<?= $fetch_profile['phone']; ?>" placeholder="Enter your phone number" class="box" required>
         </div>
         <div class="inputBox">
            <span>E-mail :</span>
            <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Enter your e-mail" class="box" required>
         </div>
         <div class="inputBox">
            <span>Payment Method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
               <!-- <option value="paytm">paytm</option>
               <option value="paypal">paypal</option> -->
            </select>
         </div>
         <div class="inputBox">
            <span>Address Line 1 :</span>
            <input type="text" name="flat" value="<?= $fetch_profile['addr1']; ?>" placeholder="e.g. Flat Number" class="box" required>
         </div>
         <div class="inputBox">
            <span>Address Line 2 :</span>
            <input type="text" name="street" value="<?= $fetch_profile['addr2']; ?>" placeholder="e.g. Street Name" class="box" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="city" value="<?= $fetch_profile['city']; ?>" placeholder="e.g. Mumbai" class="box" required>
         </div>
         <div class="inputBox">
            <span>State :</span>
            <input type="text" name="state" value="<?= $fetch_profile['state']; ?>" placeholder="e.g. Maharashtra" class="box" required>
         </div>
         <div class="inputBox">
            <span>Pincode :</span>
            <input type="text" min="0" name="pin_code" value="<?= $fetch_profile['pincode']; ?>" placeholder="e.g. 123456" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="Place Order">

   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>