<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to update leaving request
    $userId = $_POST['user_id'];
    $leaveId = $_POST['leave_id'];
    $leavingFrom = $_POST['leaving_from'];
    $leavingTo = $_POST['leaving_to'];
    $leavingDate = $_POST['leaving_date'];
    $start_time = strtotime($leavingFrom);
    $end_time = strtotime($leavingTo);
    $duration = round(($end_time - $start_time) / 60);  // Calculate difference in minutes
    // Add validation and update query here
    $updateQuery = "UPDATE leaving SET leaving_from = '$leavingFrom', leaving_to = '$leavingTo',duration='$duration', leaving_date = '$leavingDate' WHERE user_id = $userId AND id = $leaveId";
    $conn->query($updateQuery);

    echo '<script type="text/javascript">window.location.href="leaving_list.php";</script>';
    exit();
}

// Fetch the leaving details based on the user ID and leaving ID
$userId = $_GET['id'];
$leaveId = $_GET['leave_id'];

$getLeaveQuery = "SELECT * FROM leaving WHERE user_id = $userId AND id = $leaveId";
$result = $conn->query($getLeaveQuery);
$leave = $result->fetch_assoc();

// Output for testing
// echo "<pre>";
// print_r($leave);
// echo "</pre>";
// exit();
?>

<main class="container-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Update Leaving Request</h4>
            </div>
            <div class="card-body">
            <form method="post" action="" onsubmit="return validateForm();">
                    <div class="form-group">
                        <label for="leaving_from">Leaving From:</label>
                        <input type="time" class="form-control" id="leaving_from" name="leaving_from" value="<?= date('H:i', strtotime($leave['leaving_from'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="leaving_to">Leaving To:</label>
                        <input type="time" class="form-control" id="leaving_to" name="leaving_to" value="<?= date('H:i', strtotime($leave['leaving_to'])) ?>">
                    </div>
                    <div class="form-group">
                        <label for="leaving_date">Leaving Date:</label>
                        <input type="date" class="form-control" id="leaving_date" name="leaving_date" value="<?= $leave['leaving_date'] ?>">
                    </div>
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <input type="hidden" name="leave_id" value="<?= $leaveId ?>">
                    <button type="submit" class="btn btn-primary">Update Leaving Request</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'layouts/footer.php';
?>
<script>
    function validateForm() {
        var leavingFrom = document.getElementById('leaving_from').value;
        var leavingTo = document.getElementById('leaving_to').value;

        // Parse the time strings to Date objects
        var startTime = new Date('1970-01-01T' + leavingFrom + 'Z');
        var endTime = new Date('1970-01-01T' + leavingTo + 'Z');

        // Compare the times
        if (startTime >= endTime) {
            alert('Leaving From time should be before Leaving To time.');
            return false;
        }

        return true;
    }
</script>

