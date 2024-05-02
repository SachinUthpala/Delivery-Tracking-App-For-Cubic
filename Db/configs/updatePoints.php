<?php

require_once '../Db.conn.php';
session_start();

$year = date("Y");

if(isset($_POST['used_point_submit'])){
    $id = $_POST['id'];
    $alredyUse = $_POST['alredyUse'];
    $currentPoints = $_POST['currentPoints'];
    $points = floatval($_POST['used_point'] + $alredyUse);

    echo $id . '645-' . $points;

    // Use UPDATE statement to update existing row
    $sql = "UPDATE CurrentYearDelivery SET UsedPoints = :UsedPoints WHERE Name = :Name";
    $smtp = $conn->prepare($sql);
    $smtp->bindParam(":UsedPoints", $points);
    $smtp->bindParam(":Name", $id);
    $smtp->execute();

    $_SESSION['updatePpoint'] = 1;
    header("Location: ../../UserPages/userPage.php");

    // Check if the update was successful

}

?>
