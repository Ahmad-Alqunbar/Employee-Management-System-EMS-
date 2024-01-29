<?php
include_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to delete leaving request
    $userId = $_POST['user_id'];
    $leaveId = $_POST['leave_id'];

    // Add confirmation and validation if needed

    $deleteQuery = "DELETE FROM leaving WHERE user_id = $userId AND id = $leaveId";
    $conn->query($deleteQuery);

    echo "leave deleted successfully!";
    exit();
}
?>
