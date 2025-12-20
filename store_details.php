<!--<?php/*
session_start();
require('fpdf186/fpdf.php');
include 'db.php';

// ✅ Check if logged in
if (!isset($_SESSION['store']) || empty($_SESSION['store'])) {
    die("Session not set. Please login again.");
}

$store = mysqli_real_escape_string($conn, $_SESSION['store']);

// ✅ Fetch store details
$sql = "SELECT * FROM store WHERE LOWER(Name)=LOWER('$store')";
$res = mysqli_query($conn, $sql);

// ✅ Error handling
if (!$res) {
    die("Database query failed: " . mysqli_error($conn));
}

// ✅ Generate PDF if found
if (mysqli_num_rows($res) > 0) {
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->Cell(0, 10, 'Store Head Details', 0, 1, 'C');
    $pdf->Ln(10);

    while ($row = mysqli_fetch_assoc($res)) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 10, 'Name:', 1, 0, 'L');
        $pdf->Cell(130, 10, $row['Name'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Branch:', 1, 0, 'L');
        $pdf->Cell(130, 10, $row['Branch'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Address:', 1, 0, 'L');
        $pdf->Cell(130, 10, $row['Address'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Contact No:', 1, 0, 'L');
        $pdf->Cell(130, 10, $row['Contact'], 1, 1, 'L');

        $pdf->Cell(50, 10, 'Created At:', 1, 0, 'L');
        $pdf->Cell(130, 10, $row['Created_At'], 1, 1, 'L');
    }

    // ✅ Download the PDF
    $pdf->Output('D', 'store_head_details.pdf');

} else {
    echo "❌ No record found for store head: " . htmlspecialchars($store);
}*/
?>
-->


<?php
session_start();
include 'db.php';
include 'storehead.php';
// ✅ Check if user is logged in
if (!isset($_SESSION['store']) || empty($_SESSION['store'])) {
    die("<h3 style='color:red;'>⚠️ Please login first to view your profile.</h3>");
}

$store = mysqli_real_escape_string($conn, $_SESSION['store']);

// ✅ Fetch store details from database
$sql = "SELECT * FROM store WHERE LOWER(Name)=LOWER('$store')";
$res = mysqli_query($conn, $sql);

// ✅ Check query execution
if (!$res) {
    die("<h3 style='color:red;'>Database query failed: " . mysqli_error($conn) . "</h3>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Store Head Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style/Profile.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="profile-card">
    <h2>🏬 Store Head Details</h2>

    <?php
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            echo '<div class="profile-item"><span>Name:</span><span>' . htmlspecialchars($row['Name']) . '</span></div>';
            echo '<div class="profile-item"><span>Branch:</span><span>' . htmlspecialchars($row['Branch']) . '</span></div>';
            echo '<div class="profile-item"><span>Address:</span><span>' . htmlspecialchars($row['Address']) . '</span></div>';
            echo '<div class="profile-item"><span>Contact No:</span><span>' . htmlspecialchars($row['Contact']) . '</span></div>';
            echo '<div class="profile-item"><span>Created At:</span><span>' . htmlspecialchars($row['Created_At']) . '</span></div>';
        }
    } else {
        echo "<p class='no-record text-center mt-3'>❌ No record found for store head: " . htmlspecialchars($store) . "</p>";
    }
    ?>

    <div class="text-center mt-4">
      <a href="store_dashboard.php" class="btn btn-primary">⬅ Back to Dashboard</a>
    </div>
  </div>
</div>

</body>
</html>
