<?php
function updateWorkingHours($user_id, $login_time) {
    $db = new Database();
    $conn = $db->getConnection();

    $todayDate = date('Y-m-d');

    // Declare $date_of_day variable
    $date_of_day = null;
    
    // Use parameter binding to prevent SQL injection
    $checkQuery = "SELECT id, date_of_day FROM ems.working_hours WHERE user_id = ? AND date_of_day = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('is', $user_id, $todayDate);
    $checkStmt->execute();

    // Bind the result
    $checkStmt->bind_result($id, $date_of_day);

    // Fetch the result before storing it
    $checkStmt->fetch();

    // Output the values for test
    // Close the result set
    $checkStmt->close();

    if ($date_of_day == $todayDate) {
        // echo $date_of_day." ==". $todayDate;
        // exit();
        // Update existing record
        $updateQuery = "UPDATE ems.working_hours SET start_time = FROM_UNIXTIME(?) , end_time = null, updated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND DATE(created_at) = CURDATE() AND date_of_day=? ";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('iis', $login_time, $user_id,$todayDate);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        // Insert a new record
        $insertQuery = "INSERT INTO ems.working_hours (user_id, start_time, created_at, date_of_day) VALUES (?, FROM_UNIXTIME(?), CURRENT_TIMESTAMP, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('iss', $user_id, $login_time, $todayDate);
        $insertStmt->execute();
        $insertStmt->close();
    }
}
?>
