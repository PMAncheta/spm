<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($_POST['data'], true);

    // Insert data into the database
    try {
        $stmt = $pdo->prepare("INSERT INTO tslr (lastName, firstName, middleName, regisPMES, loanAmort, interestInc, capBuildup, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]]);
        echo 'Data inserted successfully.';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
