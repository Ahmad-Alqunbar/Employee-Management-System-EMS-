<?php
// Assuming you have a Database class or a connection established
require_once 'config/Database.php';  

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $vacationId = $_POST['vacation_id'];
    // echo $leaveId; 
    // exit();

    // Update the leave status to accepted (assuming you have a 'status' column)
    $query = "UPDATE vacation SET status = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $vacationId);

    if ($stmt->execute()) {
        echo "vacation request accepted successfully!";
    } else {
        echo "Error accepting vacation request";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
