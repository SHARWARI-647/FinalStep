<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>



<?php
include 'depthead.php';
// Logout function
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy session
    header("Location: dept_login.php"); // Redirect to login page
    exit();
}
?>

<!-- Logout Button -->
<form method="post" class="text-center mt-4">
    <button type="submit" name="logout" class="btn btn-danger">Logout</button>
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>

