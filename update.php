<?php
include "db_connect.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$idr = isset($_GET['idr']) ? (int) $_GET['idr'] : 0;

// Fetch existing record
$query = "SELECT * FROM `inventory_$id` WHERE `record_id` = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $idr);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("<div class='alert alert-danger text-center'>Record not found.</div>");
}

// Handling form submission
if (isset($_POST["submit"])) {
    $date = $_POST['Entry_Date'];  // Getting data from form input
    $amount_taken = (int) $_POST['amount_taken']; // Ensuring it's an integer

    if ($idr == 1) {
        $main_quantity = (int) $row['main_quantity'];
        $rest = $main_quantity - $amount_taken;

        $update_query = "UPDATE `inventory_$id` SET `withdrawn_date` = ?, `taken_from` = ?, `rest_quantity` = ? WHERE `record_id` = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("ssii", $date, $amount_taken, $rest, $idr);
    } else {
        $rest = (int) $row['rest_quantity'] - $amount_taken;

        $update_query = "UPDATE `inventory_$id` SET `withdrawn_date` = ?, `taken_from` = ?, `rest_quantity` = ? WHERE `record_id` = ?";
        $update_stmt = $con->prepare($update_query);
        $update_stmt->bind_param("ssii", $date, $amount_taken, $rest, $idr);
    }

    // Execute and check result
    if ($update_stmt->execute()) {
        echo "<div class='alert alert-success text-center'>Data updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Update failed: " . $update_stmt->error . "</div>";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Update</title>
</head>

<body>
    <h2 class="text-center mt-5">Update Record</h2>
    <div class="container">
        <form method="POST">
            <div class="form-group mt-5">
                <label for="Entry_Date">Entry Date of Planning</label>
                <input type="date" id="Entry_Date" name="Entry_Date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="amount_taken">Amount Taken</label>
                <input type="number" id="amount_taken" name="amount_taken" placeholder="The amount taken"
                    class="form-control" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary btn-block">Update</button>
        </form>

        <!-- Redirect Button to display.php?id=$id -->
        <div class="text-center mt-3">
            <a href="display.php?id=<?php echo $id; ?>" class="btn btn-success">View Records</a>
        </div>
    </div>
</body>

</html>