<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle add form submission
    $name = $_POST['name'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $regisPMES = floatval($_POST['regisPMES']); // Convert to number
    $loanAmort = floatval($_POST['loanAmort']); // Convert to number
    $interestInc = floatval($_POST['interestInc']); // Convert to number
    $capBuildup = floatval($_POST['capBuildup']); // Convert to number
    $total = $regisPMES + $loanAmort + $interestInc + $capBuildup; // Calculate total

    $stmt = $pdo->prepare("INSERT INTO payroll (name, firstName, middleName, regisPMES, loanAmort, interestInc, capBuildup, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $firstName, $middleName, $regisPMES, $loanAmort, $interestInc, $capBuildup, $total]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['add_column'])) {
    // Handle add column form submission
    $stmt = $pdo->query("INSERT INTO payroll (name, firstName, middleName, regisPMES, loanAmort, interestInc, capBuildup, total) VALUES ('', '', '', 0, 0, 0, 0, 0)");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
</head>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<body>
    <h1>Payroll</h1>
    <h2>Payroll Records</h2>

    <table border="1" id="recordsTable">
        <tr>
            <th>ID</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>RegisPMES</th>
            <th>Loan Amortization</th>
            <th>Interest Income</th>
            <th>Capital Buildup</th>
            <th>Total</th>
            <th>Action</th>
        </tr>

        <?php
        // Retrieve records from database
        $stmt = $pdo->query("SELECT * FROM payroll");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if $rows is null or empty before using it in the foreach loop
        if (!empty($rows)) {
            foreach ($rows as $row) :
                $regisPMES = intval($row['regisPMES']); // Convert to integer
        ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['firstName'] ?></td>
                    <td><?= $row['middleName'] ?></td>
                    <td><?= $row['regisPMES'] ?></td>
                    <td><?= $row['loanAmort'] ?></td>
                    <td><?= $row['interestInc'] ?></td>
                    <td><?= $row['capBuildup'] ?></td>
                    <td><button onclick="editRecord(<?= $row['id'] ?>)">Edit</button></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php
        } else {
            echo "<tr><td colspan='10'>No records found</td></tr>";
        }
        ?>
    </table>

    <div>
        <button onclick="addColumn()">Add Column</button>
    </div>
</body>

</html>
