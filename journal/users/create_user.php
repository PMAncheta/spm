<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create_user"])) {
    // Retrieve form data
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    // Add more fields as needed

    try {
        // Insert new user into the database
        $stmt = $pdo->prepare("INSERT INTO rgstn (first_name, last_name) VALUES (:first_name, :last_name)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        // Bind more parameters as needed

        $stmt->execute();

        // Redirect back to users.php after user creation
        header("Location: users.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
