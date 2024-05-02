<?php

require_once '../Db.conn.php';
session_start();

$year = date("Y");

if(isset($_POST['used_point_submit'])){
    $id = $_POST['id'];
    $currentPoints = $_POST['currentPoints'];
    $points = $_POST['used_point'];

    // Use UPDATE statement to update existing row
    $sql = "UPDATE CurrentYearDelivery SET UsedPoints = :UsedPoints WHERE Name = :Name";
    $smtp = $conn->prepare($sql);
    $smtp->bindParam(":UsedPoints", $points);
    $smtp->bindParam(":Name", $id);
    $smtp->execute();

    // Check if the update was successful
    if ($smtp->rowCount() > 0) {
        $_SESSION['updatePpoint'] = 1;
        header("Location: ../UserPages/UserPage.php");
        exit(); // Always exit after redirecting
    } else {
        echo "Error updating points";
    }
}

?>
