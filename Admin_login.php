<?php 
include 'adminhead.php';
include 'db.php';
session_start();

if (isset($_POST['login'])) {
    $nm = mysqli_real_escape_string($conn, $_POST['nm']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ✅ Use prepared statement for security
    $sql = "SELECT * FROM admin WHERE Name=? AND Password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $nm, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // ✅ Check if admin exists
    if ($result && $result->num_rows > 0) {
        $_SESSION['admin'] = $nm;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }

    $stmt->close();
}
?>
<html>
<head>
    <title>Admin Login</title>
     <link rel="stylesheet" href="style/Login.css">
</head>

<body>
    <div class="login-container">
         <h1>Admin Login</h1>
         <h3>Enter your credentials</h3>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <form method="post">
            <input type="text" placeholder="Username" name="nm" required>
        <input type="password" placeholder="Password" name="password" required> 
            <button type="submit" name="login">Login</button>
        </form>

        <p>New Admin? <a href="admin_register.php">Register here</a></p>
    </div>
</body>
</html>
