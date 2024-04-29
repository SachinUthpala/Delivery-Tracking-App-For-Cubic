<?php

require_once '../Db.conn.php';
session_start();

// API URL
$url = "http://10.0.0.237:3000/api/inv";



// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// Execute cURL request
$response = curl_exec($ch);
$totalSales = 0;
$totalUsers = 0;
$totalPoints = 0;
$num = 0;
// Check for errors
if (curl_error($ch)) {
    echo 'Error: '. curl_error($ch);
    header("Location: ../index.php");
    $_SESSION['API_NOT_WORKING'] = 1;
    exit;
}else{

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



// some calculations

foreach ($sums as $PrimayId => $infos){
    $CntctCode = $infos['CntctCode'];
    $Name = $infos['Name'];
    $CardName = $infos['CardName'];
    $TotalBuys = $infos['TotalDocTotal'];

    echo $CntctCode.'=='.$Name.'--'.$TotalBuys.'<br>';
}

?>
