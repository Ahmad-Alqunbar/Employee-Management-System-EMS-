<?php 
session_start();

// Include your database configuration file and establish a connection
require_once 'config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Set is_logged_in to false in the database
$updateQuery = "UPDATE ems.users SET is_logged_in = 0 WHERE id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param('i', $user_id);
$updateStmt->execute();
$updateStmt->close();

// Check if there is a login time in the session
if (isset($_SESSION['login_time'])) {
    // Calculate the duration of the session
    $login_time = $_SESSION['login_time'];
    $logout_time = time();
    $toDay=date("Y-m-d");
    // Convert seconds to time format (HH:MM:SS)
    $duration_seconds = $logout_time - $login_time;
    $duration = gmdate("H:i:s", $duration_seconds);
    // Check if there is already a record for the user on the current date
    $checkQuery = "SELECT id FROM ems.working_hours WHERE user_id = ? AND DATE(created_at) = CURDATE() AND date_of_day=?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('is', $user_id,$toDay);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        // Update the existing record (excluding start_time)
        $updateQuery = "UPDATE ems.working_hours SET end_time = CURRENT_TIMESTAMP, duration = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND DATE(created_at) = CURDATE()
        AND date_of_day=?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('sis', $duration, $user_id,$toDay);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Insert a new record if it doesn't exist
        $insertQuery = "INSERT INTO ems.working_hours (user_id, start_time, end_time, duration, created_at) VALUES (?, NULL, CURRENT_TIMESTAMP, ?, CURRENT_TIMESTAMP)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('is', $user_id, $duration);
        $insertStmt->execute();
        $insertStmt->close();
    }

    $checkStmt->close();
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

header("Location: index.php");
exit();
?>
