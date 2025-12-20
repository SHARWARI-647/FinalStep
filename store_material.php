<?php
session_start();
include 'db.php';

// Only allow logged-in department staff
if (!isset($_SESSION['store'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

// Material master list with prices
$materials = [
    "Table" => 500,
    "Chair" => 300,
    "Duster" => 50,
    "Stamp" => 150,
    "Marker" => 20,
    "Project paper" =>20,
    "Assignment page" =>50,
    "Dress" =>600,
    "Manual" =>150,
    "File" =>10,
    "Drawing Book" =>70,
    "Graphics Material" =>200
];

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll = $_POST['enroll'];
    $lent = $_POST['material_lent'] ?? [];
    $returned = $_POST['material_returned'] ?? [];

    // Calculate fine for unreturned items
    $unreturned = array_diff($lent, $returned);
    $fine_amount = 0;
    foreach ($unreturned as $item) {
        $fine_amount += $materials[$item];
    }

    $material_lent = implode(", ", $lent);
    $material_returned = implode(", ", $returned);

    // Handle receipt upload
    $receipt_name = "";
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
        $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
        if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
            $receipt_name = "receipt_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            move_uploaded_file($_FILES['receipt']['tmp_name'], "uploads/" . $receipt_name);
        } else {
            $msg = "❌ Invalid file format.";
        }
    }

    // Insert into database
    if ($msg === "") {
        $query = "INSERT INTO store_material 
            (Enrollnment, Material_Lent, Material_Returned, Fine_Amount, Payment_Receipt) 
            VALUES ('$enroll', '$material_lent', '$material_returned', '$fine_amount', '$receipt_name')";

        if (mysqli_query($conn, $query)) {
            $msg = "✅ Material record added successfully. Fine calculated: ₹$fine_amount";
        } else {
            $msg = "❌ Database error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Material Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <link href="style\material.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center mb-4">📦 Add Material Lending Details</h3>

    <?php if (!empty($msg)) { ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-lg">
        <div class="mb-3">
            <label for="enroll" class="form-label">Enrollment Number</label>
            <input type="text" class="form-control" name="enroll" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Materials Lent</label>
            <?php foreach ($materials as $item => $cost): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="material_lent[]" value="<?= $item ?>" id="lent_<?= $item ?>">
                    <label class="form-check-label" for="lent_<?= $item ?>">
                        <?= $item ?> (₹<?= $cost ?>)
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Select Materials Returned</label>
            <?php foreach ($materials as $item => $cost): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="material_returned[]" value="<?= $item ?>" id="returned_<?= $item ?>">
                    <label class="form-check-label" for="returned_<?= $item ?>">
                        <?= $item ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="receipt" class="form-label">Upload Payment Receipt (Image)</label>
            <input type="file" class="form-control" name="receipt" accept="image/*">
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-success">➕ Add Material</button>
            <a href="store_dashboard.php" class="btn btn-secondary">🔙 Back</a>
        </div>
    </form>
</div>
</body>
</html>
