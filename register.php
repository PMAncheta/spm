<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User Registration</title>
</head>

<body>
    <div class="form-container">
        <h2>User Registration</h2>
        <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            if ($error == 'username_taken') {
                echo "<div class='error-message'>Error: This username is already taken. Please choose a different username.</div>";
            } elseif ($error == 'email_taken') {
                echo "<div class='error-message'>Error: This email address is already registered. Please use a different email address.</div>";
            }
        } elseif (isset($_GET['success']) && $_GET['success'] == 'true') {
            echo "<div class='success-message'>User registration successful!</div>";
        }
        ?>
        <form method="post" action="regConfig.php">
            <input class="form-input" type="text" name="first_name" placeholder="First Name" required><br>
            <input class="form-input" type="text" name="middle_name" placeholder="Middle Name"><br>
            <input class="form-input" type="text" name="last_name" placeholder="Last Name" required><br>

            <select class="form-select" name="prefix">
                <option value="Select">Select</option>
                <option value="Jr.">Jr.</option>
                <option value="I">I</option>
                <option value="II">II</option>
                <option value="III">III</option>
            </select><br>

            <input class="form-input" type="text" name="username" placeholder="Username" required><br>
            <input class="form-input" type="email" name="email" placeholder="Email" required><br>
            <input class="form-input" type="password" name="password" placeholder="Password" required><br>

            <p>Already an account sign up <a href="login.php">here</a>
            <input class="form-btn" type="submit" name="register" value="Register">
        </form>
    </div>
</body>

</html>
