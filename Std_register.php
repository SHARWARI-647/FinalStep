<?php
include 'db.php';
include 'stdhead.php';

if (isset($_POST['register'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $yr = mysqli_real_escape_string($conn, $_POST['yr']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mname = mysqli_real_escape_string($conn, $_POST['mname']);
    $add = mysqli_real_escape_string($conn, $_POST['add']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // ✅ Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Check if enrollment already exists
    $check = "SELECT * FROM studentdetail WHERE Enrollnment='$en'";
    $res = mysqli_query($conn, $check);
    if (mysqli_num_rows($res) > 0) {
        echo "<script>alert('Enrollment number already registered!');</script>";
    } else {
        // ✅ Insert data
        $sql = "INSERT INTO studentdetail 
                (Enrollnment, Name, Year, Branch, MotherName, Address, Email, Password)
                VALUES ('$en', '$name', '$yr', '$department', '$mname', '$add', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Registration Successful!');
                    window.location.href = 'std_login.php';
                  </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
      <link rel="stylesheet" href="style/Regsiter.css">
</head>

<body>
  <div class="register_form">
    <h1>Student Registration</h1>
    <h3>Register Here</h3>
    <form method="post">
      <div class="form-group">
        <input type="text" name="en" placeholder="Enrollment No." required>
      </div>

      <div class="form-group">
        <input type="text" name="name" placeholder="Full Name" required>
      </div>

      <div class="form-row">
        <input type="text" name="yr" placeholder="Year" required>
        <input type="text" name="department" placeholder="Department" required>
      </div>

      <div class="form-group">
        <input type="text" name="mname" placeholder="Mother's Name" required>
      </div>

      <div class="form-group">
        <textarea name="add" placeholder="Address" required></textarea>
      </div>

      <div class="form-group">
        <input type="email" name="email" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <button type="submit" name="register" id="register">Register</button>
      <p>Don't have an account? <a href="std_login.php">Login here</a></p>
    </form>
  </div>
</body>
</html>
