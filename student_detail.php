<?php
session_start();
require('fpdf186/fpdf.php');
include 'db.php';

// Check login
if (!isset($_SESSION['enrollment'])) {
    echo "<p style='color:red; text-align:center;'>Please log in first.</p>";
    exit();
}

$enrollment = $_SESSION['enrollment']; // Get dynamically from session

if (isset($_POST['generate_pdf'])) {
    // Fetch student data from both tables
    $sql = "SELECT * FROM studentdetail 
            INNER JOIN studentpersonaldtl 
            ON studentdetail.Enrollnment = studentpersonaldtl.Enrollnment 
            WHERE studentdetail.Enrollnment = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enrollment);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();

        // Initialize PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Colors
        $headerBg = [48, 7, 89];
        $headerText = [255, 255, 255];
        $labelColor = [90, 90, 90];
        $valueColor = [0, 0, 0];
        $lineColor = [210, 210, 210];

        // Header
        $pdf->SetFillColor(...$headerBg);
        $pdf->Rect(0, 0, 210, 20, 'F');
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(...$headerText);
        $pdf->Cell(0, 20, 'Student Details Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Student Info Section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(0, 0, 128);
        $pdf->Cell(0, 10, 'Personal & Academic Information', 0, 1, 'L');
        $pdf->Ln(3);

        // Table Layout
        $leftCol = 60;
        $rightCol = 120;
        $rowH = 10;

        $fields = [
            'Enrollment No' => $row['Enrollnment'],
            'Name' => $row['Name'],
            'Year' => $row['Year'],
            'Branch' => $row['Branch'],
            'Mother Name' => $row['Mothername'],
            'Date of Birth' => $row['DOB'],
            'Gender' => $row['Gender'] ?? 'N/A',
            'Email' => $row['Email'] ?? 'N/A',
            'Phone No' => $row['Phone'] ?? 'N/A',
            'Address' => $row['Address'] ?? 'N/A'
        ];

        foreach ($fields as $label => $value) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetTextColor(...$labelColor);
            $pdf->Cell($leftCol, $rowH, $label, 1, 0, 'L', true);

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(...$valueColor);
            $pdf->Cell($rightCol, $rowH, $value, 1, 1, 'L', false);
        }

        // Footer
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor(120, 120, 120);
        $pdf->Cell(0, 10, 'Generated on: ' . date("d-m-Y H:i:s"), 0, 1, 'R');

        // Output PDF
        $pdf->Output('D', 'student_details_' . $enrollment . '.pdf');
        exit;
    } else {
        $error = "No records found for Enrollment: " . htmlspecialchars($enrollment);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Details PDF Generator</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #a18cd1, #fbc2eb);
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .container {
        background: #fff;
        padding: 40px 50px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.1);
        text-align: center;
        width: 400px;
    }
    h1 {
        color: #300759;
        margin-bottom: 25px;
    }
    button {
        background-color: #300759;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    button:hover {
        background-color: #220545;
    }
    .error {
        color: red;
        margin-top: 20px;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="container">
    <h1>🎓 Download Student Details</h1>
    <?php if (!empty($error)) : ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="post">
        <button type="submit" name="generate_pdf">Download PDF</button>
    </form>
</div>

</body>
</html>
