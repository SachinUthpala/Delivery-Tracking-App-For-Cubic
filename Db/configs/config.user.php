<?php

require_once '../Db.conn.php';
session_start();

if(isset($_POST['submits'])){
    $userName= $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $adminAcc = $_POST['admin_access'];

    $insertUser = "INSERT INTO `Users`( `userName`, `userEmail`, `userPassword`, `userAccess`) VALUES (:userName,:userEmail,:userPassword,:userAccess)";
    $insertResult = $conn->prepare($insertUser); // Corrected variable name
    $insertResult->bindParam(":userName" , $userName);
    $insertResult->bindParam(":userEmail" , $email);
    $insertResult->bindParam(":userPassword" , $password);
    $insertResult->bindParam(":userAccess" , $adminAcc);

    $insertResult->execute();

    if($insertResult->rowCount() > 0){ // Corrected variable name
        echo "User added";
        $_SESSION['addUser'] = 1;
        header("Location: ../../UserPages/userPage.php");
    }else{
        echo "Error: Failed to insert data.";
        $_SESSION['unInserted'] = 1;
        header("Location: ../../UserPages/userPage.php");
    }
}

if(isset($_POST['Delete_user'])){
    $id = $_POST['id'];

    $deleteUser = "DELETE FROM `Users` WHERE userId = :id";
    $deleteUserResult = $conn->prepare($deleteUser);
    $deleteUserResult->bindParam(":id" , $id);
    $deleteUserResult->execute();

        echo "User added";
        $_SESSION['addUser'] = 1;
        header("Location: ../../UserPages/userPage.php");
}

?>
