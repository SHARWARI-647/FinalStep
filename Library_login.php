<?php
include 'db.php';
include 'libraryhead.php';
session_start();

$message = ""; // For error or success messages

if (isset($_POST['login'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if library user exists
    $sql = "SELECT * FROM lib WHERE Name='$name' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['library_name'] = $name;
        header("Location: library_dashboard.php");
        exit();
    } else {
        $message = "❌ Invalid Name or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Login</title>
        <link rel="stylesheet" href="style/Login.css">
</head>
<body>
    <div class="login-container">
        <h1>Library Login</h1>
        <h3>Enter Your Credentials</h3>

        <?php if ($message != ""): ?>
            <p style="color:red; font-weight:bold;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Name" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <button type="submit" name="login" id="login">Login</button><br><br>
            <p>Don't have an account? <a href="library_register.php">Register here</a></p>
        </form>
    </div>

</body>
</html>
