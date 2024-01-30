<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form submission to update vacation
    $userId = $_POST['user_id'];
    $vacationId = $_POST['vacation_id'];
    $newVacationFrom = $_POST['new_vacation_from'];
    $newVacationTo = $_POST['new_vacation_to'];

    // Add validation if needed
    $durationInDays=calculateDuration($newVacationFrom,$newVacationTo);

    $updateQuery = "UPDATE vacation SET vacation_from = '$newVacationFrom', vacation_to = '$newVacationTo',duration='$durationInDays' WHERE user_id = $userId AND id = $vacationId";
    $conn->query($updateQuery);

    // Redirect back to the vacation list page
    echo '<script type="text/javascript">window.location.href="vacation_list.php";</script>';
    exit();
}

$userId = $_GET['id'];
$vacationId = $_GET['vacation_id'];

$getUserVacationQuery = "SELECT u.*, v.vacation_from, v.vacation_to, v.duration, v.status, created_at, v.id as vacation_id
    FROM users u
    INNER JOIN vacation v ON u.id = v.user_id
    WHERE u.id = $userId AND v.id = $vacationId";
$result = $conn->query($getUserVacationQuery);
$userVacation = $result->fetch_assoc();
?>

<main class="container-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Update Vacation</h4>
            </div>
            <div class="card-body">
            <form method="post" action="" onsubmit="return validateForm();">
                    <div class="form-group">
                        <label for="new_vacation_from">Vacation From:</label>
                        <input type="date" class="form-control" id="new_vacation_from" name="new_vacation_from" value="<?= $userVacation['vacation_from'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="new_vacation_to">Vacation To:</label>
                        <input type="date" class="form-control" id="new_vacation_to" name="new_vacation_to" value="<?= $userVacation['vacation_to'] ?>">
                    </div>
                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                    <input type="hidden" name="vacation_id" value="<?= $vacationId ?>">
                    <button type="submit" class="btn btn-primary">Update Vacation</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php
function calculateDuration($fromDate, $toDate) {
    // Parse the date strings to DateTime objects
    $fromDateObj = new DateTime($fromDate);
    $toDateObj = new DateTime($toDate);

    // Calculate the duration in days
    $interval = $toDateObj->diff($fromDateObj);
    $durationInDays = $interval->days;

    // Add 1 to include both the start and end dates
    return $durationInDays + 1;
}
include_once 'layouts/footer.php';
?>
<script>
    function validateForm() {
        var newVacationFrom = document.getElementById('new_vacation_from').value;
        var newVacationTo = document.getElementById('new_vacation_to').value;

        // Parse the date strings to Date objects
        var startDate = new Date(newVacationFrom);
        var endDate = new Date(newVacationTo);

        // Compare the dates
        if (startDate >= endDate) {
            alert('Vacation From date should be before Vacation To date.');
            return false;
        }

        return true;
    }
</script>
