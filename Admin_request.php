<?php
include 'db.php'; // Database connection
include 'adminhead.php';
session_start();

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// ✅ Handle request actions
if (isset($_GET['Accept'])) {
    $id = intval($_GET['Accept']);
    $sql = "UPDATE request SET Status='Accepted' WHERE Id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>alert('Request accepted successfully!'); window.location='admin_request.php';</script>";
    exit();
}

if (isset($_GET['Reject'])) {
    $id = intval($_GET['Reject']);
    $sql = "UPDATE request SET Status='Rejected' WHERE Id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>alert('Request rejected successfully!'); window.location='admin_request.php';</script>";
    exit();
}

if (isset($_GET['Forward'])) {
    $id = intval($_GET['Forward']);
    $sql = "UPDATE request SET Status='Forwarded' WHERE Id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>alert('Request forwarded to department!'); window.location='admin_request.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Request Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">📄 Student Clearance Requests</h2>

    <table class="table table-bordered table-hover table-striped text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Enrollment</th>
                <th>Name</th>
                <th>Year</th>
                <th>Branch</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // ✅ Fetch requests with student details
            $sql = "SELECT request.Id, student.Enrollnment, student.Name, student.Year, student.Branch, request.Date, request.Status
                    FROM request
                    INNER JOIN student ON request.Enrollnment = student.Enrollnment
                    ORDER BY request.Id DESC";
            $res = mysqli_query($conn, $sql);

            if (mysqli_num_rows($res) > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>";
                    echo "<td>{$row['Enrollnment']}</td>";
                    echo "<td>{$row['Name']}</td>";
                    echo "<td>{$row['Year']}</td>";
                    echo "<td>{$row['Branch']}</td>";
                    echo "<td>{$row['Date']}</td>";

                    $statusColor = match ($row['Status']) {
                        'Accepted' => 'success',
                        'Rejected' => 'danger',
                        'Forwarded' => 'warning',
                        default => 'secondary'
                    };

                    echo "<td><span class='badge bg-$statusColor'>{$row['Status']}</span></td>";

                    echo "<td>
                            <a href='admin_request.php?Accept={$row['Id']}' class='btn btn-success btn-sm'>Accept</a>
                            <a href='admin_request.php?Reject={$row['Id']}' class='btn btn-danger btn-sm'>Reject</a>
                            <a href='upload.php?Student={$row['Enrollnment']}' class='btn btn-info btn-sm'>Upload</a>
                            <a href='admin_request.php?Forward={$row['Id']}' class='btn btn-warning btn-sm'>Forward</a>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center text-muted'>No requests found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>
