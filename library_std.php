<?php
session_start();
include 'db.php';

// ✅ Check session
if (!isset($_SESSION['library_name'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

$en = '';
$check_lib = null;
$message = ''; // For alert message
$alertType = ''; // Bootstrap alert type

// ✅ Handle "View Profile"
if (isset($_POST['view'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $check_lib = mysqli_query($conn, "SELECT * FROM library_material WHERE Enrollnment='$en'");
}

// ✅ Handle Approve/Reject button
if (isset($_POST['update_status_row'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['update_status_row']);

    // ✅ Update in library_material (if Id exists)
    if (isset($_POST['Id']) && $_POST['Id'] !== '') {
        $rowId = intval($_POST['Id']);
        mysqli_query($conn, "UPDATE library_material SET Status='$newStatus' WHERE Id=$rowId");
    }

    // ✅ Determine latest or default status
    $latest = mysqli_query($conn, "SELECT Status FROM library_material WHERE Enrollnment='$en' ORDER BY Id DESC LIMIT 1");
    if ($latest && mysqli_num_rows($latest) > 0) {
        $r = mysqli_fetch_assoc($latest);
        $s = $r['Status'];
    } else {
        $s = $newStatus;
    }

    // ✅ Update Library clearance and Student clearance
    mysqli_query($conn, "UPDATE library_clearance SET Status='$s' WHERE Enrollnment='$en'");
    mysqli_query($conn, "UPDATE student_clearance SET Library_st='$s' WHERE Enrollnment='$en'");

    // ✅ Handle admin_clearance table (insert or update)
    $check_admin = mysqli_query($conn, "SELECT * FROM admin_clearance WHERE Enrollnment='$en'");
    if (mysqli_num_rows($check_admin) > 0) {
        mysqli_query($conn, "UPDATE admin_clearance SET lib_st='$s' WHERE Enrollnment='$en'");
    } else {
        // Get student info
        $stu = mysqli_query($conn, "SELECT Name, Branch, Year FROM studentdetail WHERE Enrollnment='$en'");
        $stu_data = mysqli_fetch_assoc($stu);
        $name = $stu_data['Name'];
        $branch = $stu_data['Branch'];
        $year = $stu_data['Year'];

        mysqli_query($conn, "INSERT INTO admin_clearance (Enrollnment, Name, Branch, Year, lib_st)
                             VALUES ('$en', '$name', '$branch', '$year', '$s')");
    }

    // ✅ Alert message
    if ($s === 'Approved') {
        $message = "✅ Library clearance approved successfully for Enrollment: $en.";
        $alertType = "success";
    } elseif ($s === 'Rejected') {
        $message = "❌ Library clearance rejected for Enrollment: $en.";
        $alertType = "danger";
    } else {
        $message = "ℹ️ Library clearance status updated for Enrollment: $en.";
        $alertType = "info";
    }

    // ✅ Reload table
    $check_lib = mysqli_query($conn, "SELECT * FROM library_material WHERE Enrollnment='$en'");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Library Clearance</title>
     <link href="style\clearance.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h3 class="mb-4 text-center">📚 Library Clearance Details for 
        <span class="text-primary"><?= htmlspecialchars($en) ?></span>
    </h3>

    <!-- ✅ Alert Message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= htmlspecialchars($alertType) ?> text-center">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <table class="table table-bordered table-striped text-center align-middle shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Material Lent</th>
                <th>Material Returned</th>
                <th>Fine Amount</th>
                <th>Payment Receipt</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($check_lib && mysqli_num_rows($check_lib) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($check_lib)): ?>
                <?php
                    $status = $row['Status'] ?? 'Pending';
                    $badge = match ($status) {
                        "Approved" => "success",
                        "Rejected" => "danger",
                        "Pending" => "warning",
                        default => "secondary"
                    };
                    $hasMaterial = !empty($row['Material_Lent']);
                ?>
                <tr>
                    <td><?= $row['Id'] ?></td>
                    <td><?= htmlspecialchars($row['Material_Lent']) ?></td>
                    <td><?= htmlspecialchars($row['Material_Returned']) ?></td>
                    <td>₹<?= htmlspecialchars($row['Fine_Amount']) ?></td>
                    <td>
                        <?php if (!empty($row['Payment_Receipt'])): ?>
                            <?php $filePath = "uploads/" . htmlspecialchars($row['Payment_Receipt']); ?>
                            <a href="<?= $filePath ?>" target="_blank">View</a><br>
                            <img src="<?= $filePath ?>" height="80" alt="Receipt">
                        <?php else: ?>
                            <span class="text-muted">Not Uploaded</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="Id" value="<?= $row['Id'] ?>">
                            <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                            <button type="submit" name="update_status_row" value="Approved" class="btn btn-success btn-sm mb-1">✅ Approve</button>
                            <button type="submit" name="update_status_row" value="Rejected" class="btn btn-danger btn-sm">❌ Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-muted">
                    No materials lent. Student eligible for clearance.
                    <form method="post" class="mt-3">
                        <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                        <button type="submit" name="update_status_row" value="Approved" class="btn btn-success btn-sm">
                            ✅ Approve Clearance
                        </button>
                        <button type="submit" name="update_status_row" value="Rejected" class="btn btn-danger btn-sm">
                            ❌ Reject Clearance
                        </button>
                    </form>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="library_dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

</body>
</html>
