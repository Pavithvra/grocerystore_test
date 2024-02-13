<?php

include 'config.php';

if(isset($_POST['submit']))
{

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0)
   {
      $message[] = 'E-mail already registered!';
   }
   
   else
   {
      if($pass != $cpass)
      {
         $message[] = 'Password does not match!';
      }
      
      else
      {
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert->execute([$name, $email, $pass]);

         if($insert)
         {
            $message[] = 'Account created successfully!';
            header('location:login.php');
         }
      }
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

        <title>Register</title>
    </head>

    <body>
        <section class="form-container">
            <form action="" enctype="multipart/form-data" method="POST">
                <h3>Create an Account</h3>

                <input type="text" name="name" class="box" placeholder="Enter your full name" required>
                <input type="email" name="email" class="box" placeholder="Enter your E-mail" required>
                <input type="password" name="pass" class="box" placeholder="Enter your password" required>
                <input type="password" name="cpass" class="box" placeholder="Confirm your password" required>
                <input type="submit" value="Create account" class="btn" name="submit"> <br>
                    
                <p>Already have an account? <a href="login.php">Login Now</a></p>
            </form>
        </section>
    </body>
</html>