<?php
session_start();
include 'db.php';

// ✅ Check session
if (!isset($_SESSION['store'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

$en = '';
$check_dept = null;
$message = ''; // ✅ To show user messages

// ✅ Handle View button
if (isset($_POST['view'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $check_dept = mysqli_query($conn, "SELECT * FROM store_material WHERE Enrollnment='$en'");
}

// ✅ Handle Approve/Reject for individual material row
if (isset($_POST['update_status_row'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['update_status_row']);

    // ✅ Update individual record in store_material
    if (isset($_POST['Id']) && $_POST['Id'] !== '') {
        $rowId = intval($_POST['Id']);
        $updateQuery = "UPDATE store_material SET Status='$newStatus' WHERE Id=$rowId";
        mysqli_query($conn, $updateQuery);
    }

    // ✅ Get latest status for the student
    $latest = mysqli_query($conn, "SELECT Status FROM store_material WHERE Enrollnment='$en' ORDER BY Id DESC LIMIT 1");
    if ($latest && mysqli_num_rows($latest) > 0) {
        $r = mysqli_fetch_assoc($latest);
        $s = $r['Status'];
    } else {
        $s = $newStatus;
    }

    // ✅ Update or Insert into clearance tables
    mysqli_query($conn, "UPDATE store_clearance SET Status='$s' WHERE Enrollnment='$en'");
    mysqli_query($conn, "UPDATE student_clearance SET store_st='$s' WHERE Enrollnment='$en'");

    // ✅ Handle admin_clearance table (insert or update)
    $check_admin = mysqli_query($conn, "SELECT * FROM admin_clearance WHERE Enrollnment='$en'");
    if (mysqli_num_rows($check_admin) > 0) {
        mysqli_query($conn, "UPDATE admin_clearance SET store_st='$s' WHERE Enrollnment='$en'");
    } else {
        // Get student info for inserting
        $stu = mysqli_query($conn, "SELECT Name, Branch, Year FROM studentdetail WHERE Enrollnment='$en'");
        $stu_data = mysqli_fetch_assoc($stu);
        $name = $stu_data['Name'];
        $branch = $stu_data['Branch'];
        $year = $stu_data['Year'];

        mysqli_query($conn, "INSERT INTO admin_clearance (Enrollnment, Name, Branch, Year, store_st)
                             VALUES ('$en', '$name', '$branch', '$year', '$s')");
    }

    // ✅ Set message for UI display
    $message = "Store clearance status for <strong>$en</strong> has been updated to <strong>$s</strong>.";

    // ✅ Reload data
    $check_dept = mysqli_query($conn, "SELECT * FROM store_material WHERE Enrollnment='$en'");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Store Clearance</title>
     <link href="style\clearance.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">

    <h3 class="mb-4 text-center">📋 Store Clearance Details for <?= htmlspecialchars($en) ?></h3>

    <!-- ✅ Message Box -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <?= $message ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
        <?php if ($check_dept && mysqli_num_rows($check_dept) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($check_dept)): ?>
                <?php
                    $status = $row['Status'] ?? 'Pending';
                    $badge = match ($status) {
                        "Approved" => "success",
                        "Rejected" => "danger",
                        "Pending" => "warning",
                        default => "secondary"
                    };
                ?>
                <tr>
                    <td><?= $row['Id'] ?></td>
                    <td><?= htmlspecialchars($row['Material_Lent']) ?></td>
                    <td><?= htmlspecialchars($row['Material_Returned']) ?></td>
                    <td>₹<?= htmlspecialchars($row['Fine_Amount']) ?></td>
                    <td>
                        <?php if (!empty($row['Payment_Receipt'])): ?>
                            <?php $filePath = "uploads/" . htmlspecialchars($row['Payment_Receipt']); ?>
                            <a href="<?= $filePath ?>" target="_blank">View Receipt</a><br>
                            <img src="<?= $filePath ?>" height="80" alt="Receipt">
                        <?php else: ?>
                            <span class="text-muted">Not Uploaded</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                    <td>
                        <form method="post" class="d-inline">
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
                <td colspan="7" class="text-muted text-center">
                    No Store materials found. Student eligible for clearance.
                    <form method="post" class="mt-2">
                        <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                        <button type="submit" name="update_status_row" value="Approved" class="btn btn-success btn-sm">✅ Approve Clearance</button>
                        <button type="submit" name="update_status_row" value="Rejected" class="btn btn-danger btn-sm">❌ Reject Clearance</button>
                    </form>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="store_dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
