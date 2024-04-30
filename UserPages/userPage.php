<?php
require_once '../Db/Db.conn.php';
// require '../Db/configs/config.user.php';
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

// sql quires
$SelectSql = "SELECT * FROM `Users`";
$SelectSql_smtp = $conn->prepare($SelectSql);
$SelectSql_smtp->execute();

//select current year data
$currentYearSql = "SELECT * FROM CurrentYearDelivery";


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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <h2>Cubik<span class="danger" style="color: #f58634;"> Lanka</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>

            <div class="sidebar" >
                <a href="../Db/configs/InsertData.php" style="color: #f58634;">
                    <span class="material-icons-sharp">
                        not_started
                    </span>
                    <h3>Start Day</h3>
                </a>
                <a href="#" onclick="DisplayDash()" <?php if($_SESSION['DayStart'] != 1){echo 'style="color: #f58634;display:none;"';} else{echo 'style="color: #f58634;display:flex;gap:5px;"';} ?> >
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>
                <a href="#" onclick="DisplayUser()" <?php if($_SESSION['DayStart'] != 1){echo 'style="color: #f58634;display:none;"';} else{echo 'style="color: #f58634;display:flex;gap:5px;"';} ?>>
                    <span class="material-icons-sharp">
                        person_outline
                    </span>
                    <h3>Users</h3>
                </a>
                <a href="#" <?php if($_SESSION['DayStart'] != 1){echo 'style="color: #f58634;display:none;"';} else{echo 'style="color: #f58634;display:flex;gap:5px;"';} ?>>
                    <span class="material-icons-sharp">
                        insights
                    </span>
                    <h3>Analytics</h3>
                </a>
                <a href="#" style="color: #f58634;">
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
                <a href="#" onclick="logOut()" style="color: #FF0060;">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- End of Sidebar Section -->

        <!-- Main Content -->
        <main id="dashbordContainer" <?php if($_SESSION['addUser'] == 1){
            echo "style='display:none;'";
        } ?>>
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

        <!-- start user container -->
        <main id="userContainer" <?php if($_SESSION['addUser'] == 1){
            echo "style='display:block;'";
        } ?>>
            <h1>User Analisis</h1>
            <!-- Analyses -->
            
            <!-- End of Analyses -->
            <form action="../Db/configs/config.user.php" method="post" class="userForm">
                    <div class="form first">
                        <div class="details personal">
                            <span class="title">Add User</span>
                            <br><br>
                            <div class="feilds">
                                <div class="input-feilds">
                                    <label>Name</label>
                                    <input type="text" name="username" id="#" >
                                </div>
                                <div class="input-feilds">
                                    <label>Email</label>
                                    <input type="email" name="email" id="#" >
                                </div>
                                <div class="input-feilds">
                                    <label>Password</label>
                                    <input type="text" name="password" id="#">
                                </div>
                                <div class="input-feilds">
                                    <label>Admin Access</label>
                                    <select name="admin_access">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="btns">
                                <button type="submit" class="nxtBtn submits" name="submits">
                                    <span class="btnText" ></span>Create User</span>
                                </button>
                                
                            </div>
                        </div>
                    </div>
                </form>
            

            <!-- Recent Orders Table -->
            <div class="recent-orders">
                <h2>System Users</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Admin Access</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($SelectSql_smtp_row = $SelectSql_smtp->fetch(PDO::FETCH_ASSOC)){ ?>
                        <tr>
                            <td><?php echo $SelectSql_smtp_row['userId']; ?></td>
                            <td><?php echo $SelectSql_smtp_row['userName']; ?></td>
                            <td><?php echo $SelectSql_smtp_row['userEmail']; ?></td>
                            <td><?php echo $SelectSql_smtp_row['userPassword']; ?></td>
                            <td><?php 
                                if($SelectSql_smtp_row['userAccess'] == 1){
                                    ?> <span style="padding:3px 6px;border:1px solid #f58634 ;background: #f58634 ;color:aliceblue;border-radius: 5px;">Have</span> <?php
                                }else{
                                    ?> <span style="padding:3px 6px;border:1px solid #FF0060 ;background: #FF0060 ;color:aliceblue;border-radius: 5px;">Dont Have</span> <?php
                                }
                            ?></td>
                            <td>
                                <form action="../Db/configs/config.user.php" method="post">
                                    <input type="hidden" name="id" value="<?php echo $SelectSql_smtp_row['userId'];  ?>">
                                    <input type="submit" name="Delete_user" value="Delete User" style="padding:4px 6px;border:1px solid #FF0060 ;background: #FF0060 ;color:aliceblue;border-radius: 5px;">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>

                </table>
            </div>
            <!-- End of Recent Orders -->

        </main>
        <!--end -- user Container-->

      

    </div>

    <script src="orders.js"></script>
    <script src="index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- sweet alert php -->
    <?php
    
    if($_SESSION['addUser'] == 1 ){
        echo '
        <script>
        Swal.fire({
            title: "User Operation Successfull !",
            text: "Success!",
            icon: "success"
          });
          </script>
        '
        ; // Set the flag to true
        $_SESSION['addUser'] = null; // Reset the session variable
        
    }
    
    ?>


    <script>
        function logOut(){
            Swal.fire({
            title: "Are you sure?",
            text: "Do you want to logout now!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                location.href = "../Db/configs/logOut.php"
            }
            });
        }
        
            Swal.fire({
            title: "Are you sure?",
            text: "Do you want to Delete User!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                location.href = `../Db/configs/config.user.php`
            }
            });
        }
    </script>


    <script>
        function DisplayDash(){
            document.getElementById('dashbordContainer').style.display = 'block';
            document.getElementById('userContainer').style.display = 'none';
        }

        function DisplayUser(){
            document.getElementById('userContainer').style.display = 'block';
            document.getElementById('dashbordContainer').style.display = 'none';
        }
    </script>
</body>

</html>