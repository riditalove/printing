<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>QR Generation</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
</head>

<body>


  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            <h3 class="mb-0">User Information</h3>
          </div>

          <?php

          require 'phpqrcode/qrlib.php'; // Include QR Code library
          
          // Default values
          $date = "2022-02-02";
          $machine = "Rotary Label Printing Machine";
          $buyer = "";
          $client = "";
          $order_quantity = 124;
          $size = "3X3";
          $raw_material = "paper";
          $qr_image = "";

          if (isset($_POST["submit"])) {
            $date = $_POST["date"] ?? "2022-02-02";
            $machine = $_POST["machine"] ?? "";
            $buyer = $_POST["buyer"] ?? "";
            $client = $_POST["client"] ?? "";
            $order_quantity = $_POST["order_quantity"] ?? "";
            $raw_material = $_POST["raw_material"] ?? "";
            $size = $_POST["size"] ?? "";


            echo "<pre>";
            var_dump($_POST);
            echo "</pre>";
          }

          ?>

          <div class="card-body">
            <form action="" method="post">
              <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input id="date" value="<?php echo htmlspecialchars($date); ?>" class="form-control" type="date"
                  name="date" required />
              </div>

              <div class="mb-3">
                <label for="machine">Choose the machine</label>
                <select id="machine" name="machine" class="form-control">
                  <option value="">Select the Machine</option>
                  <option value="Flaxo Label Printing Machine" <?php if ($machine == "Flaxo Label Printing Machine")
                    echo "selected"; ?>>Flaxo Label Printing Machine</option>
                  <option value="Rotary Label Printing Machine" <?php if ($machine == "Rotary Label Printing Machine")
                    echo "selected"; ?>>Rotary Label Printing Machine</option>
                  <option value="MOE Printing Press Machine" <?php if ($machine == "MOE Printing Press Machine")
                    echo "selected"; ?>>MOE Printing Press Machine</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="buyer">Choose the buyer</label>
                <select id="buyer" name="buyer" class="form-control">
                  <option value="">Select the brand/buyer name</option>
                  <option value="Zara" <?php if ($buyer == "Zara")
                    echo "selected"; ?>>Zara</option>
                  <option value="TDM" <?php if ($buyer == "TDM")
                    echo "selected"; ?>>TDM</option>
                  <option value="STR" <?php if ($buyer == "STR")
                    echo "selected"; ?>>STR</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="client">Choose the client</label>
                <select id="client" name="client" class="form-control">
                  <option value="">Select the client name</option>
                  <option value="HJC Accessories Ltd" <?php if ($client == "HJC Accessories Ltd")
                    echo "selected"; ?>>HJC
                    Accessories Ltd</option>
                  <option value="Vintage Denims Ltd" <?php if ($client == "Vintage Denims Ltd")
                    echo "selected"; ?>>
                    Vintage Denims Ltd.</option>
                  <option value="Tusuka Jeans Ltd" <?php if ($client == "Tusuka Jeans Ltd")
                    echo "selected"; ?>>Tusuka
                    Jeans Ltd</option>
                  <option value="Aspire Garments Ltd" <?php if ($client == "Aspire Garments Ltd")
                    echo "selected"; ?>>
                    Aspire Garments Ltd.</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="order_quantity" class="form-label">Order Quantity (PCS)</label>
                <input id="order_quantity" value="<?php echo htmlspecialchars($order_quantity); ?>" class="form-control"
                  type="number" name="order_quantity" required />
              </div>


              
              <div class="mb-3">
                <label for="raw_material">Raw Material</label>
                <select id="raw_material" name="raw_material" class="form-control">
                  <option value="">Select the Raw Material</option>
                  <option value="Offset Plain Roll Sticker" <?php if ($raw_material == "Offset Plain Roll Sticker")
                    echo "selected"; ?>>Offset Plain Roll Sticker</option>
                  <option value="Nylon Taffeta Paper Ribon" <?php if ($raw_material == "Nylon Taffeta Paper Ribon")
                    echo "selected"; ?>>Nylon Taffeta Paper Ribon</option>
                  <option value="METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 - ART CARDe" <?php if ($raw_material== "METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 - ART CARD")
                    echo "selected"; ?>>METSÄBOARD PRIME FBB BRIGHT 355.0 G/M2 - ART CARD</option>
                </select>
              </div>


              <div class="mb-3">
                <label for="size" class="form-label">Size</label>
                <input id="size" value="<?php echo htmlspecialchars($size); ?>" class="form-control"
                  type="text" name="size" required />
              </div>



              <div class="text-center">
                <button type="submit" name="submit" class="btn btn-primary">Generate QR Code</button>
              </div>
            </form>
          </div>

          <div class="card-body text-center">
            <?php

            $PNG_TEMP_DIR = 'temp/';
            if (!file_exists($PNG_TEMP_DIR)) {
              mkdir($PNG_TEMP_DIR, 0777, true);
            }

            if (isset($_POST["submit"])) {
              $codeString = "date --\n";
              $codeString .= htmlspecialchars($_POST["date"]) . "\n";
              $codeString .= htmlspecialchars($_POST["machine"]) . "\n";
              $codeString .= htmlspecialchars($_POST["buyer"]) . "\n";
              $codeString .= htmlspecialchars($_POST["client"]) . "\n";
              $codeString .= htmlspecialchars($_POST["order_quantity"]) . "\n";
              $codeString .= htmlspecialchars($_POST["raw_material"]) . "\n";

              $filename = $PNG_TEMP_DIR . 'test' . md5($codeString) . 'date--' . date("Y-m-d_H-i-s") . '.png';
              QRcode::png($codeString, $filename, QR_ECLEVEL_L, 5, 4);
              echo '<img src="' . htmlspecialchars($PNG_TEMP_DIR . basename($filename)) . '" /></br>';

            }

            ?>

          </div>

        </div>
      </div>
    </div>
  </div>



</body>

</html>