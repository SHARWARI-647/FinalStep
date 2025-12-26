<?php
session_start();
include 'db.php';
include 'adminhead.php';

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

// ✅ Handle Approve/Reject Actions
if (isset($_POST['update_status_row'])) {
    $id = intval($_POST['Id']);
    $status = $_POST['update_status_row'];
    $enrollment = $_POST['en'];

    // Update status in admin_clearance
    $stmt = $conn->prepare("UPDATE admin_clearance SET Status=? WHERE Id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    // Update final status in student_clearance
    $stmt2 = $conn->prepare("UPDATE student_clearance SET final_st=? WHERE Enrollnment=?");
    $stmt2->bind_param("ss", $status, $enrollment);
    $stmt2->execute();
    $stmt2->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ✅ Fetch Data
$query = "SELECT * FROM admin_clearance";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Database error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Clearance Dashboard</title>

<!-- ✅ YOUR CSS -->
<link rel="stylesheet" href="style/Dashboard.css">
</head>

<body>

<div class="container">
    <div class="card">
        <div class="card-body">

            <h2 class="card-title">🏛 Admin Clearance Dashboard</h2>

            <table class="table-data">
                <thead>
                    <tr>
                        <th>Enrollment</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Branch</th>
                        <th>Dept</th>
                        <th>Store</th>
                        <th>Library</th>
                        <th>Lib Receipt</th>
                        <th>Store Receipt</th>
                        <th>Clearance PDF</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td data-label="Enrollment"><?= htmlspecialchars($row['Enrollnment']) ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['Name']) ?></td>
                        <td data-label="Year"><?= htmlspecialchars($row['Year']) ?></td>
                        <td data-label="Branch"><?= htmlspecialchars($row['Branch']) ?></td>
                        <td data-label="Dept"><?= htmlspecialchars($row['dept_st']) ?></td>
                        <td data-label="Store"><?= htmlspecialchars($row['store_st']) ?></td>
                        <td data-label="Library"><?= htmlspecialchars($row['lib_st']) ?></td>

                        <td data-label="Library Receipt">
                            <?php if ($row['Payment_Receipt_Library']): ?>
                                <a class="btn btn-primary"
                                   target="_blank"
                                   href="uploads/receipts/<?= urlencode($row['Payment_Receipt_Library']) ?>">
                                   View
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td data-label="Store Receipt">
                            <?php if ($row['Payment_Receipt_Store']): ?>
                                <a class="btn btn-primary"
                                   target="_blank"
                                   href="uploads/receipts/<?= urlencode($row['Payment_Receipt_Store']) ?>">
                                   View
                                </a>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>

                        <td data-label="Clearance PDF">
                            <?php if ($row['Clearance_PDF']): ?>
                                <a class="btn btn-success"
                                   target="_blank"
                                   href="uploads/clearance_pdfs/<?= urlencode($row['Clearance_PDF']) ?>">
                                   View
                                </a>
                            <?php else: ?>
                                Not Generated
                            <?php endif; ?>
                        </td>

                        <td data-label="Status"><?= htmlspecialchars($row['Status']) ?></td>

                        <td data-label="Action">
                            <form method="post">
                                <input type="hidden" name="Id" value="<?= $row['Id'] ?>">
                                <input type="hidden" name="en" value="<?= $row['Enrollnment'] ?>">
                                <button name="update_status_row" value="Approved" class="btn-success">Approve</button>
                                <button name="update_status_row" value="Rejected" class="btn-danger">Reject</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" style="text-align:center;">No records found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
