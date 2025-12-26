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

<!-- YOUR DASHBOARD CSS -->
<link rel="stylesheet" href="style/Dashboard.css">
</head>

<body>

<div class="container">
    <div class="card">
        <div class="card-body">

            <h2 class="card-title">🏛 Department Clearance Dashboard</h2>

            <table class="table-data">
                <thead>
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
                        <td data-label="Enrollment"><?= htmlspecialchars($row['Enrollnment']) ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['Name']) ?></td>
                        <td data-label="Year"><?= htmlspecialchars($row['Year']) ?></td>
                        <td data-label="Branch"><?= htmlspecialchars($row['Branch']) ?></td>

                        <td data-label="Status">
                            <?php 
                                $st = $row['Status'];
                                $class = match($st) {
                                    "Approved" => "btn-success",
                                    "Rejected" => "btn-danger",
                                    default => "btn-primary"
                                };
                            ?>
                            <span class="btn <?= $class ?>"><?= htmlspecialchars($st) ?></span>
                        </td>

                        <td data-label="Action">
                            <form method="post" action="dept_std.php">
                                <input type="hidden" name="nm" value="<?= htmlspecialchars($row['Name']) ?>">
                                <input type="hidden" name="en" value="<?= htmlspecialchars($row['Enrollnment']) ?>">
                                <input type="hidden" name="st" value="<?= htmlspecialchars($row['Status']) ?>">
                                <input type="submit" class="btn btn-primary" value="View Profile" name="view">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</body>
</html>
