<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_category'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;


   $select_cat = $conn->prepare("SELECT * FROM `category` WHERE name = ?");
   $select_cat->execute([$name]);

   if($select_cat->rowCount() > 0){
      $message[] = 'category already exist!';
   }else{

      $insert_cat = $conn->prepare("INSERT INTO `category`(name, image) VALUES(?,?)");
      $insert_cat->execute([$name, $image]);

      if($insert_cat){
         if($image_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'new category added!';
         }

      }

   }


};



if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `category` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_p = $conn->prepare("DELETE p FROM `products` p INNER JOIN `category` c ON p.category = c.name WHERE c.id = ?");
   $delete_p->execute([$delete_id]);
   $delete_products = $conn->prepare("DELETE FROM `category` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   
   header('location:admin_category.php');

}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body class="category">
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Create new Category</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="enter category name">
         </div>
         <div class="inputBox">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <input type="submit" class="btn" value="add category" name="add_category">
   </form>

</section>


<section class="show-products">

   <h1 class="title">Categories Added</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `category`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="flex-btn">
         <a href="admin_category.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No products added yet!</p>';
   }
   ?>

   </div>

</section>










<script src="js/script.js"></script>

</body>
</html>