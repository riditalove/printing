<?php
if (isset($_GET['id'])) {
    $id = (int) htmlspecialchars($_GET['id']); // Ensure it's an integer

    echo "<h2 class='text-center mt-3'>Order Details for ID: $id</h2>";
} else {
    die("<h2 class='text-center mt-3 text-danger'>No ID Provided</h2>");
}

// Database connection
include "db_connect.php";

// Sanitize table name dynamically
$table_name = "inventory_" . $id;
$table_name = mysqli_real_escape_string($con, $table_name);

// Query to fetch records
$q = "SELECT * FROM `$table_name`";
$r = mysqli_query($con, $q);

if (!$r) {
    die("<div class='text-center text-danger'>Error: " . mysqli_error($con) . "</div>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Order Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Display</h2>
        <br>

        <!-- Add Button -->
        <div class="text-right mb-3">
            <a href="add.php?id=<?= $id ?>" class="btn btn-success">Add Record</a>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">SI No</th>
                    <th scope="col">Main Table No</th>
                    <th scope="col">Entry Date</th>
                    <th scope="col">Main Quantity</th>
                    <th scope="col">Amount Taken</th>
                    <th scope="col">Rest Quantity</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($r)) {
                    $idr = $row['record_id'];
                   
                    echo '<tr>
                        <th scope="row">' . htmlspecialchars($row['record_id']) . '</th>
                        <td>' . htmlspecialchars($row['main_table_id']) . '</td>
                        <td>' . htmlspecialchars($row['withdrawn_date']) . '</td>
                        <td>' . htmlspecialchars($row['main_quantity']) . '</td>
                        <td>' . htmlspecialchars($row['taken_from']) . '</td>
                        <td>' . htmlspecialchars($row['rest_quantity']) . '</td>
                        <td>
                            <a href="update.php?id=' . $id . '&idr=' . $idr . '" class="btn btn-primary btn-sm text-white">Update</a>
                        </td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
