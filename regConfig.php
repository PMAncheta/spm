<?php
session_start();
include 'config.php'; // Include the database configuration file

// Function to track login attempts and deactivate account after 3 incorrect attempts
function trackLoginAttempts($username) {
    global $pdo;

    // Check if the user is an admin, if yes, do not track login attempts
    $stmt = $pdo->prepare("SELECT user_category, login_attempts FROM rgstn WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['user_category'] === 'admin') {
        return false;
    }

    // Increment login attempts
    $loginAttempts = $result['login_attempts'] + 1;
    $sql = "UPDATE rgstn SET login_attempts = :loginAttempts WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':loginAttempts', $loginAttempts);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if login attempts exceed the limit
    if ($loginAttempts >= 3) {
        // Deactivate the account
        $sql = "UPDATE rgstn SET status = 0 WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        header("Location: login.php?success=true");
        
        return true; // Account deactivated
    }

    return false; // Login attempts below limit, incorrect login
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if the form submission is for registration or login
    if (isset($_POST["register"])) {
        // User registration logic
        $first_name = $_POST["first_name"];
        $middle_name = $_POST["middle_name"];
        $last_name = $_POST["last_name"];
        $prefix = $_POST["prefix"];
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $user_category = $_POST["user_category"];

        // Check if the username already exists
        $stmt = $pdo->prepare("SELECT * FROM rgstn WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: register.php?error=username_taken");
            exit();
        }

        // Check if the email already exists
        $stmt = $pdo->prepare("SELECT * FROM rgstn WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: register.php?error=email_taken");
            exit();
        }

        // Password complexity check function
        function isPasswordComplex($password) {
            // Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one symbol, and one numeric digit
            $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
            return preg_match($pattern, $password);
        }

        if (!isPasswordComplex($password)) {
            echo "Error: Password does not meet complexity requirements.";
            exit();
        }

        try {
            // Establishing the database connection
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $db_user, $db_password);
            // Set the PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Hash the password before storing it in the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Your SQL query to insert data into the users table
            $sql = "INSERT INTO rgstn (first_name, middle_name, last_name, prefix, username, email, password, user_category, status, login_attempts) 
            VALUES (:first_name, :middle_name, :last_name, :prefix, :username, :email, :password, :user_category, 1, 0)";

            // Prepare the SQL statement
            $stmt = $pdo->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':first_name', $first_name);
            $stmt->bindParam(':middle_name', $middle_name);
            $stmt->bindParam(':last_name', $last_name);
            $stmt->bindParam(':prefix', $prefix);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_category', $user_category);

            // Execute the query
            $stmt->execute();

            // echo "User registration successful.";
            header("Location: login.php");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Redirect to login.php on successful registration
        header("Location: login.php?success=true");
        exit();

        // Close the database connection
        $pdo = null;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        // User login logic
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Check if the account is deactivated
        $stmt = $pdo->prepare("SELECT status FROM rgstn WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $status = $stmt->fetchColumn();

        if ($status == 0) {
            header("Location: login.php?error=account_deactivated");
            exit();
        }

        try {
            // Your SQL query to retrieve user data based on the username
            $sql = "SELECT * FROM rgstn WHERE username = :username AND status = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                $_SESSION['user_category'] = $user['user_category']; // Set the user role in the session

                // Reset login attempts to 0 when user successfully logs in
                $resetLoginAttemptsSql = "UPDATE rgstn SET login_attempts = 0 WHERE username = :username";
                $resetLoginAttemptsStmt = $pdo->prepare($resetLoginAttemptsSql);
                $resetLoginAttemptsStmt->bindParam(':username', $username);
                $resetLoginAttemptsStmt->execute();
            
                // Determine user category based on role
                if ($_SESSION['user_category'] === 'admin') {
                    $user_category = 'admin';
                } elseif ($_SESSION['user_category'] === 'head-user') {
                    $user_category = 'head-user';
                } elseif ($_SESSION['user_category'] === 'employee-user') {
                    $user_category = 'employee-user';
                } else {
                    // Handle unknown role (redirect to an error page, for example)
                    header("Location: login.php?error=unknown_user_category");
                    exit();
                }
            
                // Redirect users based on their roles and categories
                if ($_SESSION['user_category'] === 'admin') {
                    header("Location: /payroll/admin/dashboard.php");
                    exit();
                } elseif ($_SESSION['user_category'] === 'head-user') {
                    header("Location: /payroll/head_user/dashboard.php");
                    exit();
                } elseif ($_SESSION['user_category'] === 'employee-user') {
                    header("Location: /payroll/employee_user/dashboard.php");
                    exit();
                }
            } else {
                // Invalid username or password, redirect back to login page with an error message
                trackLoginAttempts($username); // Increment login attempts
                header("Location: login.php?error=invalid_credentials");
                exit();
            }
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Close the pdo connection
        $pdo = null;
    }
}
?>
