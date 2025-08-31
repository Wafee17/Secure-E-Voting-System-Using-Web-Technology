<!-- forget password transition flow

1.index.php (forget password button)
2.forget_password_request.php
3.verify_otp.php
4.reset_password.php  -->


<?php
require_once("admin/inc/config.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start();

if (isset($_POST['request_reset'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    
    // Check if the email exists in the database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($db, $query);
    
    if (mysqli_num_rows($result) > 0) {
        // Email exists, generate OTP
        $otp = rand(100000, 999999); // Generate 6-digit OTP
        
        // Send OTP via email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your-email@gmail.com';
            $mail->Password = 'laolmxwhvpcvrprf'; // Use an app password here
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your-email@gmail.com', 'Online Voting System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP for Password Reset';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;'>
                <h2 style='color: black; text-align: center;'>Verification Code</h2>
                <p style='font-size: 16px; color: #333;'>Dear User, $su_username</p>
                <p style='font-size: 16px; color: #333;'>Your One-Time Password (OTP) for password reset is:</p>
                <div style='text-align: center;'>
                    <h1 style='font-size: 32px; color: black;background-color:#67c232; margin: 10px 0;'>$otp</h1>
                </div>
                <p style='font-size: 16px; color: #333;'>Please use this code to complete your verification. The code is valid for 10 minutes.</p>
                <p style='font-size: 14px; color: #777;'>If you did not request this code, please ignore this email.</p>
                <hr style='border: 0; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #999; text-align: center;'>This is an automated message. Please do not reply.</p>
            </div>
            ";
            
            // Optional: Plain text version for email clients that do not support HTML
            $mail->AltBody = "Your OTP code for password reset is: $otp. Please use this code to complete your verification.";

            $mail->send();

            // Store email and OTP in session
            $_SESSION['email'] = $email;
            $_SESSION['otp'] = $otp;
            $_SESSION['reset_password'] = true;

            // Redirect to OTP verification page
            echo "<script>location.assign('verify_otp.php');</script>";
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        // Email not found in the database
        echo "<script>alert('No account found with this email.');</script>";
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container h-100">
        <div class="d-flex justify-content-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <img src="assets/images/logo.gif" class="brand_logo" alt="Logo">
                    </div>
                </div>

                <div class="d-flex justify-content-center form_container">

                    <form method="POST">

                        <p class="label">Enter your email:</p><br>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="email" name="email" id="email" class="form-control input_user" value="" placeholder="email" required />
                        </div>

                        <div class="d-flex justify-content-center my-3 login_container">
                            <button type="submit" name="request_reset" class="btn login_btn btn-success">Request Password Reset</button>
                        </div>
                </div>

                </form>
            </div>
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/boostrap.min.js"></script>
</body>

</html>
