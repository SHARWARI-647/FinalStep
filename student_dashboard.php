<?php
session_start();
require 'db.php';
include 'stdhead.php';

// Redirect if not logged in
if (!isset($_SESSION['enrollment'])) {
    header("Location: Std_login.php");
    exit();
}

$student_enrollment = $_SESSION['enrollment'];

// Fetch student info
$std = mysqli_query($conn, "SELECT * FROM studentdetail WHERE Enrollnment='$student_enrollment'");
$stdd = mysqli_fetch_assoc($std);
if (!$stdd) {
    echo "<p style='color:red;'>Student not found.</p>";
    exit();
}

// Fetch clearance statuses
$query1 = "SELECT Library_st, dept_st, store_st, final_st FROM student_clearance WHERE Enrollnment='$student_enrollment'";
$result1 = mysqli_query($conn, $query1);
$student_data = mysqli_fetch_assoc($result1);
if (!$student_data) {
    $student_data = [
        'dept_st' => 'Pending',
        'Library_st' => 'Pending',
        'store_st' => 'Pending',
        'final_st' => 'Pending'
    ];
}

// Fetch department material data
$query3 = "SELECT Material_Lent, Material_Returned, Fine_Amount, Payment_Receipt FROM dept_material WHERE Enrollnment='$student_enrollment'";
$result3 = mysqli_query($conn, $query3);
$dept_data = mysqli_fetch_assoc($result3);
if (!$dept_data) {
    $dept_data = [
        'Material_Lent' => '',
        'Material_Returned' => '',
        'Fine_Amount' => 0,
        'Payment_Receipt' => ''
    ];
}

// Handle receipt upload
$upload_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_receipt'])) {
    $dept = $_POST['department'] ?? '';
    if (!in_array($dept, ['library', 'store'])) {
        $upload_message = "Invalid department selected.";
    } elseif (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
        $upload_message = "Error uploading file.";
    } else {
        $fileTmpPath = $_FILES['receipt']['tmp_name'];
        $fileName = basename($_FILES['receipt']['name']);
        $fileSize = $_FILES['receipt']['size'];
        $fileType = mime_content_type($fileTmpPath);
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!in_array($fileType, $allowedTypes)) {
            $upload_message = "Invalid file type. Only JPG, PNG, and PDF allowed.";
        } elseif ($fileSize > 5 * 1024 * 1024) {
            $upload_message = "File size exceeds 5MB limit.";
        } else {
            $uploadDir = __DIR__ . '/uploads/receipts/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $newFileName = $student_enrollment . '_' . $dept . '_' . time() . '.' . $ext;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $check_exist = mysqli_query($conn, "SELECT * FROM dept_material WHERE Enrollnment='$student_enrollment'");
                if (mysqli_num_rows($check_exist) > 0) {
                    $update_sql = "UPDATE dept_material SET Payment_Receipt = ? WHERE Enrollnment = ?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("ss", $newFileName, $student_enrollment);
                    $stmt->execute();
                } else {
                    $insert_sql = "INSERT INTO dept_material (Enrollnment, Payment_Receipt) VALUES (?, ?)";
                    $stmt = $conn->prepare($insert_sql);
                    $stmt->bind_param("ss", $student_enrollment, $newFileName);
                    $stmt->execute();
                }

                $upload_message = "✅ Receipt uploaded successfully for $dept department.";
                $result3 = mysqli_query($conn, $query3);
                $dept_data = mysqli_fetch_assoc($result3);
            } else {
                $upload_message = "Failed to move uploaded file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Student Dashboard</title>
<link href="style\Dashboard.css" rel="stylesheet">
</head>

<body>
<div class="container">

    <h2>🎓 Welcome, <?= htmlspecialchars($stdd['Name']) ?></h2>

    <!-- STATUS TABLE -->
    <table >
        <thead >
            <tr>
                <th>Enrollment</th>
                <th>Department Status</th>
                <th>Library Status</th>
                <th>Store Status</th>
                <th>Final Status</th>
            </tr>
        </thead>
        <tbody align="center">
            <tr>
                <td><?= htmlspecialchars($stdd['Enrollnment']) ?></td>
                <td><span class="badge bg-<?= ($student_data['dept_st'] === 'Approved') ? 'success' : (($student_data['dept_st'] === 'Rejected') ? 'danger' : 'warning') ?>"><?= $student_data['dept_st'] ?></span></td>
                <td><span class="badge bg-<?= ($student_data['Library_st'] === 'Approved') ? 'success' : (($student_data['Library_st'] === 'Rejected') ? 'danger' : 'warning') ?>"><?= $student_data['Library_st'] ?></span></td>
                <td><span class="badge bg-<?= ($student_data['store_st'] === 'Approved') ? 'success' : (($student_data['store_st'] === 'Rejected') ? 'danger' : 'warning') ?>"><?= $student_data['store_st'] ?></span></td>
                <td><span class="badge bg-<?= ($student_data['final_st'] === 'Approved') ? 'success' : (($student_data['final_st'] === 'Rejected') ? 'danger' : 'warning') ?>"><?= $student_data['final_st'] ?></span></td>
            </tr>
        </tbody>
    </table>

<button id="bt2"><a href="generate_clearance.php" class="btn btn-primary">Download Clearance Certificate</a></button>
<button><a href="generate_tc.php" class="btn btn-primary">Download Transfer Certificate</a></button>


    <br><br>
    <!-- UPLOAD RECEIPT SECTION -->
    <div class="upload-section">
        <h4>📤 Upload Payment Receipt</h4>

        <?php if ($upload_message): ?>
            <div class="message"><?= htmlspecialchars($upload_message) ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label>Select Department</label>
            <select name="department" required>
                <option value="">Choose department</option>
                <option value="library">Library</option>
                <option value="store">Store</option>
            </select>

            <label>Select Receipt File (JPG, PNG, PDF max 5MB)</label>
            <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" required>

            <button type="submit" name="upload_receipt">Upload Receipt</button>
        </form>

        <?php if (!empty($dept_data['Payment_Receipt'])): 
            $receiptPath = 'uploads/receipts/' . htmlspecialchars($dept_data['Payment_Receipt']);
            $ext = strtolower(pathinfo($receiptPath, PATHINFO_EXTENSION));
        ?>
            <div class="receipt-preview">
                <strong>Uploaded Receipt:</strong><br>
                <a href="<?= $receiptPath ?>" target="_blank" download>Download Receipt</a>
                <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                    <img src="<?= $receiptPath ?>" alt="Receipt Image" style="max-height:200px;">
                <?php elseif ($ext === 'pdf'): ?>
                    <p><em>📄 PDF file uploaded.</em></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
