<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to update user
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $userName = $_POST['user_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    // Add validation and error handling if needed
    $updateQuery = "UPDATE users SET name = '$name', user_name = '$userName', email = '$email', phone = '$phone' WHERE id = $userId";
    $conn->query($updateQuery);
    // Redirect back to the user list page
    echo '<script type="text/javascript">window.location.href="users_list.php";</script>';
    exit();
}

// Fetch the user's current details based on the ID from the URL
$userId = $_GET['id'];
$getUserQuery = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($getUserQuery);
$user = $result->fetch_assoc();
?>

<main class="container-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Update User</h4>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="user_name">Username:</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" value="<?= $user['user_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?>">
                    </div>
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'layouts/footer.php';
?>
