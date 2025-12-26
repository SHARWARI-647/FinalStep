<?php
include 'db.php';
include 'storehead.php';

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $department = $_POST['department'] ?? ''; // If department field is not in form, this avoids error
    $add = $_POST['add'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];

    $sql = "INSERT INTO store (Name, Branch, Address, Contact, Password) 
            VALUES ('$name', '$department', '$add', '$contact', '$password')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registration successful!'); window.location='store_login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<html>
<head>
    <title>Store Registration</title>
       <link rel="stylesheet" href="style/Regsiter.css">
</head>
<body>
    <div class="register_form">
        <h1>Store Registration</h1>
        <h3>Register Here</h3>

        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required><br><br>
            <input type="text" name="department" placeholder="Department" required><br><br>
            <textarea name="add" placeholder="Address" required></textarea><br><br>
            <input type="text" name="contact" placeholder="Contact No." required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" name="register" id="register">Register</button>
            <p>Don't have an account? <a href="store_login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
