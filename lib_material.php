<?php
session_start();
include 'db.php';

// ✅ Only allow logged-in library head
if (!isset($_SESSION['library_name'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

// ✅ Include library header
include 'libraryhead.php';

// ✅ Material list with prices
$materials = [
    "Nirali" => 500,
    "Technowledge" => 300,
    "Techmax" => 50,
    "Tecneo" => 150,
    "Magazines" => 50,
    "Reference Book" => 500
];

$msg = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll = mysqli_real_escape_string($conn, $_POST['enroll']);
    $lent = $_POST['material_lent'] ?? [];
    $returned = $_POST['material_returned'] ?? [];

    // ✅ Calculate fine for unreturned items
    $unreturned = array_diff($lent, $returned);
    $fine_amount = 0;
    foreach ($unreturned as $item) {
        $fine_amount += $materials[$item];
    }

    $material_lent = implode(", ", $lent);
    $material_returned = implode(", ", $returned);

    // ✅ Handle receipt upload
    $receipt_name = "";
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $receipt_name = "receipt_" . time() . "_" . rand(1000, 9999) . "." . $ext;
            move_uploaded_file($_FILES['receipt']['tmp_name'], "uploads/" . $receipt_name);
        } else {
            $msg = "❌ Invalid file format. Only JPG, PNG, or GIF allowed.";
        }
    }

    // ✅ Insert record into database
    if ($msg === "") {
        $query = "INSERT INTO library_material 
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
    <title>Add Library Material Record</title>
    <link href="style\material.css" rel="stylesheet">
</head>
<body class="container">

<div class="form-container">
    <h2>📚 Add Library Material Details</h2>

    <?php if (!empty($msg)) { ?>
        <div class="message"><?= htmlspecialchars($msg) ?></div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data">
        <label>Enrollment Number</label>
        <input type="text" name="enroll" placeholder="Enter enrollment number" required><br><br>

        <h4>Select Materials Lent</h4>
        <?php foreach ($materials as $item => $cost): ?>
            <label>
                <input type="checkbox" name="material_lent[]" value="<?= htmlspecialchars($item) ?>">
                <?= htmlspecialchars($item) ?> (₹<?= $cost ?>)
            </label><br>
        <?php endforeach; ?>
        <br>

        <h4>Select Materials Returned</h4>
        <?php foreach ($materials as $item => $cost): ?>
            <label>
                <input type="checkbox" name="material_returned[]" value="<?= htmlspecialchars($item) ?>">
                <?= htmlspecialchars($item) ?>
            </label><br>
        <?php endforeach; ?>
        <br>

        <label>Upload Payment Receipt (Image)</label>
        <input type="file" name="receipt" accept="image/*"><br><br>

        <div class="btn-group">
            <button type="submit" id="register">➕ Add Record</button>
            <a href="library_dashboard.php" class="btn-back">🔙 Back to Dashboard</a>
        </div>
    </form>
</div>

</body>
</html>
