<?php
// Include the configuration file
include 'config.php';

// Define the deactivateAccount() function
function deactivateAccount($username) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE rgstn SET status = 0 WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}

// Define the activateAccount() function
function activateAccount($username) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE rgstn SET status = 1, login_attempts = 0 WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}
?>

<!DOCTYPE html>
  <!-- Coding by CodingLab | www.codinglabweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="style.css">
    
    <!----===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    
    <!--<title>Dashboard Sidebar Menu</title>--> 
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <!--<img src="logo.png" alt="">-->
                </span>

                <div class="text logo-text">
                    <span class="name">SPM</span>
                </div>
            </div>

            <i class='bx bx-chevron-right toggle'></i>
        </header>

        <div class="menu-bar">
            <div class="menu">

                <li class="search-box">
                    <i class='bx bx-search icon'></i>
                    <input type="text" placeholder="Search...">
                </li>

                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/payroll/journal/dashboard.php">
                            <i class='bx bx-home-alt icon' ></i>
                            <span class="text nav-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-bar-chart-alt-2 icon' ></i>
                            <span class="text nav-text">Revenue</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="#">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Notifications</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/payroll/admin/showSLR.php">
                            <i class='bx bx-pie-chart-alt icon' ></i>
                            <span class="text nav-text">Analytics</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/payroll/admin/SLR.php">
                            <i class='bx bx-heart icon' ></i>
                            <span class="text nav-text">Salary Loan Remittance</span>
                        </a>
                    </li>

                    <li class="nav-link">
                        <a href="/payroll/admin/admin_panel.php">
                            <i class='bx bx-user icon' ></i>
                            <span class="text nav-text">User Employees</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="bottom-content">
                <li class="">
                    <a href="/payroll/login.php">
                        <i class='bx bx-log-out icon' ></i>
                        <span class="text nav-text">Logout</span>
                    </a>
                </li>

                <li class="mode">
                    <div class="sun-moon">
                        <i class='bx bx-moon icon moon'></i>
                        <i class='bx bx-sun icon sun'></i>
                    </div>
                    <span class="mode-text text">Dark mode</span>

                    <div class="toggle-switch">
                        <span class="switch"></span>
                    </div>
                </li>
                
            </div>
        </div>

    </nav>

    <section class="home">
        <div class="text">Users Account</div>

        <ul class="user-list">
            <?php
            include 'config.php';
            session_start();

            if ($_SESSION['user_category'] !== 'admin') {
                header("Location: login.php");
                exit();
            }

            // Handle account activation/deactivation logic
            if (isset($_GET['action']) && isset($_GET['username'])) {
                $action = $_GET['action'];
                $username = $_GET['username'];
            
                if ($_SESSION['user_category'] === 'admin') {
                    if ($action === 'activate') {
                        // Activate the account
                        activateAccount($username);
                        echo "<p>Account for username '$username' has been activated.</p>";
                    } elseif ($action === 'deactivate') {
                        // Deactivate the account
                        deactivateAccount($username);
                        echo "<p>Account for username '$username' has been deactivated.</p>";
                    }
                } else {
                    echo "Access denied. You don't have permission to perform this action.";
                }
            }

            // Fetch and display the list of users
            $stmt = $pdo->prepare("SELECT username, status FROM rgstn");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($users as $user) {
                $username = $user['username'];
                $status = $user['status'];
                echo "<li>$username (Status: " . ($status == 1 ? 'Active' : 'Inactive') . ")";
        
                // Check if the account is active
                if ($status == 1) {
                    // Show Deactivate link for active accounts
                    echo " - <a href='admin_panel.php?action=deactivate&username=$username'>Deactivate</a>";
                } else {
                    // Show Activate link for inactive accounts
                    echo " - <a href='admin_panel.php?action=activate&username=$username'>Activate</a>";
                }
                echo "</li>";
            }       
            ?>
        </ul>
    </section>

    <script>
        const body = document.querySelector('body'),
        sidebar = body.querySelector('nav'),
        toggle = body.querySelector(".toggle"),
        searchBtn = body.querySelector(".search-box"),
        modeSwitch = body.querySelector(".toggle-switch"),
        modeText = body.querySelector(".mode-text");


        toggle.addEventListener("click" , () =>{
            sidebar.classList.toggle("close");
        })

        searchBtn.addEventListener("click" , () =>{
            sidebar.classList.remove("close");
        })

        modeSwitch.addEventListener("click" , () =>{
            body.classList.toggle("dark");
            
            if(body.classList.contains("dark")){
                modeText.innerText = "Light mode";
            }else{
                modeText.innerText = "Dark mode";
                
            }
        });
    </script>

</body>
</html>