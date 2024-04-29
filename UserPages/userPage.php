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
    $docTotal = $item['DocTotal'];
    $CardName = $item['CardName'];
    if (!isset($sums[$PrimayId])) {
        $sums[$PrimayId] = [
            'Name' => $name,
            'CardName' => $CardName,
            'TotalDocTotal' => 0
        ];
    }
    $sums[$PrimayId]['TotalDocTotal'] += $docTotal;
}



// some calculations

foreach ($sums as $PrimayId => $infos){
    $totalSales = $totalSales + $infos['TotalDocTotal'];
    $totalUsers = $totalUsers +1;
}

//sales 100000 = 1 point

$totalPoints = $totalSales / 100000;




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Cubik | Sri Lanka</title>


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
                    <h2>Cubik<span class="danger"> Lanka</span></h2>
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
                            <h1 style="padding-left:10px"><?php echo "Rs.".number_format($totalSales, 2, '.', ','); ?></h1>
                        </div>
                        
                    </div>
                </div>
                <div class="visits">
                    <div class="status">
                        <div class="info">
                            <h3>Total Customrs</h3>
                            <h1 style="padding-left:10px"><?php echo $totalUsers; ?></h1>
                        </div>
                        
                    </div>
                </div>
                <div class="searches">
                    <div class="status">
                        <div class="info">
                            <h3>Total Points</h3>
                            <h1 style="padding-left:10px"><?php echo number_format($totalPoints, 2, '.', ','); ?></h1>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- End of Analyses -->

            

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h2>Top Buyers</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Total Invoice</th>
                            <th>Total Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sums as $PrimayId => $info): ?>
                            <?php if ($info['TotalDocTotal'] >= 8000000): ?>
                                <tr>
                                    <td><?php $num = $num +1 ; 
                                    echo $num; ?></td>
                                    <td><?php echo $info['Name']; ?></td>
                                    <td><?php echo $info['CardName']; ?></td>
                                    <td><?php echo "Rs .".number_format($info['TotalDocTotal'], 2, '.', ','); ?></td>
                                    <td><?php $userPoints = $info['TotalDocTotal'] / 100000;
                                    echo number_format($userPoints, 2, '.', ','); ?></td>
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