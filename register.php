
<?php
require_once 'config/Database.php';

$error_message = ""; // Initialize the error message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $name = $_POST['name'];
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Additional validation
    if (empty($name) || empty($user_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Database connection
        $db = new Database();
        $conn = $db->getConnection();

        // Check for unique user_name and email
        $checkQuery = "SELECT COUNT(*) FROM ems.users WHERE user_name = ? OR email = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param('ss', $user_name, $email);
        $checkStmt->execute();
        $checkStmt->bind_result($count);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($count > 0) {
            $error_message = "User with the same user_name or email already exists.";
        } else {
            // Insert data into the 'users' table with hashed password
            $query = "INSERT INTO ems.users (name, user_name, email, phone, password) 
                      VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            // Bind parameters
            $stmt->bind_param('sssss', $name, $user_name, $email, $phone, $hashed_password);

            // Execute the query
            if ($stmt->execute()) {
                header("Location: index.php");
                exit(); // Make sure to exit after sending the header to prevent further execution
            } else {
                $error_message = "Error registering user.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/css/register.css">

</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="text-center text-dark mt-5"></h2>
                <div class="text-center mb-5 text-dark"></div>
                <div class="card my-5">
                    <form method="POST" action="register.php"class="card-body cardbody-color p-lg-5">
                        <div class="text-center">
                            <img src="./asset/images/pngegg.png" class="img-fluid profile-image-pic img-thumbnail rounded-circle my-3" width="200px" alt="profile">
                            <h2 class="text-center text-dark mb-4">Sign UP </h2>
                            <?php if (!empty($error_message)) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" placeholder="Name">
                        </div>
                        <div class="mb-3 ">
                            <input type="text" name="user_name" class="form-control" id="Username" aria-describedby="emailHelp" placeholder="User Name">
                        </div>
                        <div class="mb-3 ">
                            <input type="text" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email">
                        </div>
                        <div class="mb-3 ">
                            <input type="text" name="phone" class="form-control" id="phone" aria-describedby="emailHelp" placeholder="Phone">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="password">
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                        </div>
                  
                        <div class="text-center"><button type="submit" class="btn btn-color px-5 mb-5 w-100">Register</button></div>
                        <div id="emailHelp" class="form-text text-center mb-5 text-dark">I Have Account ? <a href="index.php" class="text-dark fw-bold"> Login</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</body>
<script src="asset/js/bootstrap.bundle.min.js"></script>
</html>