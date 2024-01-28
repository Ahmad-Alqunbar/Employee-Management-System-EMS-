<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once 'config/Database.php';
require 'vendor/autoload.php';
session_start();

// Handle forget password request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_POST['email'];
    $db = new Database();
    $conn = $db->getConnection();

    // Check if the user exists
    $query = "SELECT id, email FROM ems.users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_email);
    $stmt->execute();
    $stmt->bind_result($user_id, $email);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token in the database
        $query = "INSERT INTO ems.password_reset_tokens (user_id, token, expiration_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour
        $stmt->bind_param('iss', $user_id, $token, $expirationTime);
        $stmt->execute();
        $stmt->close();

        // Send password reset email
        $resetLink = "http://localhost/EMS/reset-password.php?token=$token"; // Use localhost
        $subject = "Password Reset";
        $message = "Click the following link to reset your password: $resetLink";

        // Use PHPMailer to send the email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Change to your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ahmady00376ali@gmail.com'; // Your Gmail username or SMTP username
            $mail->Password   = 'dvnv eusa qkuh pqab';       // SMTP password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;
            $mail->SMTPDebug  = 2; // Enable verbose debug output
            $mail->Debugoutput = 'html'; // Set debug output format to HTML

            // Recipients
            $mail->setFrom('ahmady003763ali@gmail.com', 'Israr Administration'); // Change to your email
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            $_SESSION['success_message'] = "Password reset instructions sent to your email.";
        } catch (Exception $e) {
            // Log the error (do not expose to users in production)
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");

            $_SESSION['error_message'] = "Email could not be sent. Please try again later.";
        }
    } else {
        $_SESSION['error_message'] = "User not found.";
    }

    // Redirect to the login page
    header("Location: forgot-password.php");
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forget password</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
<style>
        .btn-color{
    background-color: #0e1c36;
    color: #fff;
    
  }
</style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        Forget Password
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['success_message'])) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $_SESSION['success_message']; ?>
                            </div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>

                        <?php if (!empty($_SESSION['error_message'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['error_message']; ?>
                            </div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>
                        <form method="post" action="forgot-password.php">
                            <div class="mb-3">
                                <label for="forgot-email" class="form-label">Enter your email to reset password:</label>
                                <input type="email" class="form-control" id="forgot-email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary px-5 mb-2 w-100">Reset Password</button>
                            <a href="index.php" class="btn btn-color px-5  w-100">Go Sign in !!</a>

                        </form>
                        <!-- The rest of your HTML remains the same -->

                    </div>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>


    </div>

</body>

</html>