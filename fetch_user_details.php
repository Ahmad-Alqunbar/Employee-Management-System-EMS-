<?php
// Assuming you have a Database class or a connection established
require_once 'config/Database.php';  
$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user ID from the POST data
    $userId = $_POST['user_id'];

    // Fetch user details based on the user ID
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Assuming you have 'name' and 'email' columns in your users table
        $userName = $user['name'];
        $userEmail = $user['email'];

        // You can customize this HTML output based on your needs
        $userDetails = "Do you want to activate this user: $userName ?";
        echo $userDetails;
    } else {
        echo "User not found";
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
