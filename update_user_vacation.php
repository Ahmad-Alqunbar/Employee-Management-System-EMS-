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

    $updateQuery = "UPDATE vacation SET vacation_from = '$newVacationFrom', vacation_to = '$newVacationTo' WHERE user_id = $userId AND id = $vacationId";
    $conn->query($updateQuery);

    // Redirect back to the vacation list page
    echo '<script type="text/javascript">window.location.href="vacation_list.php";</script>';
    exit();
}

// Fetch the user's current vacation details based on the ID from the URL
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
                <form method="post" action="">
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
include_once 'layouts/footer.php';
?>
