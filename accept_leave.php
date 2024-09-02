<?php
// Assuming you have a Database class or a connection established
require_once 'config/Database.php';  

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $leaveId = $_POST['leave_id'];
    $leaveStatus = $_POST['status'];
   
    // Update the leave status to accepted (assuming you have a 'status' column)
    $query = "UPDATE leaving SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $leaveStatus, $leaveId);

    if ($stmt->execute()) {
        echo " Leave request accepted successfully! ";
    } else {
        echo " Error accepting leave request ";
    }
    $stmt->close();
    $conn->close();
}
?>
