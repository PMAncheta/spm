<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>User Login</title>
</head>

<body>
    <div class="form-container">
        <h2>User Login</h2>
        <?php
        if (isset($_GET['success']) && $_GET['success'] == 'true') {
            echo "<div class='success-message'>Registration successful! You can now log in.</div>";
        }
        ?>
        <form method="post" action="regConfig.php">
            <input class="form-input" type="text" name="username" placeholder="Username" required><br>
            <input class="form-input" type="password" name="password" placeholder="Password" required><br>
            <p>Dont have account yet? <a href="register.php">Sign here</a>
            <input class="form-btn" type="submit" name="login" value="Login">
        </form>
    </div>
</body>

</html>
