<?php
session_start();
require 'db.php';
require('fpdf186/fpdf.php');

// ✅ Check login
if (!isset($_SESSION['enrollment'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

$student_enrollment = $_SESSION['enrollment'];

// ✅ Fetch student and clearance details
$query = "
    SELECT sd.Name, sd.Enrollnment, sd.Year, sd.Branch, ac.dept_st, ac.store_st, ac.lib_st
    FROM studentdetail sd
    LEFT JOIN admin_clearance ac ON sd.Enrollnment = ac.Enrollnment
    WHERE sd.Enrollnment = '" . mysqli_real_escape_string($conn, $student_enrollment) . "'
";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("<p style='color:red;'>Database error: " . mysqli_error($conn) . "</p>");
}

$student_data = mysqli_fetch_assoc($result);

if (!$student_data) {
    echo "<p style='color:red; text-align:center;'>No record found for enrollment number: <b>$student_enrollment</b></p>";
    exit();
}

// ✅ Check all clearance statuses
if (
    $student_data['dept_st'] !== 'Approved' ||
    $student_data['store_st'] !== 'Approved' ||
    $student_data['lib_st'] !== 'Approved'
) {
    echo "<p style='color:red; text-align:center;'>All departments must approve your clearance before you can download the certificate.</p>";
    exit();
}

// ✅ Directory setup
$uploadDir = "uploads/clearance_pdfs/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// ✅ File name
$pdf_filename = "Clearance_Certificate_" . $student_data['Enrollnment'] . ".pdf";
$pdf_path = $uploadDir . $pdf_filename;

// ✅ Generate and Save PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// --- Header ---
$pdf->SetFillColor(48, 7, 89);
$pdf->Rect(0, 0, 210, 30, 'F');
$pdf->SetFont('Arial', 'B', 18);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetXY(0, 10);
$pdf->Cell(210, 10, 'GOVERNMENT POLYTECHNIC, YAVATMAL', 0, 1, 'C');

// --- Title ---
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('Arial', 'B', 20);
$pdf->Ln(15);
$pdf->Cell(0, 12, 'Student Clearance Certificate', 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 10, 'Date Issued: ' . date("d-m-Y"), 0, 1, 'R');
$pdf->Ln(10);

// --- Student Info ---
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Student Details:', 0, 1);
$pdf->SetFont('Arial', '', 13);
$pdf->Cell(50, 8, 'Name:', 0, 0);
$pdf->Cell(0, 8, $student_data['Name'], 0, 1);
$pdf->Cell(50, 8, 'Enrollment No:', 0, 0);
$pdf->Cell(0, 8, $student_data['Enrollnment'], 0, 1);
$pdf->Cell(50, 8, 'Branch:', 0, 0);
$pdf->Cell(0, 8, $student_data['Branch'], 0, 1);
$pdf->Cell(50, 8, 'Year:', 0, 0);
$pdf->Cell(0, 8, $student_data['Year'], 0, 1);
$pdf->Ln(7);

// --- Clearance Info ---
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Clearance Information:', 0, 1);
$pdf->SetFont('Arial', '', 13);
$pdf->Cell(50, 9, 'Department Status:', 0, 0);
$pdf->Cell(0, 9, $student_data['dept_st'], 0, 1);
$pdf->Cell(50, 9, 'Store Status:', 0, 0);
$pdf->Cell(0, 9, $student_data['store_st'], 0, 1);
$pdf->Cell(50, 9, 'Library Status:', 0, 0);
$pdf->Cell(0, 9, $student_data['lib_st'], 0, 1);

$pdf->Ln(7);
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 9, "This certificate confirms that the student has cleared all dues from Department, Store, and Library sections and is eligible for Transfer Certificate (TC).", 0, 'L');
$pdf->Ln(7);

// --- Signatures ---
$pdf->Image('style/image.png',15,185, 7);
$pdf->Cell(0, 8, 'Head of Department', 0, 1, 'L');
$pdf->Ln(7);

$pdf->Image('style/image.png', 15, 200, 7);
$pdf->Cell(0, 8, 'Librarian', 0, 1, 'L');
$pdf->Ln(7);

$pdf->Image('style/image.png', 15, 215, 7);
$pdf->Cell(0, 8, 'Store In-Charge', 0, 1, 'L');


// ✅ Save the PDF file
$pdf->Output('F', $pdf_path);

// ✅ Update the admin_clearance table with PDF filename
$update = $conn->prepare("UPDATE admin_clearance SET Clearance_PDF=? WHERE Enrollnment=?");
$update->bind_param("ss", $pdf_filename, $student_enrollment);
$update->execute();
$update->close();

// ✅ Show message with link
echo "
    <div style='text-align:center; font-family:Poppins; margin-top:50px;'>
        <h3 style='color:green;'>🎉 Clearance Certificate Generated Successfully!</h3>
        <p>You can <a href='$pdf_path' target='_blank' style='color:#007bff; text-decoration:none;'>download your clearance certificate here</a>.</p>
        <a href='student_dashboard.php' style='background:#300559; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;'>Go Back to Dashboard</a>
    </div>
";
?>
