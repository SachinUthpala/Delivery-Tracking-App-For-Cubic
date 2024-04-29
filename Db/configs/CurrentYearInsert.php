<?php

require_once '../Db.conn.php';
session_start();

$year = date("Y");

// API URL
$url = "http://10.0.0.237:3000/api/inv";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_error($ch)) {
    echo 'Error: '. curl_error($ch);
    header("Location: ../index.php");
    $_SESSION['API_NOT_WORKING'] = 1;
    exit;
}

// Close cURL session
curl_close($ch);

// Decode JSON response
$data = json_decode($response, true);

// Check if decoding was successful
if (!$data) {
    echo 'Error: Unable to decode JSON response';
    exit;
}

// Check if data is empty
if (empty($data)) {
    echo 'No data available';
    exit;
}

// Initialize an empty array to store aggregated DocTotal for each Name
$totals = [];

// Aggregate data by Name and calculate total DocTotal for each Name
foreach ($data as $item) {
    $docDate = $item['DocDate'];
    $docYear = date('Y', strtotime($docDate));
    if ($docYear == $year) {
        $name = $item['Name'];
        $CardName = $item['CardName'];
        $CntctCode = $item['CntctCode'];
        $docTotal = $item['DocTotal'];
        if (!isset($totals[$name])) {
            $totals[$name] = ['year' => $year,
            'CardName' => $CardName,
            'CntctCode' => $CntctCode,
            'total' => 0];
        }
        $totals[$name]['total'] += $docTotal;
    }
}

// Insert aggregated totals into database
foreach ($totals as $name => $info) {

    $CntctCode = $info['CntctCode'];
    $CardName = $info['CardName'];

    $sqlCheck = "SELECT * FROM CurrentYearDelivery WHERE Name = :Name";
    $checkStmt = $conn->prepare($sqlCheck);
    $checkStmt->bindParam(":Name", $name);
    $checkStmt->execute();

    if($checkStmt->rowCount() > 0) {
        $updateSql = "UPDATE `CurrentYearDelivery` SET `AllDocTotal`=:AllDocTotal WHERE Name = :name";
        $updateResult = $conn->prepare($updateSql);
        $updateResult -> bindParam(":AllDocTotal" , $info['total']);
        $updateResult -> bindParam(":name" , $name);
        $updateResult->execute();

    }else{
        
        $RemainingPoints = number_format(($info['total'] / 100000) , 2 , '.');
        $sql = "INSERT INTO `CurrentYearDelivery` (`Name`, `CntctCode`, `CardName`, `AllDocTotal`, `RemainingPoints`) 
                VALUES (:Name, :CntctCode, :CardName, :AllDocTotal, :RemainingPoints)";
        $insert = $conn->prepare($sql);
        $insert->bindParam(":Name", $name);
        $insert->bindParam(":CntctCode", $CntctCode);
        $insert->bindParam(":CardName", $CardName);
        $insert->bindParam(":AllDocTotal", $info['total']);
        $insert->bindParam(":RemainingPoints", $RemainingPoints);
        $insert->execute();
    }


    
}


header("Location: ../../UserPages/userPage.php");

?>
