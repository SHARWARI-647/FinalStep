<?php
session_start();
require('fpdf186/fpdf.php');
include 'db.php';

// ✅ Ensure student is logged in
if (!isset($_SESSION['enrollment'])) {
    exit(); // no output before PDF
}

$en= $_SESSION['enrollment'];

// ✅ Fetch student safely
$sql = "SELECT sd.*, sp.* 
        FROM studentdetail sd 
        LEFT JOIN studentpersonaldtl sp ON sd.Enrollnment = sp.Enrollnment 
        WHERE sd.Enrollnment = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $en);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) exit();

// ✅ Helper to avoid undefined keys
function getValue($array, $key) {
    return isset($array[$key]) && !empty($array[$key]) ? $array[$key] : 'N/A';
}
$uploadDir = "uploads/tc_pdfs/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$pdf_filename = "TC_" . $en . ".pdf";
$pdf_path = $uploadDir . $pdf_filename;

// --- Generate PDF ---
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFillColor(175,225,175);
$pdf->Rect(0,0,210,297,true);

$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'GOVERNMENT POLYTECHNIC, YAVATMAL', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Times', 'B', 16);
$pdf->Cell(0,10, 'LEAVING CERTIFICATE', 1,1, 'C');

$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, 10, '(Vide Rule 14 and 30)', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 5, '(No change  in any entry in this Certificate shall be made except by the authority issuing it and any ', 0, 1,'C');
$pdf->Cell(190, 5, 'infringement of this requirement is liable to involve the impostion of penalty such that of rustication) ', 0, 1,'C');
$pdf->Ln(10);

// --- Student registration/enrollment ---
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50,0 , "Registerd No. : " .getValue($student,'RegisteredNo')."                                          "."Enrollnment :  " . getValue($student,'Enrollnment'), 0, 1);
$pdf->Cell(125, 2,"                          ..................."."                                                                 "."...................." , 0, 1);
$pdf->Ln(10);

// --- Student personal details ---
    
$pdf->Cell(50, 0, "1."."   Name : " .getValue($student,'Name'), 0, 1);
$pdf->Cell(190, 2,"______________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50,0 , "2."."   Enrollnment : " . getValue($student,'Enrollnment'), 0, 1);
$pdf->Cell(190, 2,"_________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "3."."   Mother's Name  : " .getValue($student,'Mothername'), 0, 1);
$pdf->Cell(190, 2,"______________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "4."."   Caste (with sub caste) : " . getValue($student,'Caste'), 0, 1);
$pdf->Cell(190, 2,"________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "5."."   Nationality : " . getValue($student,'Nationality'), 0, 1);
$pdf->Cell(190, 2,"__________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "6."."   Place of Birth : " .  getValue($student,'DOBPlace'), 0, 1);
$pdf->Cell(190, 2,"________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$d = getValue($student,'DOB'); // expecting YYYY-MM-DD

if ($d !== 'N/A') {
    $dob_parts = explode('-', $d); // [YYYY, MM, DD]
    $year = $dob_parts[0];
    $month_num = $dob_parts[1];
    $day = $dob_parts[2];

    $months = [
        '01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June',
        '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December'
    ];
    $month_word = $months[$month_num] ?? $month_num;

    function numberToWords($num) {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($num);
    }

    $day_word = numberToWords((int)$day);
    $year_word = numberToWords((int)$year);

} else {
    $day = $month_num = $year = $day_word = $month_word = $year_word = 'N/A';
}

// --- PDF Output ---
$pdf->Cell(50, 0, "7.   Date of Birth, in figures: (DD) $day (MM) $month_num (YYYY) $year", 0, 1);
$pdf->Cell(190, 2,"__________                   _________               ____________", 0, 1,'R');
$pdf->Ln(5);
$pdf->Cell(50, 0, "      In words: (DD) $day_word (Month) $month_word (YYYY) $year_word", 0, 1);
$pdf->Cell(190, 2,"_____________________________________________________________________", 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "8."."   Institution Last Attended : " . getValue($student,'LastInstitution'), 0, 1);
$pdf->Cell(190, 2,"______________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "9."."   Date of Admission : " . getValue($student,'DateAddmission'), 0, 1);
$pdf->Cell(190, 2,"___________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0,"10."." Progress : Satisfactory" , 0, 1);
$pdf->Cell(190, 2,"__________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "11."."  Conduct : Good", 0, 1);
$pdf->Cell(190, 2,"__________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "12."."  Date of leaving instituion : " .date("Y-m-d"), 0, 1);
$pdf->Cell(190, 2,"_____________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "13."."  Year of which studying :  " . getValue($student,'Year')."                    Year of : ". getValue($student,'Branch'), 0, 1);
$pdf->Cell(190, 2,"_________"."                    " ."____________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "   "."     which is three year diploma course : ", 0, 1);
$pdf->Cell(190, 2,"_____________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "14."."  Reason for leaving Institution : Completed the Diploma Course" , 0, 1);
$pdf->Cell(190, 2,"_________________________________________________" , 0, 1,'R');
$pdf->Ln(7);

$pdf->Cell(50, 0, "15."."  Remarks : Completed the Diploma Course in ". getValue($student,'Branch'), 0, 1);
$pdf->Cell(190, 2,"__________________________________________________________________" , 0, 1,'R');
$pdf->Ln(7);


// --- Certification note ---
$pdf->Cell(190, 0, "Certificate that the above infprmation is in accordance with the instition Register. ", 0, 1,'C');

$pdf->Ln(15);
$pdf->Cell(50, 0, "Date : " . date("Y-m-d"), 0, 1);
$pdf->Image('style/image2.png', 165, 245, 7);
$pdf->Cell(170, 10, "Principal ", 0, 1,'R');
$pdf->Cell(50, 10, $pdf->Cell(190,0, "Government Polytechnic, Yavatmal", 0, 1,'R')."Place: Yavatmal", 0, 1);


// ✅ Output PDF
$pdf->Output('F', $pdf_path);

/* =========================================================
   7. STORE PDF INFO IN SESSION
========================================================= */
$_SESSION['pdf_path'] = $pdf_path;
$_SESSION['pdf_file'] = $pdf_filename;

/* =========================================================
   8. REDIRECT TO OTP PAGE
========================================================= */
header("Location: verify_TC_otp.php");
exit();
?>







