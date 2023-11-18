<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle add form submission
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $regisPMES = floatval($_POST['regisPMES']); // Convert to number
    $loanAmort = floatval($_POST['loanAmort']); // Convert to number
    $interestInc = floatval($_POST['interestInc']); // Convert to number
    $capBuildup = floatval($_POST['capBuildup']); // Convert to number
    $total = $regisPMES + $loanAmort + $interestInc + $capBuildup; // Calculate total

    $stmt = $pdo->prepare("INSERT INTO tslr (lastName, firstName, middleName, regisPMES, loanAmort, interestInc, capBuildup, total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$lastName, $firstName, $middleName, $regisPMES, $loanAmort, $interestInc, $capBuildup, $total]);
}

// Retrieve records from database
$stmt = $pdo->query("SELECT * FROM tslr");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SLR Records</title>
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
    <h1>SLR Records</h1>

    <h2>Records</h2>
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
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['lastName'] ?></td>
                <td><?= $row['firstName'] ?></td>
                <td><?= $row['middleName'] ?></td>
                <td><?= $row['regisPMES'] ?></td>
                <td><?= $row['loanAmort'] ?></td>
                <td><?= $row['interestInc'] ?></td>
                <td><?= $row['capBuildup'] ?></td>
                <td><?= $row['regisPMES'] + $row['loanAmort'] + $row['interestInc'] + $row['capBuildup'] ?></td>
                <td><button onclick="editRecord(<?= $row['id'] ?>)">Edit</button></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Add a new button to trigger the addColumn() function -->
    <div>
        <button onclick="addColumn()">Add Column</button>
    </div>

</body>

<script>
     function editRecord(rowIndex) {
        const table = document.getElementById('recordsTable');
        const row = table.rows[rowIndex];
        const cells = row.cells;

        for (let i = 1; i < cells.length - 2; i++) { // Exclude the last editable column (Total)
            const value = cells[i].textContent;
            cells[i].innerHTML = `<input type="text" class="editable" value="${value}" oninput="handleInputChange(this, ${rowIndex}, ${i})">`;
        }

        const cellAction = cells[cells.length - 1];
        cellAction.innerHTML = `<button onclick="saveRecord(${rowIndex})">Save</button>`;
    }

    function addColumn() {
        const table = document.getElementById('recordsTable');
        const lastCellIndex = table.rows[0].cells.length - 1; // Exclude the last cell (Action)

        // Limit the number of cells to be added to 10
        const numCellsToAdd = Math.min(10, lastCellIndex);

        // Add a new row below the table
        const newRow = table.insertRow(table.rows.length); // Insert at the end of the table

        // Add a new cell in the new row for each column (up to 10 cells)
        for (let i = 0; i < numCellsToAdd; i++) {
            const newCell = newRow.insertCell(i);

            // Check if the current column is ID, Total, or Action
            if (i === 0 || i === lastCellIndex - 1 || i === lastCellIndex) {
                newCell.textContent = 'Auto Generate'; // Set non-editable text for ID, Total, and Action
            } else {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = ''; // Set the default value here
                input.setAttribute('oninput', `handleInputChange(this, ${table.rows.length - 1}, ${i})`);
                newCell.appendChild(input);
            }
        }

        const saveButtonCell = newRow.insertCell(numCellsToAdd); // Add a new cell for the "Save" button
        saveButtonCell.innerHTML = `<button onclick="saveNewColumn(${table.rows.length - 1})">Save</button>`;
    }

    function handleInputChange(input, rowIndex, columnIndex) {
        // Handle input changes and update the data array or perform other actions as needed
        // In this example, we'll update the data array in the same format as saveNewColumn.php
        const data = {
            regisPMES: '',
            loanAmort: '',
            interestInc: '',
            capBuildup: '',
        };

        switch (columnIndex) {
            case 1: // RegisPMES column
                data.regisPMES = input.value;
                break;
            case 2: // Loan Amortization column
                data.loanAmort = input.value;
                break;
            case 3: // Interest Income column
                data.interestInc = input.value;
                break;
            case 4: // Capital Buildup column
                data.capBuildup = input.value;
                break;
            default:
                break;
        }

        // You can perform additional actions here if needed

        // Log the updated data array (you can remove this line in production)
        console.log(data);
    }

    function saveNewColumn(rowIndex) {
        const table = document.getElementById('recordsTable');
        const row = table.rows[rowIndex];
        const cells = row.cells;

        const data = [];
        for (let i = 1; i < cells.length - 2; i++) { // Exclude the last editable column (Action)
            const input = cells[i].querySelector('input');
            data.push(input ? input.value : ''); // Check if an input element exists
        }

        // Send the data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'saveNewColumn.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response); // Log the response from the server

                // Update the row in the table with the response data
                for (let i = 1; i < cells.length - 1; i++) {
                    cells[i].textContent = response[i];
                }

                const cellAction = cells[cells.length - 1];
                cellAction.innerHTML = `<button onclick="editRecord(${rowIndex})">Edit</button>`;
            }
        };
        xhr.send('data=' + JSON.stringify(data) + '&id=' + rowIndex);
    }

    function saveRecord(rowIndex) {
        const table = document.getElementById('recordsTable');
        const row = table.rows[rowIndex];
        const cells = row.cells;
        const editableCells = row.querySelectorAll('.editable');
        const data = Array.from(editableCells).map(cell => cell.value);

        // Send the data to the server using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'saveRecord.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                console.log(response); // Log the response from the server

                // Update the row in the table with the response data
                for (let i = 1; i < cells.length - 1; i++) {
                    cells[i].textContent = response[i];
                }

                const cellAction = cells[cells.length - 1];
                cellAction.innerHTML = `<button onclick="editRecord(${rowIndex})">Edit</button>`;
            }
        };
        xhr.send('data=' + JSON.stringify(data) + '&id=' + rowIndex); // Pass the rowIndex to saveRecord.php
    }
    </script>

</html>
