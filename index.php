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
     <title>Cubik Login</title> 
    <link rel="stylesheet" href="./Asserts/CSS/Style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>

    <!-- sweet alert start -->
    <!--for sweet alert-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js" ></script>
    <!-- end of sweet alert -->
  </head>
  <body>
    <div class="container">
      <div class="wrapper">
        <div class="title"><span>Cubik Delivery Login</span></div>
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

  <!--for sweet alert-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js" ></script>

    <?php
    
    if($_SESSION['wrong_email'] == 1){
        echo '
                <script>
                Swal.fire({
					icon: "error",
					title: "Oops...",
					text: "Wrong Email !",
				  });
                  </script>
                '
                ;
	    $_SESSION['wrong_email'] = null;
    }else if($_SESSION['wrong_pass'] == 1){
        echo '
                <script>
                Swal.fire({
					icon: "error",
					title: "Oops...",
					text: "Wrong Password !",
				  });
                  </script>
                '
                ;
	    $_SESSION['wrong_pass'] = null;
    }else if($_SESSION['API_NOT_WORKING'] == 1){
      echo '
              <script>
              Swal.fire({
        icon: "error",
        title: "Api Is Not Working...",
        text: "Api Is Not Working. Please Contact Your System Administrator !",
        });
                </script>
              '
              ;
    $_SESSION['API_NOT_WORKING'] = null;
  }
    
    ?>
</html>