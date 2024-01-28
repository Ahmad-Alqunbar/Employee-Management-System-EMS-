<?php
session_start();

if (!isset($_SESSION['user_id'])) {
   // Redirect to the login page or perform other actions
   header("HTTP/1.1 401 Unauthorized");
   exit();
}

require_once 'config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $user_id = $_SESSION['user_id'];
   $vacation_from = $_POST['vacation_from'];
   $vacation_to = $_POST['vacation_to'];
   $reason_vacation = $_POST['reason_vacation'];
   $duration = $_POST['duration'];

   $db = new Database();
   $conn = $db->getConnection();

   // Perform SQL INSERT operation based on form data
   $query = "INSERT INTO ems.vacation (user_id, vacation_from, vacation_to,the_reason, duration, created_at, updated_at)
             VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
   $stmt = $conn->prepare($query);
   $stmt->bind_param('isssi', $user_id, $vacation_from, $vacation_to, $reason_vacation, $duration);

 


    if ($stmt->execute()) {
        echo "Request submitted successfully!";
    } else {
        echo "Failed to submit request.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("HTTP/1.1 400 Bad Request");
}
?>
