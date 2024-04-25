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

// Aggregate data by Name and calculate sum of DocTotal
$sums = [];
foreach ($data as $item) {
    $name = $item['Name'];
    $docTotal = $item['DocTotal'];
    if (!isset($sums[$name])) {
        $sums[$name] = 0;
    }
    $sums[$name] += $docTotal;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Doc Totals</title>
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

    <h2>User Doc Totals</h2>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Total DocTotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sums as $name => $total): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $total; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>
