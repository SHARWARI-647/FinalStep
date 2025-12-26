<?php
session_start();

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
    $_SESSION['otp_email'] = $email;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'rahangdaler018@gmail.com';
        $mail->Password = 'ipoeginoswzqgtco'; // app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('rahangdaler018@gmail.com', 'Student Clearance System');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'OTP Verification';
        $mail->Body = "
            <h3>Your OTP Code</h3>
            <h1>$otp</h1>
            <p>Valid for 5 minutes.</p>
        ";

        $mail->send();
        $message = "OTP sent successfully to your email.";
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
        $message = "OTP expired. Please resend.";
    } elseif ($_POST['otp'] == $_SESSION['otp']) {
        $showDownload = true;
        $message = "OTP verified successfully!";
        unset($_SESSION['otp'], $_SESSION['otp_time']);
    } else {
        $message = "Invalid OTP. Try again.";
        $showOtpForm = true;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>OTP Verification</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #300559, #6a0dad);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .otp-container {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        .otp-container h2 {
            color: #300559;
            margin-bottom: 10px;
        }

        .otp-container p {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .otp-container input {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 15px;
            transition: 0.3s;
        }

        .otp-container input:focus {
            border-color: #300559;
        }

        .otp-container button {
            width: 100%;
            margin-top: 18px;
            padding: 12px;
            background: #300559;
            border: none;
            color: white;
            font-size: 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .otp-container button:hover {
            background: #4b0c8a;
        }

        .success {
            color: green;
            font-weight: 500;
        }

        .error {
            color: red;
            font-weight: 500;
        }

        .download-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #300559;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .download-btn:hover {
            background: #4b0c8a;
        }

        @keyframes fadeIn {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body>

<div class="otp-container">
    <h2>Email Verification</h2>

    <?php if ($message): ?>
        <p class="<?= $showDownload ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>
    <?php endif; ?>

    <!-- EMAIL FORM -->
    <?php if (!$showOtpForm && !$showDownload): ?>
        <form method="post">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" name="send_otp">Send OTP</button>
        </form>
    <?php endif; ?>

    <!-- OTP FORM -->
    <?php if ($showOtpForm): ?>
        <form method="post">
            <input type="text" name="otp" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp">Verify OTP</button>
        </form>
    <?php endif; ?>

    <!-- DOWNLOAD -->
    <?php if ($showDownload): ?>
        <a href="<?= $_SESSION['pdf_path']; ?>" target="_blank" class="download-btn">
            Download Clearance Certificate
        </a>
    <?php endif; ?>
</div>

</body>
</html>
