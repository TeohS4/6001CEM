<?php
include('../connect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../phpmailer/Exception.php';
require_once '../phpmailer/PHPMailer.php';
require_once '../phpmailer/SMTP.php';

function send_password_reset($get_name, $get_email, $token){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
	$mail->Host = 'smtp.gmail.com';
	$mail->SMTPAuth = true;
	$mail->Username = 'weijieteoh26@gmail.com'; // Email for SMTP server
	$mail->Password = 'icnigwfanwuydwap'; // Secret Passowrd
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->Port = '587';

	$mail->setFrom('weijieteoh26@gmail.com', 'EcoPack'); // Gmail address which you used as SMTP server
	$mail->addAddress($get_email); // Email address where you want to receive emails (you can use any of your gmail address including the gmail address which you used as SMTP server)

	$mail->isHTML(true);
	$mail->Subject = 'Reset Password';
	
    $email_template = "<p>Click the following link to reset your password: </p>
                        <a href='http://localhost/ecopack/login/reset-password.php?token=$token&email=$get_email'>Click Me</a>";

    $mail->Body = $email_template;
    $mail->send();
}

session_start();
if(isset($_POST['password_reset'])){
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $token = md5(rand());

    $check_email = "SELECT username, email FROM user WHERE email='$email' LIMIT 1";
    $check_email_run = mysqli_query($db, $check_email);
    $row = mysqli_fetch_array($check_email_run);

    if ($row) {
        $get_name = $row['username'];
        $get_email = $row['email'];
        $update_token = "UPDATE user SET reset_token='$token' WHERE email='$get_email' LIMIT 1";
        $update_token_run = mysqli_query($db, $update_token);

        if ($update_token_run) {
            // Assuming send_password_reset() is a valid function that sends reset instructions.
            send_password_reset($get_name, $get_email, $token);
            $_SESSION['status'] = "Password reset instructions sent to your email.";
            header("Location: forgot-pass.php");
            exit(0);
        } else {
            $_SESSION['status'] = "Error updating reset token.";
            header("Location: forgot-password.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "No Email Found";
        header("Location: forgot-password.php");
        exit(0);
    }
}

if (isset($_POST['password_update'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm_password']);

    $token = mysqli_real_escape_string($db, $_POST['password_token']);

    if (!empty($token)) {

        if (!empty($email) && !empty($new_password) && !empty($confirm_password)) {
            // Checking Token is valid or not
            $check_token = "SELECT reset_token FROM user WHERE reset_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($db, $check_token);

            if (mysqli_num_rows($check_token_run) > 0) {
                if ($new_password == $confirm_password) {
                    // Hash the new password before storing it
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    $update_password = "UPDATE user SET password='$hashed_password' WHERE reset_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($db, $update_password);

                    if ($update_password_run) {
                        // Update the token before redirecting
                        $new_token = md5(rand()) . "New Token"; // Generate a new token here
                        $update_to_new_token = "UPDATE user SET reset_token='$new_token' WHERE reset_token='$token' LIMIT 1";
                        $update_to_new_token_run = mysqli_query($db, $update_to_new_token);

                        header("Location: login.php");
                        exit(0);
                    } else {
                        $_SESSION['status'] = "There is an error. Your password was not updated!";
                        header("Location: reset-password.php?token=$token&email=$email");
                        exit(0);
                    }

                } else {
                    $_SESSION['status'] = "Password and Confirm Password do not match";
                    header("Location: reset-password.php?token=$token&email=$email");
                    exit(0);
                }

            } else {
                $_SESSION['status'] = "Invalid token";
                header("Location: reset-password.php?token=$token&email=$email");
                exit(0);
            }

        } else {
            $_SESSION['status'] = "Please fill in all the fields";
            header("Location: reset-password.php?token=$token&email=$email");
            exit(0);
        }

    } else {
        $_SESSION['status'] = "No Token Available";
        header("Location: reset-password.php");
        exit(0);
    }
}

?>