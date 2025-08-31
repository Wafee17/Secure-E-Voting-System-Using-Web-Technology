<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->


<?php

//logic to automattically activate or de-activate the elections
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once("admin/inc/config.php");

$fetchingElections = mysqli_query($db, "SELECT * FROM elections") or die(mysqli_error($db));
while ($data = mysqli_fetch_assoc($fetchingElections)) {
	$starting_date = $data['starting_date'];
	$ending_date = $data['ending_date'];
	$curr_date = date("Y-m-d");
	$election_id = $data['id'];
	$status = $data['status'];

	//Active = Expire = Ending Date
	//InActive = Active = Starting Date

	if ($status == "Active") {
		$date1 = date_create($curr_date);
		$date2 = date_create($ending_date);
		$diff = date_diff($date1, $date2);

		if ((int) $diff->format("%R%a") < 0) {
			//Update  
			mysqli_query($db, "UPDATE elections SET status='Expired' WHERE id = '" . $election_id . "' ") or die(mysqli_error($db));
		}
	} else if ($status == "InActive") {

		$date1 = date_create($curr_date);
		$date2 = date_create($starting_date);
		$diff = date_diff($date1, $date2);


		if ((int) $diff->format("%R%a") <= 0) {
			//Update
			mysqli_query($db, "UPDATE elections SET status='Active' WHERE id = '" . $election_id . "' ") or die(mysqli_error($db));
		}
	}
}

?>


<!DOCTYPE html>
<html>

<head>
	<title>Login - Online Voting System</title>
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

				<?php
				if (isset($_GET['sign-up'])) {
				?>
					<div class="d-flex justify-content-center form_container">
						<form method="POST">
							<div class="input-group mb-2 mt-5">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-user"></i></span>
								</div>
								<input type="text" name="su_username" class="form-control input_user" placeholder="username" required />
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-key"></i></span>
								</div>
								<input type="text" name="su_mobile_number" class="form-control input_pass" value="" placeholder="mobile number" required />
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-key"></i></span>
								</div>
								<input type="text" name="su_email" class="form-control input_pass" value="" placeholder="email" required />
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-key"></i></span>
								</div>
								<input type="password" name="su_password" class="form-control input_pass" value="" placeholder="password" required />
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-key"></i></span>
								</div>
								<input type="password" name="su_confirm_password" class="form-control input_pass" value="" placeholder="confirm password" required />
							</div>

							<div class="d-flex justify-content-center mt-3 login_container">
								<button type="submit" name="sign_up_btn" class="btn login_btn">Sign Up</button>
							</div>
						</form>
					</div>

					<div class="mt-4">
						<div class="d-flex justify-content-center links text-white">
							Already created an account? <a href="index.php" class="ml-2 text-white">Sign In</a>
						</div>

					</div>
				<?php
				} else {
				?>
					<div class="d-flex justify-content-center form_container">
						<form method="POST">
							<div class="input-group mb-3">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-user"></i></span>
								</div>
								<input type="text" name="email" class="form-control input_user" value="" placeholder="email" required />
							</div>
							<div class="input-group mb-2">
								<div class="input-group-append">
									<span class="input-group-text"><i class="fas fa-key"></i></span>
								</div>
								<input type="password" name="password" class="form-control input_pass" placeholder="password" required />
							</div>

							<div class="d-flex justify-content-center mt-3 login_container">
								<button type="submit" name="loginBtn" class="btn login_btn">Login</button>
							</div>
						</form>
					</div>

					<div class="mt-4">
						<div class="d-flex justify-content-center links text-white">
							Don't have an account? <a href="?sign-up=1" class="ml-2 text-white">Sign Up</a>
						</div>
						<div class="d-flex justify-content-center links text-white">
							<a href="forgot_password_request.php" class="text-white">Forgot your password?</a>
						</div>
					</div>

				<?php
				}
				?>

				<?php
				if (isset($_GET['registered'])) {
				?>
					<span class="bg-white text-success text-center my-3">your Account has been created successfully!</span>
				<?php
				} elseif (isset($_GET['invalid'])) {
				?>
					<span class="bg-white text-danger text-center my-3">Passwords mismatched, Please try again!</span>
				<?php
				} elseif (isset($_GET['not_registered'])) {
				?>
					<span class="bg-white text-warning text-center my-3 ">Sorry, you are not registered!</span>
				<?php
				} elseif (isset($_GET['invalid_access'])) {
				?>
					<span class="bg-white text-danger text-center my-3">Invalid username or password!</span>
				<?php
				}elseif (isset($_GET['otp_invalid'])) {
					?>
						<span class="bg-white text-danger text-center my-3">Invalid OTP entered, please try again!</span>
					<?php
				}
				?>



			</div>
		</div>
	</div>
	<script src="assets/js/jquery.min.js"></script>
	<script src="assets/js/boostrap.min.js"></script>
</body>

</html>




<?php
require_once("admin/inc/config.php");

if (isset($_POST['sign_up_btn'])) {

    $su_username = mysqli_real_escape_string($db, $_POST['su_username']);
    $su_email = mysqli_real_escape_string($db, $_POST['su_email']);
    $su_mobile_number = mysqli_real_escape_string($db, $_POST['su_mobile_number']);
    $su_password = mysqli_real_escape_string($db, sha1($_POST['su_password']));
    $su_confirm_password = mysqli_real_escape_string($db, sha1($_POST['su_confirm_password']));
    $user_role = "Voter";  // Default role

    // su_mobile_number su_password su_confirm_password 

    if ($su_password == $su_confirm_password) {
        // Insert the user into the database
        mysqli_query($db, "INSERT INTO users(username, email, mobile_number, password, user_role) 
            VALUES('$su_username', '$su_email', '$su_mobile_number', '$su_password', '$user_role')") or die(mysqli_error($db));

        // Generate OTP
        $otp = rand(100000, 999999);

        // Send OTP via email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'votingsystem0000@gmail.com';
            $mail->Password = 'laolmxwhvpcvrprf';  // Use your SMTP credentials
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('votingsystem0000@gmail.com', 'Online Voting System');
            $mail->addAddress($su_email);
            $mail->isHTML(true);
            $mail->Subject = 'OTP Verification';
            $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;'>
                <h2 style='color: black; text-align: center;'>Verification Code</h2>
                <p style='font-size: 16px; color: #333;'>Dear User, $su_username</p>
                <p style='font-size: 16px; color: #333;'>Your One-Time Password (OTP) is:</p>
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
            $mail->AltBody = "Your OTP code is: $otp. Please use this code to complete your verification.";

            $mail->send();

            // Store OTP and user data in session
            session_start();
            $_SESSION['otp'] = $otp;
            $_SESSION['su_username'] = $su_username;
            $_SESSION['su_email'] = $su_email;
            $_SESSION['su_mobile_number'] = $su_mobile_number;
            $_SESSION['su_password'] = $su_password;

            echo 'OTP sent to your email. Please verify.';
            echo '<script>location.assign("otp_verification.php?email=' . $su_email . '");</script>';
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        echo '<script>location.assign("index.php?sign-up=1&invalid=1");</script>';
    }
}
elseif (isset($_POST['loginBtn'])) {
    session_start();

    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, sha1($_POST['password']));

    // SELECT QUERY
    $fetchingData = mysqli_query($db, "SELECT * FROM users WHERE email='$email'") or die(mysqli_error($db));

    // Check if user exists
    if (mysqli_num_rows($fetchingData) > 0) {
        $data = mysqli_fetch_assoc($fetchingData);

        // Verify the password
        if ($password == $data['password']) {
            // Generate OTP
            $otp = rand(100000, 999999);

            // Send OTP via email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'votingsystem0000@gmail.com';
                $mail->Password = 'laolmxwhvpcvrprf'; // Use an app password here
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('votingsystem0000@gmail.com', 'Online Voting System');
                $mail->addAddress($data['email']);
                $mail->isHTML(true);
				$mail->Subject = 'OTP Verification';
				$mail->Body = "
				<div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px;'>
					<h2 style='color: black; text-align: center;'>Verification Code</h2>
					<p style='font-size: 16px; color: #333;'>Dear User, $su_username</p>
					<p style='font-size: 16px; color: #333;'>Your One-Time Password (OTP) is:</p>
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
				$mail->AltBody = "Your OTP code is: $otp. Please use this code to complete your verification.";
	

                $mail->send();

                // Store OTP and user data in session
                $_SESSION['otp'] = $otp;
                $_SESSION['user_id'] = $data['id'];
                $_SESSION['email'] = $data['email'];

                // Redirect to OTP verification page
                echo '<script>location.assign("otp_verification.php?email=' . $data['email'] . '");</script>';
            } catch (Exception $e) {
                echo "Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Incorrect password
            echo '<script>location.assign("index.php?invalid_access=1");</script>';
        }
    } else {
        // User not found
        echo '<script>location.assign("index.php?sign-up=1&not_registered=1");</script>';
    }
}
