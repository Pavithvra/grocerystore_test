<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['update_profile'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
   $update_profile->execute([$name, $email, $user_id]);


   $old_pass = $_POST['old_pass'];
   $update_pass = md5($_POST['update_pass']);
   $update_pass = filter_var($update_pass, FILTER_SANITIZE_STRING);
   $new_pass = md5($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = md5($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if(!empty($update_pass) AND !empty($new_pass) AND !empty($confirm_pass)){
      if($update_pass != $old_pass){
         $message[] = 'Old password does not match!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'Confirm password does not match!';
      }else{
         $update_pass_query = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass_query->execute([$confirm_pass, $user_id]);
         $message[] = 'Password updated successfully!';
      }
   }

   $address1 = $_POST['addr1'];
   $address1 = filter_var($address1, FILTER_SANITIZE_STRING);
   $address2 = $_POST['addr2'];
   $address2 = filter_var($address2, FILTER_SANITIZE_STRING);
   $city = $_POST['city'];
   $city = filter_var($city, FILTER_SANITIZE_STRING);
   $state = $_POST['state'];
   $state = filter_var($state, FILTER_SANITIZE_STRING);
   $pincode = $_POST['pin_code'];
   $pincode = filter_var($pincode, FILTER_SANITIZE_STRING);
   $phone = $_POST['phone'];
   $phone = filter_var($phone, FILTER_SANITIZE_STRING);

   if(!empty($address1) OR !empty($address2) OR !empty($city) OR !empty($state) OR !empty($pincode)){
      $update_addr = $conn->prepare("UPDATE `users` set addr1 = ?, addr2 = ?, city = ?, state = ?, pincode = ?, phone = ? WHERE id = ?");
      $update_addr->execute([$address1, $address2, $city, $state, $pincode, $phone, $user_id]);
      $message[] = 'Account updated successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="stylesheet" href="css/reg_style.css">
        <link rel="stylesheet" href="css/components.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <title>Update Profile</title>
    </head>

    <body>
    <?php include 'header.php'; ?>
        <section class="form-containerU">
            <form action="" enctype="multipart/form-data" method="POST">
                <h3>Update profile</h3>

                <!-- <span>Full name:</span> -->
               <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" placeholder="Update Username" required class="boxU">
               <!-- <span>E-mail:</span> -->
               <input type="email" name="email" value="<?= $fetch_profile['email']; ?>" placeholder="Update E-mail" required class="boxU">

               <input type="hidden" name="old_pass" value="<?= $fetch_profile['password']; ?>">
               <!-- <span>Old Password:</span> -->
               <input type="password" name="update_pass" placeholder="Enter your old password" class="boxU">
               <!-- <span>New Password:</span> -->
               <input type="password" name="new_pass" placeholder="Enter the new password" class="boxU">
               <!-- <span>Confirm Password:</span> -->
               <input type="password" name="confirm_pass" placeholder="Confirm the new password" class="boxU">
               <p>Address:</p>
               <!-- <span>Address Line 1:</span> -->
               <input type="text" name="addr1" value="<?= $fetch_profile['addr1']; ?>" placeholder="Flat Number" class="boxU">
               <!-- <span>Address Line 2:</span> -->
               <input type="text" name="addr2" value="<?= $fetch_profile['addr2']; ?>" placeholder="Street Name" class="boxU">
               <!-- <span>City:</span> -->
               <input type="text" name="city" value="<?= $fetch_profile['city']; ?>" placeholder="City" class="boxU">
               <!-- <span>State</span> -->
               <input type="text" name="state" value="<?= $fetch_profile['state']; ?>" placeholder="State" class="boxU">
               <!-- <span>Pincode:</span> -->
               <input type="text" name="pin_code" value="<?= $fetch_profile['pincode']; ?>" placeholder="Pincode" class="boxU">
               <!-- <span>Pincode:</span> -->
               <input type="text" name="phone" value="<?= $fetch_profile['phone']; ?>" placeholder="Phone Number" class="boxU">

                <input type="submit" class="btnU" value="Update Profile" name="update_profile"> <br>
                    
            </form>
        </section>
        <?php include 'footer.php'; ?>
        <script src="js/script.js"></script>
    </body>
</html>