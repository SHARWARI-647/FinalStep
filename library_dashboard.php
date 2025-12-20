<?php
session_start();
include 'libraryhead.php';
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['library_name'])) {
    echo "<p style='color:red;'>Please log in first.</p>";
    exit();
}

// Fetch all library clearance requests
$query = "SELECT * FROM library_clearance";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Library Clearance Dashboard</title>
    <link href="style/Dashboard.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="card shadow-lg border-0 mt-4">
        <div class="card-body">
            <h2 class="card-title text-center">📚 Library Clearance Dashboard</h2>
            <p class="text-muted text-center">
                Welcome, <strong><?php echo htmlspecialchars($_SESSION['library_name']); ?></strong> 👋
            </p>
            <hr>

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
                            <tr>
                                <td><?= htmlspecialchars($row['Enrollnment']) ?></td>
                                <td><?= htmlspecialchars($row['Name']) ?></td>
                                <td><?= htmlspecialchars($row['Year']) ?></td>
                                <td><?= htmlspecialchars($row['Branch']) ?></td>
                                <td>
                                    <form method="post" action="library_std.php">
                                        <input type="hidden" name="en" value="<?= htmlspecialchars($row['Enrollnment']) ?>">
                                        <input type="submit" class="btn btn-primary btn-sm" value="View Profile" name="view">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if (mysqli_num_rows($result) === 0): ?>
                            <tr><td colspan="5" class="text-center text-muted">No requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
