<?php
session_start();
include 'db.php';
include 'depthead.php';

// ✅ Check if department is logged in
if (!isset($_SESSION['department'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

$dept_name = $_SESSION['department'];

// ✅ Use a prepared statement for security
$stmt = $conn->prepare("SELECT * FROM dept WHERE Name = ?");
$stmt->bind_param("s", $dept_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $dept = $result->fetch_assoc();
} else {
    echo "<p style='color:red; text-align:center;'>❌ Department details not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Department Head Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style/Profile.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="profile-card">
    <h2>🏛 Department Head Profile</h2>

    <div class="profile-item">
      <span>Name:</span>
      <span><?= htmlspecialchars($dept['Name']) ?></span>
    </div>

    <div class="profile-item">
      <span>Branch:</span>
      <span><?= htmlspecialchars($dept['Branch']) ?></span>
    </div>

    <div class="profile-item">
      <span>Address:</span>
      <span><?= htmlspecialchars($dept['Address']) ?></span>
    </div>

    <div class="profile-item">
      <span>Contact:</span>
      <span><?= htmlspecialchars($dept['Contact']) ?></span>
    </div>


    <div class="profile-item">
      <span>Role:</span>
      <span>Department Head</span>
    </div>

    <div class="text-center">
      <a href="dept_dashboard.php" class="btn btn-primary mt-3">⬅ Back to Dashboard</a>
    
    </div>
  </div>
</div>

</body>
</html>
