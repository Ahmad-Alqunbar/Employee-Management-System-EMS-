<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}

require_once 'config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $type = $_POST['type'];
    $leaving_date = $_POST['leaving_date'];
    $leaving_from = $_POST['leaving_from'];
    $leaving_to = $_POST['leaving_to'];

    // Calculate duration (in minutes)
    $start_time = strtotime($leaving_from);
    $end_time = strtotime($leaving_to);
    $duration = round(($end_time - $start_time) / 60);  // Calculate difference in minutes

    $db = new Database();
    $conn = $db->getConnection();

    $query = "INSERT INTO ems.leaving (user_id, leaving_date, leaving_from, leaving_to, duration, created_at, updated_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isssi', $user_id, $leaving_date, $leaving_from, $leaving_to, $duration);

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
