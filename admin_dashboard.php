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

// ✅ Fetch Data from admin_clearance
$query = "SELECT * FROM admin_clearance";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("<p style='color:red; text-align:center;'>Database query failed: " . mysqli_error($conn) . "</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Clearance Dashboard</title>
<link href="style/Dashboard.css" rel="stylesheet">
<style>
/* Same CSS as before, but simplified for readability */


h2 {
  text-align: center;
  color: #300559;
  margin-bottom: 20px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
table thead {
  background-color: #300559;
  color: white;
}
table th, table td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}
table tr:hover {
  background: #eee;
}
.btn {
  padding: 5px 10px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
  color: white;
  font-size: 14px;
}
.btn-success { background-color: #28a745; }
.btn-danger { background-color: #dc3545; }
.btn-primary { background-color: #007bff; }
.btn-success:hover { background-color: #218838; }
.btn-danger:hover { background-color: #c82333; }
.btn-primary:hover { background-color: #0056b3; }
</style>
</head>
<body>

<div class="container">
  <h2>🏛 Admin Clearance Dashboard</h2>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>Enrollment</th>
          <th>Name</th>
          <th>Year</th>
          <th>Branch</th>
          <th>Dept Status</th>
          <th>Store Status</th>
          <th>Library Status</th>
          <th>Library Receipt</th>
          <th>Store Receipt</th>
          <th>Clearance PDF</th>
          <th>Final Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['Enrollnment']) ?></td>
              <td><?= htmlspecialchars($row['Name']) ?></td>
              <td><?= htmlspecialchars($row['Year']) ?></td>
              <td><?= htmlspecialchars($row['Branch']) ?></td>
              <td><?= htmlspecialchars($row['dept_st']) ?></td>
              <td><?= htmlspecialchars($row['store_st']) ?></td>
              <td><?= htmlspecialchars($row['lib_st']) ?></td>

              <td>
                <?php if (!empty($row['Payment_Receipt_Library'])): ?>
                  <a href="uploads/receipts/<?= urlencode($row['Payment_Receipt_Library']) ?>" target="_blank" class="btn btn-primary">View</a>
                <?php else: ?>
                  <span style="color: #888;">None</span>
                <?php endif; ?>
              </td>

              <td>
                <?php if (!empty($row['Payment_Receipt_Store'])): ?>
                  <a href="uploads/receipts/<?= urlencode($row['Payment_Receipt_Store']) ?>" target="_blank" class="btn btn-primary">View</a>
                <?php else: ?>
                  <span style="color: #888;">None</span>
                <?php endif; ?>
              </td>

           <td>
  <?php if (!empty($row['Clearance_PDF'])): ?>
    <a href="uploads/clearance_pdfs/<?= urlencode($row['Clearance_PDF']) ?>" target="_blank" class="btn btn-success">View</a>
  <?php else: ?>
    <span style="color: #888;">Not Generated</span>
  <?php endif; ?>
</td>


              <td><?= htmlspecialchars($row['Status']) ?></td>

              <td>
                <form method="post">
                  <input type="hidden" name="Id" value="<?= $row['Id'] ?>">
                  <input type="hidden" name="en" value="<?= htmlspecialchars($row['Enrollnment']) ?>">
                  <button type="submit" name="update_status_row" value="Approved" class="btn btn-success">Approve</button>
                  <button type="submit" name="update_status_row" value="Rejected" class="btn btn-danger">Reject</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="12" style="text-align:center; color:#666;">No clearance requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
