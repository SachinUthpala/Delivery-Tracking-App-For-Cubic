<?php
 // Assuming this file contains your database connection code
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

// Initialize an empty array to store filtered data
$filteredData = [];

// Aggregate data by CntctCode and calculate sum of DocTotal
foreach ($data as $item) {
    $docDate = $item['DocDate'];
    $docYear = date('Y', strtotime($docDate));
    if ($docYear == $year) {
        $filteredData[] = $item;
    }
}

// Display filtered data in a table
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Filtered Data for <?php echo $year; ?></h2>
    <table>
        <thead>
            <tr>
                <th>CntctCode</th>
                <th>Name</th>
                <th>DocNum</th>
                <th>DocDate</th>
                <th>CANCELED</th>
                <th>CardName</th>
                <th>DocTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filteredData as $item): ?>
                <tr>
                    <td><?php echo $item['CntctCode']; ?></td>
                    <td><?php echo $item['Name']; ?></td>
                    <td><?php echo $item['DocNum']; ?></td>
                    <td><?php echo $item['DocDate']; ?></td>
                    <td><?php echo $item['CANCELED']; ?></td>
                    <td><?php echo $item['CardName']; ?></td>
                    <td><?php echo $item['DocTotal']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
