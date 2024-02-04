<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';
$db = new Database();
$conn = $db->getConnection();

$query = "SELECT id, name FROM users";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $users = [];
}

$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$selectedUsers = $_GET['selectedUser'] ?? '';
$selectedUsers = is_array($selectedUsers) ? $selectedUsers : [$selectedUsers];
$isSearchConditionsSet = false;

// Build the SQL query based on search parameters
$sql = "SELECT users.id as user_id, users.name as user_name, SUM(working_hours.duration) as total_duration
        FROM working_hours 
        LEFT JOIN users ON working_hours.user_id = users.id 
        WHERE role_id=0 AND active=1";

$paramTypes = "";
$bindParams = [];

if (!empty($dateFrom)) {
    $sql .= " AND working_hours.date_of_day >= ?";
    $paramTypes .= "s";
    $bindParams[] = $dateFrom;
    $isSearchConditionsSet = true;
}

if (!empty($dateTo)) {
    $sql .= " AND working_hours.date_of_day <= ?";
    $paramTypes .= "s";
    $bindParams[] = $dateTo;
    $isSearchConditionsSet = true;
}

if (is_array($selectedUsers)) {
    // Convert each element to an integer
    $selectedUsers = array_map('intval', $selectedUsers);

    $placeholders = implode(',', array_fill(0, count($selectedUsers), '?'));
    $sql .= " AND working_hours.user_id IN ($placeholders)";
    $paramTypes .= str_repeat("i", count($selectedUsers));
    $bindParams = array_merge($bindParams, $selectedUsers);
} else {
    // Handle the case when selectedUsers is not an array (possibly a single value)
    $sql .= " AND working_hours.user_id = ?";
    $paramTypes .= "i";
    // Convert the single value to an integer
    $bindParams[] = intval($selectedUsers);
}

// Group by and order by user_id, user_name
$sql .= " GROUP BY user_id, users.name ORDER BY users.id";

$isSearchConditionsSet = true;

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($paramTypes)) {
    $paramTypeArray = str_split($paramTypes);
    $bindParams = array_merge([$paramTypes], $bindParams);

    $refs = [];
    foreach ($bindParams as $key => $value) {
        $refs[$key] = &$bindParams[$key];
    }

    call_user_func_array([$stmt, 'bind_param'], $refs);
}

// Execute the SQL query
$stmt->execute();
$result = $stmt->get_result();

// Close the statement
$stmt->close();
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<main class="container-fluid">
    <div class="container">
        <div class="card mt-5">
            <div class="card-header btn-color">
                <h4>Employee Working Hours Reports</h4>
            </div>
            <div class="card-body">
                <form method="get" action="working_hours_reports.php">
                    <div class="row">
                        <!-- filter date -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="searchDropdown">Date From</label>
                                <input type="date" class="form-control" name="date_from" id="date_from">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="searchDropdown">Date To</label>
                                <input type="date" class="form-control" name="date_to" id="date_to">
                            </div>
                        </div>

                        <!-- select users to search -->
                        <div class="col-md-6 mb-3">
                            <label for="searchInput">Choose the Users</label>
                            <div class="form-group d-flex justify-content-around">
                                <select id="select2" class="form-control mr-2" name="selectedUser[]" id="userDropdown"
                                    multiple="multiple">
                                    <?php
                                    foreach ($users as $user) {
                                        echo "<option value='{$user['id']}'>{$user['name']}</option>";
                                    }
                                    ?>
                                </select>
                                <button class="btn btn-color" type="submit">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- Display search results or content here -->
                <?php if ($isSearchConditionsSet && $result && $result->num_rows > 0) {
    echo "<div class='mt-4'>";
    echo "<h5>Search Results:</h5>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-bordered'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>User</th>";
    echo "<th>Total Duration</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['user_name']}</td>";

        // Convert total duration from minutes to time format (HH:MM)
        $totalDurationHours = floor($row['total_duration'] / 60);
        $totalDurationMinutes = $row['total_duration'] % 60;
        $formattedTotalDuration = gmdate("H:i:s", mktime($totalDurationHours, $totalDurationMinutes));

        echo "<td>{$formattedTotalDuration}</td>";
        echo "</tr>";
    }

    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    echo "</div>";
} elseif ($isSearchConditionsSet) {
    echo "<div class='mt-4'>";
    echo "<p>No results found.</p>";
    echo "</div>";
}?>
            </div>
        </div>
    </div>
</main>


<?php
include_once 'layouts/footer.php';
?>
<!-- Include Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#select2').select2();
        $('#select1').select2();
    });
</script>
