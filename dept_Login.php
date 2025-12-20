<?php
session_start();
include 'db.php';
include 'depthead.php';

// Handle login
if (isset($_POST['login'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM dept WHERE Name='$name' AND Password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['department'] = $row['Name'];
        header("Location: dept_dashboard.php");
        exit();
    } else {
        $error = "Invalid department name or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Login</title>
    <link rel="stylesheet" href="style/Login.css">
</head>
<body>
    <div class="login-container">
        <h1>Department Login</h1>
        <h3>Enter your credentials</h3>

        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post">
            <input type="text" name="name" placeholder="Department Name" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p>New Department? <a href="dept_register.php">Register here</a></p>
    </div>
</body>
</html>
