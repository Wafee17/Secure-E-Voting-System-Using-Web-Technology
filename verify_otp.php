<!-- otp verification page for forget password or Resetting password -->


<?php
require_once("admin/inc/config.php");
session_start();

if (!isset($_SESSION['reset_password'])) {
    header("Location: forgot_password.php"); // Redirect to forgot password page if not reset request
    exit();
}

if (isset($_POST['verify_otp'])) {
    $otp_entered = mysqli_real_escape_string($db, $_POST['otp']);

    // Verify the OTP entered by the user
    if ($otp_entered == $_SESSION['otp']) {
        header("Location: reset_password.php"); // Redirect to reset password page if OTP is correct
        exit();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
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

                        <p class="label">Enter OTP:</p><br>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" name="otp" id="otp" class="form-control input_user" required />
                        </div>

                        <div class="d-flex justify-content-center my-3 login_container">
                            <button type="submit" name="verify_otp" class="btn login_btn btn-success">Verify OTP</button>
                        </div>
                        <span class="bg-white text-success text-center my-3 ">Kindly check your mail for the OTP!</span>

                </div>

                </form>
                <?php
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
            </div>
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/boostrap.min.js"></script>
</body>

</html>