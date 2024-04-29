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

// Initialize an empty array to store aggregated DocTotal for each Name
$totals = [];

// Aggregate data by Name and calculate total DocTotal for each Name
foreach ($data as $item) {
    $docDate = $item['DocDate'];
    $docYear = date('Y', strtotime($docDate));
    if ($docYear == $year) {
        $name = $item['Name'];
        $docTotal = $item['DocTotal'];
        if (!isset($totals[$name])) {
            $totals[$name] = ['year' => $year, 'total' => 0];
        }
        $totals[$name]['total'] += $docTotal;
    }
}

// Display aggregated totals in a table
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total DocTotal by Name</title>
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
    <h2>Total DocTotal by Name for <?php echo $year; ?></h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Year</th>
                <th>Total DocTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($totals as $name => $info): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $info['year']; ?></td>
                    <td><?php echo $info['total']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
