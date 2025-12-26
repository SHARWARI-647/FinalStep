<?php
session_start();
include 'db.php';
include 'adminhead.php';

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

$admin_name = $_SESSION['admin'];

// ✅ Fetch admin details
$stmt = $conn->prepare("SELECT * FROM admin WHERE Name=?");
$stmt->bind_param("s", $admin_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "<p style='color:red;'>Admin details not found.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="style/Profile.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <div class="profile-card">
    <h2>👤 Admin Profile</h2>
    <div class="profile-item">
      <span>Name:</span>
      <span><?= htmlspecialchars($admin['Name']) ?></span>
    </div>
    <div class="profile-item">
      <span>Branch:</span>
      <span><?= htmlspecialchars($admin['Branch']) ?></span>
    </div>
    <div class="profile-item">
      <span>Address:</span>
      <span><?= htmlspecialchars($admin['Address']) ?></span>
    </div>
    <div class="profile-item">
      <span>Contact:</span>
      <span><?= htmlspecialchars($admin['Contact']) ?></span>
    </div>
    <div class="profile-item">
      <span>Password:</span>
      <span><?= htmlspecialchars($admin['Password']) ?></span>
    </div>
    <div class="profile-item">
      <span>Role:</span>
      <span>Administrator</span>
    </div>

    <div class="text-center">
      <a href="admin_dashboard.php" class="btn btn-primary mt-3">⬅ Back to Dashboard</a>
    </div>
  </div>
</div>

</body>
</html>
