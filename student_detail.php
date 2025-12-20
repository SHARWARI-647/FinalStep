<?php
session_start();
require('fpdf186/fpdf.php');
include 'stdhead.php';
include 'db.php';

/* ---------------- CHECK LOGIN ---------------- */
if (!isset($_SESSION['enrollment'])) {
    echo "<p style='color:red;text-align:center;'>Please login first</p>";
    exit();
}

$enrollment = $_SESSION['enrollment'];
$error = "";

/* ---------------- FETCH STUDENT DATA ---------------- */
$sql = "SELECT sd.*, sp.*
        FROM studentdetail sd
        INNER JOIN studentpersonaldtl sp
        ON sd.Enrollnment = sp.Enrollnment
        WHERE sd.Enrollnment = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $enrollment);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    $error = "Student record not found";
}

/* ---------------- PDF GENERATION ---------------- */
if (isset($_POST['generate_pdf']) && empty($error)) {

    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFillColor(48,7,89);
    $pdf->Rect(0,0,210,20,'F');
    $pdf->SetFont('Arial','B',18);
    $pdf->SetTextColor(255,255,255);
    $pdf->Cell(0,20,'Student Details Report',0,1,'C');
    $pdf->Ln(10);

    $pdf->SetTextColor(0,0,0);
    $pdf->SetFont('Arial','',12);

    $fields = [
        'Enrollment No' => $student['Enrollnment'],
        'Name'          => $student['Name'],
        'Year'          => $student['Year'],
        'Branch'        => $student['Branch'],
        'Mother Name'   => $student['MotherName'],
        'DOB'           => $student['DOB'],
        'Gender'        => $student['Gender'],
        'Email'         => $student['Email'],
        'Phone'         => $student['Phone'] ?? null,
        'Address'       => $student['Address']
    ];

    foreach ($fields as $label => $value) {
        $pdf->Cell(60,10,$label,1);
        $pdf->Cell(130,10,$value,1,1);
    }

    $pdf->Output('D', 'student_'.$enrollment.'.pdf');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Profile</title>
<link href="style/Profile.css" rel="stylesheet">
</head>

<body>

<div class="profile-card">
    <h2>🎓 Student Profile</h2>

    <?php if (!empty($error)) : ?>
        <p style="color:red;text-align:center;"><?= $error ?></p>
    <?php else : ?>

    <div class="profile-item">
        <span>Enrollment No</span>
        <span><?= htmlspecialchars($student['Enrollnment']) ?></span>
    </div>
    <div class="profile-item">
        <span>Name</span>
        <span><?= htmlspecialchars($student['Name']) ?></span>
    </div>
    <div class="profile-item">
        <span>Branch</span>
        <span><?= htmlspecialchars($student['Branch']) ?></span>
    </div>
    <div class="profile-item">
        <span>Year</span>
        <span><?= htmlspecialchars($student['Year']) ?></span>
    </div>
    <div class="profile-item">
        <span>Mother Name</span>
        <span><?= htmlspecialchars($student['MotherName']) ?></span>
    </div>
    <div class="profile-item">
        <span>DOB</span>
        <span><?= htmlspecialchars($student['DOB']) ?></span>
    </div>
    <div class="profile-item">
        <span>Email</span>
        <span><?= htmlspecialchars($student['Email']) ?></span>
    </div>
    <div class="profile-item">
        <span>Phone</span>
        <span><?= htmlspecialchars($student['Phone']?? null) ?></span>
    </div>
    <div class="profile-item">
        <span>Address</span>
        <span><?= htmlspecialchars($student['Address']) ?></span>
    </div>

    <div class="text-center">
        <form method="post">
            <button type="submit" name="generate_pdf" class="btn-primary-custom">
                📄 Download PDF
            </button>
        </form>

        <a href="student_logout.php" class="btn-logout">Logout</a>
    </div>

    <?php endif; ?>
</div>

</body>
</html>