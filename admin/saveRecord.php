<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $data = json_decode($_POST['data'], true);

    // Extract data from the JSON array
    $lastName = $data[0];
    $firstName = $data[1];
    $middleName = $data[2];
    $regisPMES = $data[3];
    $loanAmort = $data[4];
    $interestInc = $data[5];
    $capBuildup = $data[6];

    // Perform the update operation in your database
    try {
        $stmt = $pdo->prepare("UPDATE tslr SET lastName=?, firstName=?, middleName=?, regisPMES=?, loanAmort=?, interestInc=?, capBuildup=?, total=? WHERE id=?");
        $stmt->execute([$lastName, $firstName, $middleName, $regisPMES, $loanAmort, $interestInc, $capBuildup, ($regisPMES + $loanAmort + $interestInc + $capBuildup), $id]);

        echo json_encode(['id' => $id, 'lastName' => $lastName, 'firstName' => $firstName, 'middleName' => $middleName, 'regisPMES' => $regisPMES, 'loanAmort' => $loanAmort, 'interestInc' => $interestInc, 'capBuildup' => $capBuildup, 'total' => ($regisPMES + $loanAmort + $interestInc + $capBuildup)]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
