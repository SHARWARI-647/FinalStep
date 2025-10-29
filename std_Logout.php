<?php
session_start(); // Always start session before using it
include 'stdhead.php';

// ✅ Handle logout before any HTML output
if (isset($_POST['logout'])) {
    session_unset();    // Clear session variables
    session_destroy();  // Destroy session
    header("Location: Std_login.php"); // Redirect to login
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-center" style="background:#f5f9ff;">

<div class="container mt-5">
    <h2 class="text-primary mb-4">Student Dashboard</h2>
    <form method="post">
        <button type="submit" name="logout" class="btn btn-danger btn-lg">Logout</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
