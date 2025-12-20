<?php
session_start();
include 'db.php';
include 'depthead.php';

// ✅ Check if logged in
if (!isset($_SESSION['department'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first. <a href='dept_login.php'>Login Here</a></p>";
    exit();
}

// ✅ Define available materials and their cost
$materials = [
    "Project Report" => 100,
    "Lab Coat" => 150,
    "Calculator" => 200,
    "Drawing Sheet" => 50,
    "Toolkit" => 300
];

$msg = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enroll = mysqli_real_escape_string($conn, $_POST['enroll']);
    $materials_lent = isset($_POST['material_lent']) ? implode(", ", $_POST['material_lent']) : "";
    $materials_returned = isset($_POST['material_returned']) ? implode(", ", $_POST['material_returned']) : "";

    // ✅ Handle file upload
    $receipt_file = "";
    if (!empty($_FILES['receipt']['name'])) {
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir);
        $filename = time() . "_" . basename($_FILES["receipt"]["name"]);
        $target_file = $target_dir . $filename;
        if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $target_file)) {
            $receipt_file = $filename;
        }
    }

    // ✅ Calculate pending fine (based on lent vs returned)
    $lent_cost = 0;
    $returned_cost = 0;
    foreach ($_POST['material_lent'] ?? [] as $item) {
        $lent_cost += $materials[$item];
    }
    foreach ($_POST['material_returned'] ?? [] as $item) {
        $returned_cost += $materials[$item];
    }
    $fine_amount = $lent_cost - $returned_cost;

    // ✅ Insert record into `dept_material`
    $query = "INSERT INTO dept_material (Enrollnment, Material_Lent, Material_Returned, Fine_Amount, Payment_Receipt, Updated_At) 
              VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $enroll, $materials_lent, $materials_returned, $fine_amount, $receipt_file);

    if ($stmt->execute()) {
        $msg = "✅ Material record added successfully! Pending Fine: ₹" . $fine_amount;
    } else {
        $msg = "❌ Error adding record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Add Material Record</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style\material.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5 mb-5">
    <h3 class="text-center mb-4">📦 Add Material Lending Details</h3>

    <?php if (!empty($msg)) { ?>
        <div class="alert alert-info text-center"><?= $msg ?></div>
    <?php } ?>

    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-lg">
        <div class="mb-3">
            <label for="enroll" class="form-label">Enrollment Number</label>
            <input type="text" class="form-control" name="enroll" placeholder="Enter enrollment number" required>
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
            <button type="submit" class="btn btn-success me-2">➕ Add Material</button>
            <a href="dept_dashboard.php" class="btn btn-secondary">🔙 Back</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
