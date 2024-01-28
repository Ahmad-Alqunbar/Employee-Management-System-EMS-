<?php
   require_once 'config/Database.php';
   $db = new Database();
   $conn = $db->getConnection();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    // echo "User_id = ".$userId;
    // exit();
    // Update the user status to active (1) in the database
    $query = "UPDATE users SET active = 1 WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);

    // Execute the update query
    if ($stmt->execute()) {
        echo 'User status updated successfully!';
    } else {
        echo 'Failed to update user status.';
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
