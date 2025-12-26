<?php
session_start();

if (!isset($_SESSION['pdf_path'])) {
    die("Unauthorized access.");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$message = "";
$showOtpForm = false;
$showDownload = false;

/* =========================================================
   1. SEND OTP
========================================================= */
if (isset($_POST['send_otp'])) {

    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rahangdaler018@gmail.com';
        $mail->Password = 'ipoeginoswzqgtco';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('rahangdaler018@gmail.com', 'Student Clearance System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body = "<h2>Your OTP is</h2><h1>$otp</h1><p>Valid for 5 minutes</p>";

        $mail->send();
        $message = "OTP sent successfully!";
        $showOtpForm = true;

    } catch (Exception $e) {
        $message = "OTP sending failed.";
    }
}

/* =========================================================
   2. VERIFY OTP
========================================================= */
if (isset($_POST['verify_otp'])) {

    if (!isset($_SESSION['otp'], $_SESSION['otp_time'])) {
        $message = "Session expired. Please resend OTP.";
    } elseif (time() - $_SESSION['otp_time'] > 300) {
        $message = "OTP expired.";
    } elseif ($_POST['otp'] == $_SESSION['otp']) {
        $showDownload = true;
        $message = "OTP verified successfully!";
        unset($_SESSION['otp'], $_SESSION['otp_time']);
    } else {
        $message = "Invalid OTP.";
        $showOtpForm = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>OTP Verification</title>

<style>
body{
    background:#f2f2f2;
    font-family:Poppins,sans-serif;
    display:flex;
    align-items:center;
    justify-content:center;
    min-height:100vh;
}
.otp-box{
    background:#fff;
    padding:30px;
    width:350px;
    border-radius:12px;
    box-shadow:0 15px 30px rgba(0,0,0,.2);
    text-align:center;
}
input,button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:8px;
}
button{
    background:#300559;
    color:#fff;
    border:none;
    cursor:pointer;
}
.download{
    display:inline-block;
    margin-top:20px;
    background:#300559;
    color:#fff;
    padding:12px 20px;
    border-radius:8px;
    text-decoration:none;
}
.success{color:green;}
.error{color:red;}
</style>
</head>

<body>

<div class="otp-box">
<h2>Email Verification</h2>

<?php if ($message): ?>
<p class="<?= $showDownload ? 'success':'error' ?>"><?= $message ?></p>
<?php endif; ?>

<?php if (!$showOtpForm && !$showDownload): ?>
<form method="post">
    <input type="email" name="email" placeholder="Enter Email" required>
    <button name="send_otp">Send OTP</button>
</form>
<?php endif; ?>

<?php if ($showOtpForm): ?>
<form method="post">
    <input type="text" name="otp" placeholder="Enter OTP" required>
    <button name="verify_otp">Verify OTP</button>
</form>
<?php endif; ?>

<?php if ($showDownload): ?>
<a href="<?= $_SESSION['pdf_path']; ?>" target="_blank" class="download">
    Download Transfer Certificate
</a>
<?php endif; ?>

</div>
</body>
</html>
