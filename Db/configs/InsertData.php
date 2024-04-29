<?php

require_once '../Db.conn.php'; // Assuming this file contains your database connection code
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


// Aggregate data by CntctCode and calculate sum of DocTotal
$sums = [];
foreach ($data as $item) {
    $PrimayId = $item['Name'];
    $name = $item['Name'];
    $CntctCode = $item['CntctCode'];
    $docTotal = $item['DocTotal'];
    $CardName = $item['CardName'];
    if (!isset($sums[$PrimayId])) {
        $sums[$PrimayId] = [
            'Name' => $name,
            'CardName' => $CardName,
            'CntctCode' => $CntctCode,
            'TotalDocTotal' => 0
        ];
    }
    $sums[$PrimayId]['TotalDocTotal'] += $docTotal;
}

$n=0;

// Iterate through the aggregated data
// foreach ($sums as $PrimayId => $infos){
//     $CntctCode = $infos['CntctCode'];
//     $Name = $infos['Name'];
//     $CardName = $infos['CardName'];
//     $TotalBuys = $infos['TotalDocTotal'];
//     $n = $n+1;
//     // Checking if the username is available

//     $sqlCheck = "SELECT * FROM Deliverys WHERE Name = :Name";
//     $checkStmt = $conn->prepare($sqlCheck);
//     $checkStmt->bindParam(":Name", $Name);
//     $checkStmt->execute();

//     if($checkStmt->rowCount() > 0) {

//         $row = $checkSsqlUpdate1_Resulttmt->fetch(PDO::FETCH_ASSOC);
//         $Sales = $row['AllDocTotal'];
//         $exsistedYear = $row['CurrentYear'];

//         if($exsistedYear == $year){
//             $sqlUpdate1 = "UPDATE `Deliverys` SET `CntctCode`=:CntctCode,`CardName`=:CardName
//             ,`AllDocTotal`=:AllDocTotal,`ThisYearAllDocTotal`=:ThisYearAllDocTotal , `CurrentYear` =:CurrentYear WHERE Name = :name";
            
//             $sqlUpdate1_Result = $conn->prepare($sqlUpdate1);
//             $sqlUpdate1_Result->bindParam(":Name", $Name);
//             $sqlUpdate1_Result->bindParam(":CntctCode", $CntctCode);
//             $sqlUpdate1_Result->bindParam(":CardName", $CardName);
//             $sqlUpdate1_Result->bindParam(":AllDocTotal", $TotalBuys); 
//             $sqlUpdate1_Result->bindParam(":ThisYearAllDocTotal", $TotalBuys); 
//             $sqlUpdate1_Result->bindParam(":CurrentYear" , $year);
//             $sqlUpdate1_Result->execute();

//             echo 'Updated'.'<br>';
//         }else{

//             $ThisYearTotalBuyers = $TotalBuys - $Sales;

//             $sqlUpdate2 = "UPDATE `Deliverys` SET `CntctCode`=:CntctCode,`CardName`=:CardName
//             ,`AllDocTotal`=:AllDocTotal,`ThisYearAllDocTotal`=:ThisYearAllDocTotal , `CurrentYear` =:CurrentYear WHERE Name = :name";
            
//             $sqlUpdate2_Result = $conn->prepare($sqlUpdate2);
//             $sqlUpdate2_Result->bindParam(":Name", $Name);
//             $sqlUpdate2_Result->bindParam(":CntctCode", $CntctCode);
//             $sqlUpdate2_Result->bindParam(":CardName", $CardName);
//             $sqlUpdate2_Result->bindParam(":AllDocTotal", $TotalBuys); 
//             $sqlUpdate2_Result->bindParam(":ThisYearAllDocTotal", $ThisYearTotalBuyers); 
//             $sqlUpdate2_Result->bindParam(":CurrentYear" , $year);
//             $sqlUpdate2_Result->execute();

//         }

//         $sqlUpdate = "UPDATE `Deliverys` SET `CntctCode`=:CntctCode,`CardName`=:CardName
//         ,`AllDocTotal`=:AllDocTotal,`ThisYearAllDocTotal`=:ThisYearAllDocTotal WHERE Name = :name";
//     }else{
//         $sql2 = "INSERT INTO `Deliverys`(`Name`, `CntctCode`, `CardName`, `AllDocTotal`, `ThisYearAllDocTotal` , `CurrentYear`) VALUES (:Name, :CntctCode, :CardName, :AllDocTotal, :ThisYearAllDocTotal , :CurrentYear)";
//         $insertResult = $conn->prepare($sql2);
//         $insertResult->bindParam(":Name", $Name);
//         $insertResult->bindParam(":CntctCode", $CntctCode);
//         $insertResult->bindParam(":CardName", $CardName);
//         $insertResult->bindParam(":AllDocTotal", $TotalBuys); // Assuming $AllDocTotal should be $TotalBuys
//         $insertResult->bindParam(":ThisYearAllDocTotal", $TotalBuys); // Assuming $AllDocTotal should be $TotalBuys
//         $insertResult->bindParam(":CurrentYear" , $year);
//         $insertResult->execute();


//     }
// }

foreach ($sums as $PrimayId => $infos){
    $CntctCode = $infos['CntctCode'];
    $Name = $infos['Name'];
    $CardName = $infos['CardName'];
    $TotalBuys = $infos['TotalDocTotal'];
    $n = $n+1;
    // Checking if the username is available

    $sqlCheck = "SELECT * FROM Deliverys WHERE Name = :Name";
    $checkStmt = $conn->prepare($sqlCheck);
    $checkStmt->bindParam(":Name", $Name);
    $checkStmt->execute();

    if($checkStmt->rowCount() > 0) {
        $row = $checkStmt->fetch(PDO::FETCH_ASSOC);
        $Sales = $row['AllDocTotal'];
        $existedYear = $row['CurrentYear'];

        if($existedYear == $year){
            $sqlUpdate1 = "UPDATE `Deliverys` SET `CntctCode`=:CntctCode, `CardName`=:CardName, `AllDocTotal`=:AllDocTotal, `ThisYearAllDocTotal`=:ThisYearAllDocTotal , `CurrentYear`=:CurrentYear WHERE Name = :Name";
            $sqlUpdate1_Result = $conn->prepare($sqlUpdate1);
            $sqlUpdate1_Result->bindParam(":CntctCode", $CntctCode);
            $sqlUpdate1_Result->bindParam(":CardName", $CardName);
            $sqlUpdate1_Result->bindParam(":AllDocTotal", $TotalBuys); 
            $sqlUpdate1_Result->bindParam(":ThisYearAllDocTotal", $TotalBuys); 
            $sqlUpdate1_Result->bindParam(":CurrentYear", $year);
            $sqlUpdate1_Result->bindParam(":Name", $Name);
            $sqlUpdate1_Result->execute();

            echo $n.'Updated--'.'<br>';
        } else {
            $ThisYearTotalBuyers = $TotalBuys - $Sales;

            $sqlUpdate2 = "UPDATE `Deliverys` SET `CntctCode`=:CntctCode, `CardName`=:CardName, `AllDocTotal`=:AllDocTotal, `ThisYearAllDocTotal`=:ThisYearAllDocTotal , `CurrentYear`=:CurrentYear WHERE Name = :Name";
            $sqlUpdate2_Result = $conn->prepare($sqlUpdate2);
            $sqlUpdate2_Result->bindParam(":CntctCode", $CntctCode);
            $sqlUpdate2_Result->bindParam(":CardName", $CardName);
            $sqlUpdate2_Result->bindParam(":AllDocTotal", $TotalBuys); 
            $sqlUpdate2_Result->bindParam(":ThisYearAllDocTotal", $ThisYearTotalBuyers); 
            $sqlUpdate2_Result->bindParam(":CurrentYear", $year);
            $sqlUpdate2_Result->bindParam(":Name", $Name);
            $sqlUpdate2_Result->execute();

            echo $n.'Updated**'.'<br>';
        }
    } else {
        $sql2 = "INSERT INTO `Deliverys`(`Name`, `CntctCode`, `CardName`, `AllDocTotal`, `ThisYearAllDocTotal`, `CurrentYear`) VALUES (:Name, :CntctCode, :CardName, :AllDocTotal, :ThisYearAllDocTotal, :CurrentYear)";
        $insertResult = $conn->prepare($sql2);
        $insertResult->bindParam(":Name", $Name);
        $insertResult->bindParam(":CntctCode", $CntctCode);
        $insertResult->bindParam(":CardName", $CardName);
        $insertResult->bindParam(":AllDocTotal", $TotalBuys); 
        $insertResult->bindParam(":ThisYearAllDocTotal", $TotalBuys); 
        $insertResult->bindParam(":CurrentYear", $year);
        $insertResult->execute();

        echo 'Inserted'.'<br>';
    }
}


?>
