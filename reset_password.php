<?php
require_once("admin/inc/config.php");
session_start();

// Check if the session is valid (reset password flow has started)
if (!isset($_SESSION['reset_password'])) {
    header("Location: forgot_password.php"); // Redirect to forgot password page if not reset request
    exit();
}

// Reset password logic
if (isset($_POST['reset_password'])) {
    $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);
    $email = $_SESSION['email'];

    // Check if the passwords match
    if ($new_password == $confirm_password) {
        // Hash the new password using sha1
        $hashed_password = sha1($new_password);

        // Update the password in the database
        $query = "UPDATE users SET password='$hashed_password' WHERE email='$email'";
        if (mysqli_query($db, $query)) {
            // Clear session data after successful reset
            unset($_SESSION['email'], $_SESSION['otp'], $_SESSION['reset_password']);
            echo "<script>alert('Password reset successfully!'); location.assign('index.php?passwordreset=1');</script>";
        } else {
            echo "<script>alert('Error resetting password. Please try again.');</script>";
        }
    } else {
        $error_message = "Passwords do not match. Please try again.";
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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

                        <p class="label">New Password:</p><br>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="password" name="new_password" id="new_password" class="form-control input_user" required />
                        </div>
                        <p class="label">Confirm Password:</p><br>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control input_user" required />
                        </div>

                        <div class="d-flex justify-content-center my-3 login_container">
                            <button type="submit" name="reset_password" class="btn login_btn btn-success">Reset Password</button>
                        </div>
                </div>

                </form>
                <?php
    // Display error message if passwords don't match
    if (isset($error_message)) {
        echo "<p style='color: red;'>$error_message</p>";
    }
    ?>
            </div>
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/boostrap.min.js"></script>
</body>

</html>
