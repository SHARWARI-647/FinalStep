<?php
session_start();
include 'db.php'; 
include 'depthead.php';

// Check if Department Staff is logged in
if (!isset($_SESSION['department'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

// Fetch all department clearance requests
$query = "SELECT * FROM department_clearance ORDER BY Id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Clearance Dashboard</title>
    <link href="style/Dashboard.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h2 class="card-title text-center mb-4">🏛 Department Clearance Dashboard</h2>

            <div class="table-responsive">
                <table class="table table-striped align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Enrollment</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Branch</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['Enrollnment']) ?></td>
                                <td><?= htmlspecialchars($row['Name']) ?></td>
                                <td><?= htmlspecialchars($row['Year']) ?></td>
                                <td><?= htmlspecialchars($row['Branch']) ?></td>
                                <td>
                                    <?php 
                                        $st = $row['Status'];
                                        $badge = match($st) {
                                            "Approved" => "success",
                                            "Rejected" => "danger",
                                            "Pending" => "warning",
                                            default => "secondary"
                                        };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($st) ?></span>
                                </td>
                                <td>
                                    <form method="post" action="dept_std.php" class="d-inline">
                                        <input type="hidden" name="nm" value="<?= htmlspecialchars($row['Name']) ?>">
                                        <input type="hidden" name="en" value="<?= htmlspecialchars($row['Enrollnment']) ?>">
                                        <input type="hidden" name="st" value="<?= htmlspecialchars($row['Status']) ?>">
                                        <input type="submit" class="btn btn-sm btn-primary" value="View Profile" name="view">
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
