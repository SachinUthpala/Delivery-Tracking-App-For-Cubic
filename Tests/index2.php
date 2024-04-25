<?php

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
    echo 'Error: ' . curl_error($ch);
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

// Organize data by CntctCode
$organizedData = [];
foreach ($data as $item) {
    $cntctCode = $item['CntctCode'];
    if (!isset($organizedData[$cntctCode])) {
        $organizedData[$cntctCode] = [];
    }
    $organizedData[$cntctCode][] = $item;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Data</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
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

    <h2>Data from API</h2>

    <?php foreach ($organizedData as $cntctCode => $items): ?>
        <?php $total = 0;   ?>  
        <h3>CntctCode: <?php echo $cntctCode; ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>DocNum</th>
                    <th>CANCELED</th>
                    <th>CardName</th>
                    <th>DocTotal</th>
                    <!-- Add more table headers if needed -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $total = $total + $item['DocTotal'] ?>
                    <tr>
                        <td><?php echo $item['Name']; ?></td>
                        <td><?php echo $item['DocNum']; ?></td>
                        <td><?php echo $item['CANCELED']; ?></td>
                        <td><?php echo $item['CardName']; ?></td>
                        <td><?php echo $item['DocTotal']; ?></td>
                        <td><?php echo $total;?></td>
                        <!-- Add more table cells if needed -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endforeach; ?>

</body>

</html>
