<?php

require_once '../Db.conn.php'; //getting database connection
session_start();

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(!$email || !$password){
        $_SESSION['credition_null'] = 1;
        header("Location: ../../index.php");
    }

    //chek email exsist
    $sql_email = "SELECT * FROM Users WHERE enail = :usermail";

    try {
        $result = $conn->prepare($sql_email);
        // Bind the value of userMail to the placeholder
        $result->bindParam(':usermail', $email);
        $result->execute();

        if($result->rowCount() >0){
            $row = $result -> fetch(PDO::FETCH_ASSOC);
            $userPass = $row['password'];

            if($password == $userPass){

            }else{
                $_SESSION['wrong_pass'] = 1;
                header("Location: ../../index.php");
            }
        }else{
            $_SESSION['wrong_email'] = 1;
            header("Location: ../../index.php");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>