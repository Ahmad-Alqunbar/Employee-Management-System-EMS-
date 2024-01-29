<?php
include_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to delete vacation
    $userId = $_POST['user_id'];
    $vacationId = $_POST['vacation_id'];
    // Add confirmation and validation if needed
    $deleteQuery = "DELETE FROM vacation WHERE user_id = $userId AND id = $vacationId";
    $conn->query($deleteQuery);
    echo "vacation deleted successfully!";
    exit();
}
?>
