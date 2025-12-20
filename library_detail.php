<?php
session_start();
include 'db.php';
include 'libraryhead.php'; // include your header/navigation for library

// ✅ Check if library is logged in
if (!isset($_SESSION['library_name'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

$lib_name = $_SESSION['library_name'];

// ✅ Secure SQL query using prepared statement
$stmt = $conn->prepare("SELECT * FROM lib WHERE Name = ?");
$stmt->bind_param("s", $lib_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $lib = $result->fetch_assoc();
} else {
    echo "<p style='color:red; text-align:center;'>❌ Library details not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Head Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style/Profile.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="profile-card">
    <h2>📚 Library Head Profile</h2>

    <div class="profile-item">
      <span>Name:</span>
      <span><?= htmlspecialchars($lib['Name']) ?></span>
    </div>

    <div class="profile-item">
      <span>Branch:</span>
      <span><?= htmlspecialchars($lib['Branch']) ?></span>
    </div>

    <div class="profile-item">
      <span>Address:</span>
      <span><?= htmlspecialchars($lib['Address']) ?></span>
    </div>

    <div class="profile-item">
      <span>Contact:</span>
      <span><?= htmlspecialchars($lib['Contact']) ?></span>
    </div>

    <div class="profile-item">
      <span>Created At:</span>
      <span><?= htmlspecialchars($lib['Created_At']) ?></span>
    </div>

    <div class="profile-item">
      <span>Role:</span>
      <span>Library Head</span>
    </div>

    <div class="text-center">
      <a href="library_dashboard.php" class="btn btn-primary mt-3">⬅ Back to Dashboard</a>
    </div>
  </div>
</div>

</body>
</html>
