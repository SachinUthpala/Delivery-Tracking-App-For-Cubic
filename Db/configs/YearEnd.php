<?php

require_once '../Db.conn.php';
// Get the current month (numeric representation)
session_start();
$currentMonth = date('m');

// Get the current date (day of the month)
$currentDate = date('d');


// if($currentMonth == 12){
//     if($currentDate == 31){
//         $sql = "TRUNCATE TABLE CurrentYearDelivery";
//         $smtp = $conn -> prepare($sql);
//         $smtp->execute();
//         header("Location: ../../index.php"); 
//     }else{

//     }
// }else{

// }


    $sql = "DELETE FROM CurrentYearDelivery";
    $smtp = $conn -> prepare($sql);
    if (!$smtp->execute()) {
        echo "Error: " . $smtp->errorInfo();
    } else {
        $_SESSION = array();

        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-42000, '/');
        }

        // Destroy the session
        session_destroy();
        echo "Rows deleted successfully";
        header("Location: ../../index.php");
    }

?>