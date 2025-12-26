<?php
include 'stdhead.php';
include 'db.php';
session_start();

if (isset($_POST['login'])) {
    $en = trim($_POST['en']);
    $password = trim($_POST['password']);

    // ✅ Prepared statement to prevent SQL Injection
    $stmt = $conn->prepare("SELECT * FROM studentdetail WHERE Enrollnment = ?");
    $stmt->bind_param("s", $en);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // ✅ If password is hashed
        if (password_verify($password, $row['Password'])) {
            $_SESSION['enrollment'] = $row['Enrollnment'];
            $_SESSION['student_name'] = $row['Name'];
            header("Location: student_dashboard.php");
            exit();
        } 
        // ⚠️ Temporary fallback (plain-text password)
        elseif ($row['Password'] === $password) {
            $_SESSION['enrollment'] = $row['Enrollnment'];
            $_SESSION['student_name'] = $row['Name'];
            header("Location: student_dashboard.php");
            exit();
        } 
        else {
            echo "<script>alert('Incorrect password!');</script>";
        }
    } else {
        echo "<script>alert('Invalid enrollment number!');</script>";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link rel="stylesheet" href="style/Login.css">
</head>
<body>
    <div class="login-container">
        <h1>Student Login</h1>
        <form method="post" autocomplete="off">
            <input type="text" name="en" placeholder="Enrollment Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <Button type="submit" name="login" value="Login" id="login">Login</Button>
             <p>Don't have an account? <a href="std_register.php">Register here</a></p>
        </form>
    </div>
</body>
</html>
