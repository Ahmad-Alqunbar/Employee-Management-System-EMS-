<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    // Assuming $startTime and $endTime are in HH:MM format
    $startTimeTimestamp = strtotime($startTime);
    $endTimeTimestamp = strtotime($endTime);

    $duration_seconds = $endTimeTimestamp - $startTimeTimestamp;
    $duration = gmdate("H:i:s", $duration_seconds);

    // Get the id_working_hours and date_of_day from the form or URL, whichever is applicable
    $id_working_hours = $_POST['id_working_hours']; // Change this line based on your form structure
    $date_of_day = $_POST['date_of_day']; // Change this line based on your form structure

    $updateQuery = "UPDATE working_hours SET start_time = '$startTime', end_time = '$endTime', duration= '$duration' WHERE user_id = $userId AND id=$id_working_hours AND date_of_day='$date_of_day'";
    $conn->query($updateQuery);
    echo '<script type="text/javascript">window.location.href="user_working_hours.php";</script>';
    exit();
}

// Fetch the user's current working hours based on the ID from the URL
$userId = $_GET['user_id'];
$id_working_hours = $_GET['id']; // Assuming 'id' is the parameter name in the URL
$date_of_day = $_GET['date_of_day']; // Assuming 'date_of_day' is the parameter name in the URL

$getUserQuery = "SELECT * FROM working_hours WHERE user_id = $userId AND id = $id_working_hours AND date_of_day = '$date_of_day'";
$result = $conn->query($getUserQuery);
$user = $result->fetch_assoc();
?>
<main class="container-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Update User Working Hours</h4>
            </div>
            <div class="card-body">
                  <h6>User Name : <?=$_GET['Name'] ?></h6>
 
                <form method="post" action="">
                    <!-- Add hidden fields for id_working_hours and date_of_day -->

                    <input type="hidden" name="id_working_hours" value="<?= $id_working_hours ?>">
                    <input type="hidden" name="date_of_day" value="<?= $date_of_day ?>">

                    <div class="form-group">
                        <label for="start_time">Arrival time:</label>
                        <input type="time" class="form-control" id="start_time" name="start_time" value="<?= date('H:i', strtotime($user['start_time'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="end_time">Leave time:</label>
                        <input type="time" class="form-control" id="end_time" name="end_time" value="<?= date('H:i', strtotime($user['end_time'])) ?>">
                    </div>
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <button type="submit" class="btn btn-primary">Update Working Hours</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'layouts/footer.php';
?>
