<?php
include "db_connect.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id == 0) {
    die("<div class='alert alert-danger text-center'>Invalid ID.</div>");
}

if (isset($_POST["submit"])) {
    $date = $_POST['Entry_Date'];
    $amount_taken = (int) $_POST['amount_taken'];

    // Fetch last record's `rest_quantity`
    $latest_rest_query = "SELECT `rest_quantity` FROM `inventory_$id` ORDER BY `record_id` DESC LIMIT 1";
    $result = $con->query($latest_rest_query);
    
    $latest_rest = ($result->num_rows > 0) ? $result->fetch_assoc()['rest_quantity'] : 0;

    $rest = $latest_rest - $amount_taken;

    // Fetch `main_quantity` from `inventory` table
    $main_query = "SELECT `main_quantity` FROM `inventory` WHERE id = $id";
    $result = $con->query($main_query);

    if ($result->num_rows > 0) {
        $main_quantity = $result->fetch_assoc()['main_quantity'];
    } else {
        $main_quantity = 0; // Default value if no record found
    }

    // Insert new record
    $insert_query = "INSERT INTO `inventory_$id` (`main_table_id`, `withdrawn_date`, `taken_from`, `rest_quantity`, `main_quantity`) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($insert_query);
    $stmt->bind_param("isiii", $id, $date, $amount_taken, $rest, $main_quantity);

    if ($stmt->execute()) {
        $last_id = $stmt->insert_id;
        echo "<div class='alert alert-success text-center'>Record inserted successfully! Last ID: $last_id</div>";
    } else {
        echo "<div class='alert alert-danger text-center'>Insert failed: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <title>Insert Record</title>
</head>

<body>
    <h2 class="text-center mt-5">Insert New Record</h2>
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

            <button type="submit" name="submit" class="btn btn-primary btn-block">Insert</button>
        </form>

        <!-- Redirect Button to display.php?id=$id -->
        <div class="text-center mt-3">
            <a href="display.php?id=<?php echo $id; ?>" class="btn btn-success">View Records</a>
        </div>
    </div>
</body>
</html>
