<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Generation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="mb-0">User Information</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input id="date" value="<?= isset($date) ? htmlspecialchars($date) : ''; ?>"
                                    class="form-control" type="date" name="date" required>
                            </div>

                            <div class="mb-3">
                                <label for="machine">Choose the machine</label>
                                <select id="machine" name="machine" class="form-control" required>
                                    <option value="">Select the Machine</option>
                                    <option value="Flaxo Label Printing Machine" <?= isset($machine) && $machine == "Flaxo Label Printing Machine" ? "selected" : ""; ?>>Flaxo Label Printing Machine
                                    </option>
                                    <option value="Rotary Label Printing Machine" <?= isset($machine) && $machine == "Rotary Label Printing Machine" ? "selected" : ""; ?>>Rotary Label
                                        Printing Machine</option>
                                    <option value="MOE Printing Press Machine" <?= isset($machine) && $machine == "MOE Printing Press Machine" ? "selected" : ""; ?>>MOE Printing Press Machine
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="provider">Choose the provider</label>
                                <select id="provider" name="provider" class="form-control" required>
                                    <option value="">Select the provider name</option>
                                    <option value="HJC Accessories Ltd" <?= isset($provider) && $provider == "HJC Accessories Ltd" ? "selected" : ""; ?>>HJC Accessories Ltd</option>
                                    <option value="Vintage Denims Ltd" <?= isset($provider) && $provider == "Vintage Denims Ltd" ? "selected" : ""; ?>>Vintage Denims Ltd</option>
                                    <option value="Tusuka Jeans Ltd" <?= isset($provider) && $provider == "Tusuka Jeans Ltd" ? "selected" : ""; ?>>Tusuka Jeans Ltd</option>
                                    <option value="Aspire Garments Ltd" <?= isset($provider) && $provider == "Aspire Garments Ltd" ? "selected" : ""; ?>>Aspire Garments Ltd</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="main_quantity" class="form-label">Order Quantity (PCS)</label>
                                <input id="main_quantity"
                                    value="<?= isset($main_quantity) ? htmlspecialchars($main_quantity) : ''; ?>"
                                    class="form-control" type="number" name="main_quantity" required>
                            </div>

                            <div class="mb-3">
                                <label for="raw_material">Raw Material</label>
                                <select id="raw_material" name="raw_material" class="form-control" required>
                                    <option value="">Select the Raw Material</option>
                                    <option value="Offset Plain Roll Sticker" <?= isset($raw_material) && $raw_material == "Offset Plain Roll Sticker" ? "selected" : ""; ?>>Offset Plain
                                        Roll Sticker</option>
                                    <option value="Nylon Taffeta Paper Ribon" <?= isset($raw_material) && $raw_material == "Nylon Taffeta Paper Ribon" ? "selected" : ""; ?>>Nylon Taffeta
                                        Paper Ribon</option>
                                    <option value="METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 - ART CARD"
                                        <?= isset($raw_material) && $raw_material == "METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 - ART CARD" ? "selected" : ""; ?>>METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 -
                                        ART CARD</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="size" class="form-label">Size</label>
                                <input id="size" value="<?= isset($size) ? htmlspecialchars($size) : ''; ?>"
                                    class="form-control" type="text" name="size" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-primary">Generate QR Code</button>
                            </div>
                        </form>
                    </div>

                    <div class="card-body text-center">
                        <?php
                        require 'phpqrcode/qrlib.php'; // Include QR Code library
                        require 'db_connect.php';

                        $PNG_TEMP_DIR = 'temp/';
                        if (!file_exists($PNG_TEMP_DIR)) {
                            mkdir($PNG_TEMP_DIR, 0777, true);
                        }

                        if (isset($_POST["submit"])) {
                            $id = rand(1, 9999);
                            $url = "http://192.168.4.248/printing/display.php?id=" . $id;

                            // Get values from the form
                            $date = $_POST["date"];
                            $machine = $_POST["machine"];
                            $provider = $_POST["provider"];
                            $main_quantity = $_POST["main_quantity"];
                            $raw_material = $_POST["raw_material"];
                            $size = $_POST["size"];

                            // Secure database insertion using prepared statements
                            $stmt = $con->prepare("INSERT INTO `inventory` (`id`, `date`, `machine`, `provider`, `main_quantity`, `size`, `raw_material`) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("isssiss", $id, $date, $machine, $provider, $main_quantity, $size, $raw_material);

                            if ($stmt->execute()) {
                                echo "<p class='text-success'>Data inserted to inventory successfully!</p>";


                                // Get the unique ID of the student
                        
                                // Create a dynamic table for the student using their ID as the table name
                                $tableName = "inventory_" . $id;
                                $createTableQuery = "CREATE TABLE $tableName (
                                        record_id INT AUTO_INCREMENT PRIMARY KEY,
                                        main_table_id INT,
                                        withdrawn_date DATE,
                                        main_quantity INT,
                                        taken_from INT,
                                        rest_quantity INT
                                    )";

                                if (mysqli_query($con, $createTableQuery)) {
                                    echo "Inventory registered successfully! Table '$tableName' created for storing records.";

                                    $sql = "INSERT INTO inventory_$id( `main_quantity`,`main_table_id` ) VALUES ('$main_quantity',$id)";
                                    if(mysqli_query($con,$sql))
                                    {
                                        echo "Data inserted for inventory!";
                                    }
                                    else
                                    {
                                        echo "sorry";
                                    }


                                } else {
                                    echo "Error creating table: " . mysqli_error($con);
                                }


                            } else {
                                echo "<p class='text-danger'>Error inserting data: " . $stmt->error . "</p>";
                            }
                            $stmt->close();


                            // Generate the QR Code
                            $filename = $PNG_TEMP_DIR . 'qr_' . md5($url) . '.png';
                            QRcode::png($url, $filename, QR_ECLEVEL_L, 4, 2);

                            echo '<h4>Scan the QR Code</h4>';
                            echo '<img src="' . htmlspecialchars($filename) . '" class="img-fluid" />';
                            echo '<br><a href="' . $url . '" target="_blank">Go to Link</a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>