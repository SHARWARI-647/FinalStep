<?php
session_start();
include 'db.php';

if (!isset($_SESSION['department'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

$en = '';
$check_dept = null;
$message = '';

// Handle "View Profile"
if (isset($_POST['view'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);
    $check_dept = mysqli_query($conn, "SELECT * FROM dept_material WHERE Enrollnment='$en'");
}

// Handle Approve/Reject for individual material
if (isset($_POST['update_status_row']) && isset($_POST['Id'])) {
    $rowId = intval($_POST['Id']);
    $newStatus = mysqli_real_escape_string($conn, $_POST['update_status_row']);
    $en = mysqli_real_escape_string($conn, $_POST['en']);

    mysqli_query($conn, "UPDATE dept_material SET Status='$newStatus' WHERE Id=$rowId");

    $latest = mysqli_query($conn, "SELECT Status FROM dept_material WHERE Enrollnment='$en' ORDER BY Id DESC LIMIT 1");
    if ($latest && mysqli_num_rows($latest) > 0) {
        $row = mysqli_fetch_assoc($latest);
        $finalStatus = $row['Status'];

        mysqli_query($conn, "UPDATE department_clearance SET Status='$finalStatus' WHERE Enrollnment='$en'");
        mysqli_query($conn, "UPDATE student_clearance SET dept_st='$finalStatus' WHERE Enrollnment='$en'");
        mysqli_query($conn, "UPDATE admin_clearance SET dept_st='$finalStatus' WHERE Enrollnment='$en'");
    }

    $message = "Status updated successfully for Enrollment: $en.";
    $check_dept = mysqli_query($conn, "SELECT * FROM dept_material WHERE Enrollnment='$en'");
}

// ✅ Handle Accept Clearance (no materials lent)
if (isset($_POST['accept_clearance'])) {
    $en = mysqli_real_escape_string($conn, $_POST['en']);

    // Update all related tables
    mysqli_query($conn, "UPDATE department_clearance SET Status='Approved' WHERE Enrollnment='$en'");
    mysqli_query($conn, "UPDATE student_clearance SET dept_st='Approved' WHERE Enrollnment='$en'");

    // ✅ Update admin_clearance table as well
    mysqli_query($conn, "UPDATE admin_clearance SET dept_st='Approved' WHERE Enrollnment='$en'");

    $message = "✅ Clearance approved — no materials were lent by this student.";
    $check_dept = mysqli_query($conn, "SELECT * FROM dept_material WHERE Enrollnment='$en'");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Clearance - Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <h3 class="text-center mb-4">📋 Department Clearance for 
        <span class="text-primary"><?= htmlspecialchars($en) ?></span>
    </h3>

    <?php if (!empty($message)): ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Material Lent</th>
                    <th>Material Returned</th>
                    <th>Fine Amount</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($check_dept && mysqli_num_rows($check_dept) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($check_dept)): ?>
                    <?php
                        $status = $row['Status'] ?? 'Pending';
                        $badge = match($status) {
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
                                <a href="uploads/<?= htmlspecialchars($row['Payment_Receipt']) ?>" target="_blank">View</a><br>
                                <img src="uploads/<?= htmlspecialchars($row['Payment_Receipt']) ?>" alt="Receipt" height="80">
                            <?php else: ?>
                                <span class="text-muted">Not Uploaded</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($status) ?></span></td>
                        <td>
                            <?php if (!empty(trim($row['Material_Lent']))): ?>
                                <form method="post" class="d-inline">
                                    <input type="hidden" name="Id" value="<?= $row['Id'] ?>">
                                    <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                                    <button type="submit" name="update_status_row" value="Approved" class="btn btn-success btn-sm mb-1">✅ Approve</button>
                                    <button type="submit" name="update_status_row" value="Rejected" class="btn btn-danger btn-sm">❌ Reject</button>
                                </form>
                            <?php else: ?>
                                <form method="post">
                                    <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                                    <button type="submit" name="accept_clearance" class="btn btn-success btn-sm">✅ Accept Clearance</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No materials were lent by this student.
                        <form method="post" class="mt-2">
                            <input type="hidden" name="en" value="<?= htmlspecialchars($en) ?>">
                            <button type="submit" name="accept_clearance" class="btn btn-success btn-sm">
                                ✅ Accept Clearance
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="dept_dashboard.php" class="btn btn-secondary">🔙 Back to Dashboard</a>
    </div>
</div>

</body>
</html>
