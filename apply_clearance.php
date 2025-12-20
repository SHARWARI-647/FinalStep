<?php
session_start();
include 'db.php';

// ✅ Check login
if (!isset($_SESSION['enrollment'])) {
    echo "<h3 style='color:red; text-align:center;'>Please log in first.</h3>";
    exit();
}

$en = $_SESSION['enrollment'];

// ✅ Fetch student details
$std = mysqli_query($conn, "SELECT * FROM studentdetail WHERE Enrollnment='$en'");
$stdd = mysqli_fetch_assoc($std);
$name = $stdd['Name'];
$branch = $stdd['Branch'];
$year = $stdd['Year'];

$message = '';

if (isset($_POST['apply'])) {
    // ✅ Check if already applied
    $check_std = mysqli_query($conn, "SELECT * FROM student_clearance WHERE Enrollnment='$en'");

    if (mysqli_num_rows($check_std) > 0) {
        $message = "<div class='alert alert-warning'>You have already applied for clearance!</div>";
    } else {
        // ✅ Insert into related clearance tables
        mysqli_query($conn, "INSERT INTO student_clearance (Enrollnment, dept_st, Library_st, store_st, final_st)
                             VALUES ('$en', 'Pending', 'Pending', 'Pending', 'Pending')");

        mysqli_query($conn, "INSERT INTO department_clearance (Enrollnment, Status, Name, Year, Branch)
                             VALUES ('$en', 'Pending', '$name', '$year', '$branch')");

        mysqli_query($conn, "INSERT INTO library_clearance (Enrollnment, Status, Name, Year, Branch)
                             VALUES ('$en', 'Pending', '$name', '$year', '$branch')");

        mysqli_query($conn, "INSERT INTO store_clearance (Enrollnment, Status, Name, Year, Branch)
                             VALUES ('$en', 'Pending', '$name', '$year', '$branch')");

        mysqli_query($conn, "INSERT INTO admin_clearance (Enrollnment, lib_st, store_st, dept_st, Status, Name, Year, Branch)
                             VALUES ('$en', 'Pending', 'Pending', 'Pending', 'Pending', '$name', '$year', '$branch')");

        $message = "<div class='alert alert-success'>🎉 Clearance request submitted successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply for Clearance</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #0077b6, #90e0ef);
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.container {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    text-align: center;
    width: 400px;
}
h2 {
    color: #0077b6;
    margin-bottom: 20px;
}
button {
    background-color: #0077b6;
    color: white;
    border: none;
    padding: 10px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}
button:hover {
    background-color: #005f8d;
}
.alert {
    margin-top: 15px;
    font-weight: bold;
}
</style>
</head>

<body>
<div class="container">
    <h2>Apply for Clearance</h2>
    <p><b>Enrollment:</b> <?= htmlspecialchars($en) ?></p>
    <p><b>Name:</b> <?= htmlspecialchars($name) ?></p>
    <p><b>Branch:</b> <?= htmlspecialchars($branch) ?></p>
    <p><b>Year:</b> <?= htmlspecialchars($year) ?></p>

    <form method="post">
        <button type="submit" name="apply">Apply for Clearance</button>
    </form>

    <?= $message ?>
</div>
</body>
</html>
