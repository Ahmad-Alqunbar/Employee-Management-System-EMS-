<?php

require_once 'config/Database.php';
session_start();

// Verify that a token is provided in the URL
if (!isset($_GET['token'])) {
    $_SESSION['error_message'] = "Invalid reset link.";
    header("Location: index.php");
    exit();
}

$token = $_GET['token'];

$db = new Database();
$conn = $db->getConnection();

// Check if the token is valid
// Check if the token is valid
$query = "SELECT user_id, expiration_time FROM ems.password_reset_tokens WHERE token = ? AND expiration_time > UTC_TIMESTAMP()";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $token);
$stmt->execute();
$stmt->bind_result($user_id, $expiration_time); // Include $expiration_time here
$stmt->fetch();
$stmt->close();

// echo $user_id . "<br>";;

// echo "Token: " . $_GET['token'] . "<br>";
// echo "Current Time: " . date('Y-m-d H:i:s') . "<br>";
// echo "Expiration Time: " . $expiration_time . "<br>";

if (!$user_id) {
    $_SESSION['error_message'] = "Invalid or expired reset link.";
    header("Location: index.php");
    exit();
}

// The token is valid; proceed to display the password reset form
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <style>
    .btn-color{
    background-color: #0e1c36;
    color: #fff;
    
  }
  
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card my-5">
                    <div class="card-header">
                        Reset Password
                    </div>
                    <div class="card-body">
                        <!-- Your password reset form goes here -->
                        <form method="post" action="process-reset-password.php">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                            <div class="mb-3">
                                <label for="new-password" class="form-label">New Password:</label>
                                <input type="password" class="form-control" id="new-password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm-password" class="form-label">Confirm Password:</label>
                                <input type="password" class="form-control" id="confirm-password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-color">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="asset/js/bootstrap.bundle.min.js"></script>
</body>

</html>
