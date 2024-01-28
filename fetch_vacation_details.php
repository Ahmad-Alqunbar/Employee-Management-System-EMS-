<?php
require_once 'config/Database.php';  
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vacation_id = $_POST['vacation_id'];
    //  echo($leave_id);
    //  exit();
    $query = "SELECT * FROM vacation WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $vacation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $leaveDetails = $result->fetch_assoc();
        echo "Are  you sure to accept the vacation ?";
    } else {
        echo "vacation request not found";
    }
    $stmt->close();
    $conn->close();
}
?>
