<?php
include_once 'config/Database.php';
$db = new Database();
$conn = $db->getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to delete a user
    $userId = $_POST['user_id'];
    $deleteQuery = "DELETE FROM users WHERE id = $userId";
    $conn->query($deleteQuery);

    echo "user deleted successfully!";
        exit();
}
?>
