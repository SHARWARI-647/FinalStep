<?php
include 'db.php';
include 'depthead.php';

if (isset($_POST['register'])) {
    // Get all form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $add = mysqli_real_escape_string($conn, $_POST['add']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Insert data into 'dept' table
    $sql = "INSERT INTO dept (Name, Branch, Address, Contact, Password) 
            VALUES ('$name', '$department', '$add', '$contact', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration Successful!'); window.location.href='dept_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<html>
<head>
    <title>Department Registration</title>
      <link rel="stylesheet" href="style/Regsiter.css">
</head>
<body>
<div class="register_form">
    <h1>Department Registration</h1>
    <h3>Register Here</h3>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required><br><br>
        <input type="text" name="department" placeholder="Department" required><br><br>
        <textarea name="add" placeholder="Address" required></textarea><br><br>
        <input type="text" name="contact" placeholder="Contact No." required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit" name="register" id="register">Register</button>
        <p>Don't have an account? <a href="dept_login.php">Login here</a></p>
    </form>
</div>
</body>
</html>
