<?php
session_start();

// ✅ If logout button is clicked
if (isset($_POST['logout'])) {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center">

    <div class="container mt-5">
        <h2 class="text-danger mb-4">Are you sure you want to log out?</h2>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger px-4">🚪 Logout</button>
            <a href="admin_dashboard.php" class="btn btn-secondary px-4">🔙 Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
