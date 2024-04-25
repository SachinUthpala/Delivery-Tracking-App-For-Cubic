<?php
require_once './Db/Db.conn.php';
session_start();

?>

<!DOCTYPE html>
<!-- Created By CodingLab - www.codinglabweb.com -->
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Login Form | CodingLab</title> 
    <link rel="stylesheet" href="./Asserts/CSS/Style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
  </head>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><span>Cubic Login Form</span></div>
        <form action="./Db/configs/login.php" method="post">
          <div class="row">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Enter Email" name="email" required>
          </div>
          <div class="row">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Enter Password" name="password" required>
          </div>
          <!-- <div class="pass"><a href="#">Forgot password?</a></div> -->
          <div class="row button">
            <input type="submit" value="Login" name="login">
          </div>
        </form>
      </div>
    </div>

  </body>
</html>