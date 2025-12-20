<?php
session_start();
include 'db.php';

if (!isset($_SESSION['enrollment'])) {
    echo "Please log in first.";
    exit();
}

$enrollment = $_SESSION['enrollment'];

if (isset($_POST['apply'])) {

    // Optional file upload (TC PDF)
    $pdf_path = null;
    if (isset($_FILES['TC_form']) && $_FILES['TC_form']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";  // Make sure this folder exists and is writable
        $file_name = basename($_FILES['TC_form']['name']);
        $pdf_path = $upload_dir . $file_name;

        move_uploaded_file($_FILES['TC_form']['tmp_name'], $pdf_path);
    }

    // Fetch statuses
    $check_lib = mysqli_query($conn, "SELECT Status FROM library_clearance WHERE Enrollnment='$enrollment'");
    $check_dept = mysqli_query($conn, "SELECT Status FROM department_clearance WHERE Enrollnment='$enrollment'");
    $check_store = mysqli_query($conn, "SELECT Status FROM store_clearance WHERE Enrollnment='$enrollment'");

    // Fetch student info
    $std = mysqli_query($conn, "SELECT * FROM studentdetail WHERE Enrollnment='$enrollment'");
    $stdd = mysqli_fetch_assoc($std);

    if (!$stdd) {
        echo "Student record not found.";
        exit();
    }

    $name = $stdd['Name'];
    $branch = $stdd['Branch'];
    $year = $stdd['Year'];

    // Fetch clearance statuses safely
    $lib_status = ($check_lib && mysqli_num_rows($check_lib) > 0) ? mysqli_fetch_assoc($check_lib)['Status'] : 'Pending';
    $dept_status = ($check_dept && mysqli_num_rows($check_dept) > 0) ? mysqli_fetch_assoc($check_dept)['Status'] : 'Pending';
    $store_status = ($check_store && mysqli_num_rows($check_store) > 0) ? mysqli_fetch_assoc($check_store)['Status'] : 'Pending';

    // Insert only if all are cleared
    if ($lib_status === 'Cleared' && $dept_status === 'Cleared' && $store_status === 'Cleared') {

        $query = "INSERT INTO admin_clearance (Enrollnment, Name, lib_st, dept_st, store_st, clearance_pdf, Status, Year, Branch)
                  VALUES ('$enrollment', '$name', '$lib_status', '$dept_status', '$store_status', '$pdf_path', 'Pending', '$year', '$branch')";

        if (mysqli_query($conn, $query)) {
            echo "<p style='color:green; text-align:center;'>TC request submitted successfully!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . mysqli_error($conn) . "</p>";
        }

    } else {
        echo "<p style='color:red; text-align:center;'>You can only apply for TC after all clearances are marked as 'Cleared'.</p>";
    }
}
?>
