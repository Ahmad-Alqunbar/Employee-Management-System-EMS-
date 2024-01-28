<?php
require_once 'config/Database.php';  
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = $_POST['leave_id'];
    //  echo($leave_id);
    //  exit();
    $query = "SELECT * FROM leaving WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $leave_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $leaveDetails = $result->fetch_assoc();
        echo "Are  you sure to accept the leave ?";
    } else {
        echo "Leave request not found";
    }
    $stmt->close();
    $conn->close();
}
?>
