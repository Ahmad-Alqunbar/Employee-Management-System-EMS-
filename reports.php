<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

// Establish a database connection
$db = new Database();
$conn = $db->getConnection();

// Fetch users from the database
$query = "SELECT id, name FROM users"; // Modify the query based on your actual schema
$result = $conn->query($query);

// Check if there are users
if ($result->num_rows > 0) {
    // Fetch users and build the options for the dropdown
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // No users found
    $users = [];
}

/// Handle search
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$selectedUsers = $_GET['selectedUser'] ?? '';
$selectedUsers = is_array($selectedUsers) ? $selectedUsers : [$selectedUsers];

// Initialize the variable
$isSearchConditionsSet = false;

// Build the SQL query based on search parameters
$sql = "SELECT working_hours.*, users.name,leaving.id as leaving_id,leaving.duration as leaveing_duration  FROM working_hours 
LEFT JOIN users ON working_hours.user_id = users.id
LEFT JOIN leaving ON working_hours.user_id = leaving.user_id and leaving_date=date_of_day WHERE role_id=0 AND  active=1";
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
        <!-- <div class="card">
            <div class="card-header btn-color">
                <h4>Filter Search </h4>
            </div>
            <div class="card-body">
               
            </div>
        </div> -->

        <div class="card mt-5">
            <div class="card-header btn-color">
                <h4>Employee Leaves Reports</h4>
            </div>
            <div class="card-body">
            <form method="get" action="reports.php">
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
                            <select id="select2" class="form-control mr-2" name="selectedUser[]" id="userDropdown" multiple="multiple">
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
                <?php if ($isSearchConditionsSet && $result && $result->num_rows > 0) : ?>
                    <div class="mt-4">
                        <h5>Search Results:</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                      
                                        <th>Duration</th>
                                        <th>leaving Duration</th>

                                        <th>leaving </th>
                                        <th>Date</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                while ($row = $result->fetch_assoc()) {
                                    // Fetch user name based on user_id
                                    $userName = ''; // Initialize the variable
                                    foreach ($users as $user) {
                                        if ($user['id'] == $row['user_id']) {
                                            $userName = $user['name'];
                                            break;
                                        }
                                    }
                                
                                    // Display the results in the table
                                    echo "<tr>";
                                    echo "<td>{$userName}</td>";
                                    echo "<td>{$row['duration']}</td>";
                                    
                                    echo "<td>".$row['leaveing_duration']/60 ."</td>";
                                    echo "<td>";
                                     if(isset($row['leaving_id'])){
                                          echo"<span class='badge badge-pill badge-danger'>Leave</span>";
                                     }else
                                     {
                                        echo"<span class='badge badge-pill badge-success'>No Leave</span>";

                                     }
                                     echo "</td>";
                                    echo "<td>{$row['date_of_day']}</td>";

                                    echo "</tr>";
                                }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif ($isSearchConditionsSet) : ?>
                    <div class="mt-4">
                        <p>No results found.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card mt-5">
            <div class="card-header btn-color">
                <h4>Employee Vacations Reports</h4>
            </div>
            <div class="card-body">
            <form method="get" action="reports.php">
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
                            <select id="select1" class="form-control mr-2" name="selectedUser[]" id="userDropdown" multiple="multiple">
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
