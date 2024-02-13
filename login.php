<?php

@include 'config.php';

session_start();

if(isset($_POST['submit']))
{

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$email, $pass]);
   $rowCount = $stmt->rowCount();  

   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($rowCount > 0)
   {
      if($row['user_type'] == 'admin')
      {
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');

      }
      
      elseif($row['user_type'] == 'user')
      {
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }
      
      else
      {
         $message[] = 'User not found!';
      }

   }
   
   else
   {
      $message[] = 'Incorrect E-mail or Password!';
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
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <title>Login</title>
    </head>

    <body>
        <section class="form-container">

            <form action="" method="POST">
               <h3>Login</h3>
               <input type="email" name="email" class="box" placeholder="Enter your E-mail" required>
               <input type="password" name="pass" class="box" placeholder="Enter your Password" required>
               <input type="submit" value="Login" class="btn" name="submit">
               
               <p>Don't have an account? <a href="register.php">Register Now</a></p>
            </form>
    
        </section>
    </body>
</html>