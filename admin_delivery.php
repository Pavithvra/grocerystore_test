<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_delivery'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   $phno = $_POST['phno'];
   $phno = filter_var($phno, FILTER_SANITIZE_STRING);

//    $image = $_FILES['image']['name'];
//    $image = filter_var($image, FILTER_SANITIZE_STRING);
//    $image_size = $_FILES['image']['size'];
//    $image_tmp_name = $_FILES['image']['tmp_name'];
//    $image_folder = 'uploaded_img/'.$image;


   $select_del = $conn->prepare("SELECT * FROM `delivery` WHERE phno = ?");
   $select_del->execute([$phno]);

   if($select_del->rowCount() > 0){
      $message[] = 'Delivery partner already exist with this number!';
   }else{

      $insert_del = $conn->prepare("INSERT INTO `delivery`(name, phno, status) VALUES(?,?, 0)");
      $insert_del->execute([$name, $phno]);

      if($insert_del){

        $message[] = 'Delivery partner added!';
        //  }else{
        //     move_uploaded_file($image_tmp_name, $image_folder);
        //     $message[] = 'new category added!';
        //  }

      }

   }

};

if(isset($_GET['delete'])){

   /*$delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `category` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_p = $conn->prepare("DELETE p FROM `products` p INNER JOIN `category` c ON p.category = c.name WHERE c.id = ?");
   $delete_p->execute([$delete_id]);
   $delete_products = $conn->prepare("DELETE FROM `category` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);*/

   $delete_id = $_GET['delete'];
   $delete_del = $conn->prepare("DELETE FROM `delivery` WHERE id = ?");
   $delete_del->execute([$delete_id]);
   
   header('location:admin_delivery.php');

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Delivery partner</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Add new delivery partner</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="name" class="box" required placeholder="enter name">
         </div>

         <div class="inputBox">
         <input type="text" name="phno" class="box" required placeholder="enter phone number">
         </div>
         <!-- <div class="inputBox">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div> -->
      </div>
      <input type="submit" class="btn" value="add delivery partner" name="add_delivery">
   </form>

</section>


<section class="show-products">

   <h1 class="title">Delivery Partners</h1>

   <div class="box-container">

   <?php
      $show_del = $conn->prepare("SELECT * FROM `delivery`");
      $show_del->execute();
      if($show_del->rowCount() > 0){
         while($fetch_del = $show_del->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="del_id"><?= $fetch_del['id']; ?></div>
      <div class="del_name"><?= $fetch_del['del_name']; ?></div>
      <div class="del_phone"><?= $fetch_del['phNo']; ?></div>

      <div class="flex-btn">
         <a href="admin_delivery.php?delete=<?= $fetch_del['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No delivery partner added yet!</p>';
   }
   ?>

   </div>

</section>










<script src="js/script.js"></script>

</body>
</html>