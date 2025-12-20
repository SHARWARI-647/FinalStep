<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["Accept"])) {
        $Accept = $_POST["Accept"];
        $sql = "UPDATE library_clearance SET Status='Approved' WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $Accept);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Accepted Successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST["Reject"])) {
        $Reject = $_POST["Reject"];
        $sql = "UPDATE library_clearance SET Status='Rejected' WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $Reject);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Rejected Successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }

    if (isset($_POST["Forward"])) {
        $Forward = $_POST["Forward"];
        $sql = "UPDATE library_clearance SET Status='Pending' WHERE Id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $Forward);
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Forwarded Successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Student Clearance Requests</h2>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Enrollment</th>
            <th>Name</th>
            <th>Year</th>
            <th>Branch</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT studentdetail.Enrollnment, studentdetail.Name, studentdetail.Year, 
                       studentdetail.Branch, library_clearance.Status, library_clearance.Id
                FROM studentdetail
                INNER JOIN library_clearance ON studentdetail.Enrollnment = library_clearance.Enrollnment";
        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['Enrollnment']) . "</td>
                        <td>" . htmlspecialchars($row['Name']) . "</td>
                        <td>" . htmlspecialchars($row['Year']) . "</td>
                        <td>" . htmlspecialchars($row['Branch']) . "</td>
                        <td>" . htmlspecialchars($row['Status']) . "</td>
                        <td>
                            <form method='post'>
                                <input type='hidden' name='Accept' value='" . $row['Id'] . "'>
                                <button type='submit' class='btn btn-success btn-sm'>Accept</button>
                            </form>

                            <form method='post'>
                                <input type='hidden' name='Reject' value='" . $row['Id'] . "'>
                                <button type='submit' class='btn btn-danger btn-sm'>Reject</button>
                            </form>

                            <form method='post'>
                                <input type='hidden' name='Forward' value='" . $row['Id'] . "'>
                                <button type='submit' class='btn btn-warning btn-sm'>Forward</button>
                            </form>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6' class='text-center'>No requests found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>