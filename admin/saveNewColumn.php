<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $data = json_decode($_POST['data'], true);

    // Extract data from the JSON array
    $regisPMES = $data[0];
    $loanAmort = $data[1];
    $interestInc = $data[2];
    $capBuildup = $data[3];

    // Perform the update operation in your database
    try {
        $stmt = $pdo->prepare("UPDATE tslr SET regisPMES=?, loanAmort=?, interestInc=?, capBuildup=?, total=? WHERE id=?");
        $stmt->execute([$regisPMES, $loanAmort, $interestInc, $capBuildup, ($regisPMES + $loanAmort + $interestInc + $capBuildup), $id]);
    
        echo json_encode(['id' => $id, 'regisPMES' => $regisPMES, 'loanAmort' => $loanAmort, 'interestInc' => $interestInc, 'capBuildup' => $capBuildup, 'total' => ($regisPMES + $loanAmort + $interestInc + $capBuildup)]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
     
}
?>
