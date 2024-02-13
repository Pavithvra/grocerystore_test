<?php

if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   

   <div class="flex">

      <a href="home.php" class="logo">
         Grocery Hub 
         <img class="shopping-cart-img" src="images/green-shopping-cart-icon-5.png" width="30px">
      </a>

      <nav class="navbar">
         <a href="home.php">HOME</a>
         <a href="shop.php">SHOP</a>
         <a href="orders.php">ORDERS</a>
         <a href="about.php">ABOUT</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <?php
            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
         ?>

         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <div class="profile">
         <?php
            if(!isset($_SESSION['user_id'])){
               // write html code
               echo '<a href="login.php" class="btn">login</a>';
               echo '<a href="register.php" class="btn">register</a>';
            } else {
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            echo '<p>'.$fetch_profile['name'].'</p>';
            echo ' <a href="user_profile_update.php" class="btn">update profile</a>';
            echo ' <a href="logout.php" class="delete-btn">logout</a>';

            
         
            
            }
         ?>
      </div>

   </div>

</header>