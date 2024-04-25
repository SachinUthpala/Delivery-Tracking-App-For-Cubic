<?php
require_once '../Db/Db.conn.php';
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

// Aggregate data by CntctCode and calculate sum of DocTotal
$sums = [];
foreach ($data as $item) {
    $cntctCode = $item['CntctCode'];
    $name = $item['Name'];
    $docTotal = $item['DocTotal'];
    $CardName = $item['CardName'];
    if (!isset($sums[$cntctCode])) {
        $sums[$cntctCode] = [
            'Name' => $name,
            'CardName' => $CardName,
            'TotalDocTotal' => 0
        ];
    }
    $sums[$cntctCode]['TotalDocTotal'] += $docTotal;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Cubic | Sri Lanka</title>


    <!--for sweet alert-->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js" ></script>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <h2>Cubic<span class="danger"> Lanka</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>

            <div class="sidebar">
                <a href="#">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">
                        person_outline
                    </span>
                    <h3>Users</h3>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">
                        insights
                    </span>
                    <h3>Analytics</h3>
                </a>
                <a href="#">
                    <div class="nav">
                        <button id="menu-btn">
                            
                        </button>
                        <div class="dark-mode" style="gap: 20px;">
                            <span class="material-icons-sharp">
                                light_mode
                            </span>
                            <span class="material-icons-sharp active">
                                dark_mode
                            </span>
                        </div>
                    </div>
                </a>
                <a href="#">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main>
            <h1>Dashboard</h1>
            <!-- Analyses -->
            <div class="analyse">
                <div class="sales">
                    <div class="status">
                        <div class="info">
                            <h3>Total Sales</h3>
                            <h1>$65,024</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="visits">
                    <div class="status">
                        <div class="info">
                            <h3>Site Visit</h3>
                            <h1>24,981</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="searches">
                    <div class="status">
                        <div class="info">
                            <h3>Searches</h3>
                            <h1>14,147</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Analyses -->

            

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h2>Recent Orders</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Total Invoice</th>
                            <th>Total Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sums as $cntctCode => $info): ?>
                            <?php if ($info['TotalDocTotal'] >= 8000000): ?>
                                <tr>
                                    <td><?php echo $cntctCode; ?></td>
                                    <td><?php echo $info['Name']; ?></td>
                                    <td><?php echo $info['CardName']; ?></td>
                                    <td><?php echo $info['TotalDocTotal']; ?></td>
                                    <td>10</td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>
            <!-- End of Recent Orders -->

        </main>
        <!-- End of Main Content -->

      

    </div>

    <script src="orders.js"></script>
    <script src="index.js"></script>
</body>

</html>