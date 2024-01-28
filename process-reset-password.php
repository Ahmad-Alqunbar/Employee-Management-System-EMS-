<?php
require_once 'config/Database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form submission
    if (isset($_POST['user_id'], $_POST['new_password'], $_POST['confirm_password'])) {
        $user_id = $_POST['user_id'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Add additional validation if needed

        if ($new_password === $confirm_password) {
            // Passwords match, update the password in the database
            $db = new Database();
            $conn = $db->getConnection();

            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_query = "UPDATE ems.users SET password = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('si', $hashed_password, $user_id);

            if ($update_stmt->execute()) {
                // Password updated successfully
                $_SESSION['success_message'] = "Password updated successfully.";
            } else {
                // Error updating password
                $_SESSION['error_message'] = "Error updating password. Please try again.";
            }

            $update_stmt->close();
        } else {
            // Passwords do not match
            $_SESSION['error_message'] = "Passwords do not match.";
        }
    } else {
        // Invalid form submission
        $_SESSION['error_message'] = "Invalid form submission.";
    }

    // Redirect back to index or login page
    header("Location: index.php");
    exit();
} else {
    // Invalid request method
    $_SESSION['error_message'] = "Invalid request method.";
    header("Location: index.php");
    exit();
}
?>
