<!-- otp verification page for SignUp & Login Page  -->


<?php
require_once("admin/inc/config.php");
session_start();

if (isset($_POST['verify_otp'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $user_otp = mysqli_real_escape_string($db, $_POST['otp']);

    // Fetch OTP from session
    $session_otp = $_SESSION['otp'];

    // Check if the OTP entered by the user matches the one stored in the session
    if ($user_otp == $session_otp) {
        // OTP is correct, proceed with login
        $query = "SELECT user_role, username, id FROM users WHERE email = '$email'";
        $result = mysqli_query($db, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Start the session and log the user in
            $_SESSION['user_role'] = $row['user_role'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

            // Redirect to the appropriate dashboard
            if ($row['user_role'] == "Admin") {
                $_SESSION['key'] = "AdminKey";
                echo "<script>location.assign('admin/index.php?homepage=1');</script>";
            } else {
                $_SESSION['key'] = "VotersKey";
                echo "<script>location.assign('voters/index.php');</script>";
            }

            // Optionally, clear the OTP from the session
            unset($_SESSION['otp']);
        } else {
            // User not found
            echo "<script>location.assign('index.php?email_not_found=1');</script>";
        }
    } else {
        // OTP is incorrect
        echo "<script>location.assign('index.php?otp_invalid=1');</script>";
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
                            <input type="text" name="otp" id="otp" class="form-control input_user" value="" placeholder="" required />
                        </div>

                        <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>"> <!-- Passing email from URL -->

                        <div class="d-flex justify-content-center my-3 login_container">
                            <button type="submit" name="verify_otp" class="btn login_btn btn-success">Verify OTP</button>
                        </div>
                </div>
                <span class="bg-white text-success text-center my-3 ">Kindly check your mail for the OTP!</span>

                </form>
            </div>
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/boostrap.min.js"></script>
</body>

</html>