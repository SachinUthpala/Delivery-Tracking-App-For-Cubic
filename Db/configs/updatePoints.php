<?php


require_once '../Db.conn.php';
session_start();

$year = date("Y");

if(isset($_POST['used_point_submit'])){
    $id = $_POST['id'];
    $currentPoints = $_POST['currentPoints'];
    $points = $_POST['used_point'];

    $sql = "INSERT INTO CurrentYearDelivery (`UsedPoints`) VALUES ( :UsedPoints) WHERE Name = :name";
    $smtp = $conn -> prepare($sql);
    $smtp->bindParam(":UsedPoints" , $points );
    $smtp->bindParam(":Name" , $id );
    
}


?>