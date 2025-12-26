<?php
include 'db.php';
include 'adminhead.php';

if (isset($_POST['register'])) {

    // Sanitize input to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $add = mysqli_real_escape_string($conn, $_POST['add']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ✅ Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Use correct column names (adjust if your table differs)
    $sql = "INSERT INTO admin (Name, Branch, Address, Contact, Password) 
            VALUES ('$name', '$department', '$add', '$contact', '$hashedPassword')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Registration successful! Please log in.'); window.location='admin_login.php';</script>";
    } else {
        echo "<script>alert('❌ Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Registration</title>
    <link rel="stylesheet" href="style/Regsiter.css">
    
</head>
<body>
    <div class="register_form">
        <h1>Admin Registration</h1>
        <h3>Register Here</h3>

        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="text" name="department" placeholder="Department" required><br>
            <textarea name="add" placeholder="Address" required></textarea><br>
            <input type="text" name="contact" placeholder="Contact No." required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit" name="register" id="register">Register</button>
            <p>Don't have an account? <a href="admin_login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
