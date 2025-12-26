<?php
include 'db.php';
include 'libraryhead.php';
$message = ""; // To display success/error messages

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if name already exists
    $check = "SELECT * FROM lib WHERE Name='$name'";
    $check_run = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_run) > 0) {
        $message = "⚠️ User already registered!";
    } else {
        $sql = "INSERT INTO lib (Name, Branch, Address, Contact, Password) 
                VALUES ('$name', '$department', '$address', '$contact', '$password')";
        if (mysqli_query($conn, $sql)) {
            $message = "✅ Registration successful! <a href='library_login.php'>Login here</a>";
        } else {
            $message = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Registration</title>
    <link rel="stylesheet" href="style/Regsiter.css">
</head>
<body>
    <div class="register_form">
        <h1>Library Registration</h1>
        <h3>Register Here</h3>

        <?php if ($message != ""): ?>
            <p style="font-weight:bold; color:<?php echo (strpos($message, '✅') !== false) ? 'green' : 'red'; ?>;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Full Name" required><br><br>
            <input type="text" name="department" placeholder="Department" required><br><br>
            <textarea name="address" placeholder="Address" required></textarea><br><br>
            <input type="text" name="contact" placeholder="Contact No." required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" name="register" id="register">Register</button><br><br>
            <p>Already have an account? <a href="library_login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>
