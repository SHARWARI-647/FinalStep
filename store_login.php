<?php 
include 'storehead.php';
include 'db.php';
session_start();

if (isset($_POST['login'])) {
    $nm = mysqli_real_escape_string($conn, $_POST['nm']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ✅ Check user in database
    $sql = "SELECT * FROM store WHERE Name='$nm' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['store'] = $nm;
        header("Location: store_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Name or Password!');</script>";
    }
}
?>

<html>
<head>
    <title>Store Login</title>
       <link rel="stylesheet" href="style/Login.css">
</head>
<body>
<div class="login-container">
    <h1>Store Login</h1> <br>
    <form method="post">  
        <input type="text" placeholder="Name" name="nm" required><br><br>
        <input type="password" placeholder="Password" name="password" required><br><br> 
        <input type="submit" value="Login" name="login" id="login"><br><br> 
         <p>Don't have an account? <a href="store_register.php">Register here</a></p>
    </form>
</div>
</body>
</html>
