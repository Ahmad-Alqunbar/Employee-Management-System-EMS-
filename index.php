<?php
session_start();
require_once 'config/Database.php';
require_once 'update_working_hours.php';  // Include the new file
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT id, user_name, password, active, role_id, is_logged_in FROM ems.users WHERE user_name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $user_name);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_user_name, $hashed_password, $active, $role_id, $is_logged_in);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        if (password_verify($password, $hashed_password)) {
            if ($active == 1) {
                // Check if the user is already logged in
                if ($is_logged_in) {
                    $error_message = "User is already logged in.";
                } else {
                    // Mark the user as logged in
                    $queryUpdate = "UPDATE ems.users SET is_logged_in = TRUE, login_time = NOW() WHERE id = ?";
                    $stmtUpdate = $conn->prepare($queryUpdate);
                    $stmtUpdate->bind_param('i', $user_id);
                    $stmtUpdate->execute();
                    $stmtUpdate->close();
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['role_id'] = $role_id;
                    $_SESSION['login_time']=time();
                    updateWorkingHours($user_id, time());

                    // Redirect based on the role
                    header("Location: " . getRedirectURL($role_id));
                    exit();
                }
            } else {
                $error_message = "You are not active. Please contact the administrator.";
            }
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }
    
}

function getRedirectURL($role_id) {
    return ($role_id == 1) ? "dashboard.php" : "user_profile.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login EMS</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/css/login.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center text-dark mt-5"></h2>
                <div class="text-center mb-5 text-dark"></div>
                <div class="card my-5">

                    <form class="card-body cardbody-color p-lg-5"method="post"action="index.php">

                        <div class="text-center">
                            <img src="./asset/images/pngegg.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px" alt="profile">
                        </div>
                        <h2 class="text-center text-dark mb-5">Sign In </h2>
                        <?php if (!empty($error_message)) : ?>
                         <div class="alert alert-danger" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['error_message'])) : ?>
                         <div class="alert alert-danger" role="alert">
                                <?php echo $_SESSION['error_message']; ?>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <input type="text" name="user_name" class="form-control" id="Username" aria-describedby="emailHelp" placeholder="User Name">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="password">
                        </div>
                        <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-5 w-100">Login</button></div>
                        <!-- Add this link to your login page -->
                        <div class="text-center mb-3">
                            <a href="forgot-password.php" class="text-dark">Forgot Password?</a>
                        </div>

                        <div id="emailHelp" class="form-text text-center mb-5 text-dark">Not
                            Registered? <a href="register.php" class="text-dark fw-bold"> Create an
                                Account</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="asset/js/bootstrap.bundle.min.js"></script>
</body>

</html>