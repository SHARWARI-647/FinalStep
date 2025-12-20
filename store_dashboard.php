<?php
session_start();

include 'db.php'; // Database Connection

include 'storehead.php';
// Check if Department Staff is logged in
if (!isset($_SESSION['store'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

// Fetch Requests 
$query = "SELECT * FROM store_clearance";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Store Clearance Dashboard</title>
    <link href="style/Dashboard.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container">
    <div class="card shadow-lg border-0">
        <div class="card-body">
            <h2 class="card-title">🏛 Store Clearance Dashboard</h2>

            <div class="table-responsive">
                <table class="table-data">
                    <thead>
                        <tr>
                            <th>Enrollment</th>
                            <th>Name</th>
                            <th>Year</th>
                            <th>Branch</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr id="row-<?= htmlspecialchars($row['Id']) ?>">
                                <td data-label="Enrollment"><?= htmlspecialchars($row['Enrollnment']) ?></td>
                                <td data-label="Name"><?= htmlspecialchars($row['Name']) ?></td>
                                <td data-label="Year"><?= htmlspecialchars($row['Year']) ?></td>
                                <td data-label="Branch"><?= htmlspecialchars($row['Branch']) ?></td>
                                <td data-label="Action">
                                    <form method="post" action="store_std.php" class="d-inline">
                                        <input type="hidden" name="nm" value="<?= htmlspecialchars($row['Name']) ?>">
                                        <input type="hidden" name="en" value="<?= htmlspecialchars($row['Enrollnment']) ?>">
                                        <input type="hidden" name="st" value="<?= htmlspecialchars($row['Status']) ?>">
                                        <input type="submit" class="btn btn-primary btn-sm" value="View Profile" name="view">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        <?php if(mysqli_num_rows($result) === 0): ?>
                            <tr><td colspan="5" style="text-align:center; padding: 20px; color:#300559; font-weight:600;">No requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
