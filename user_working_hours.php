<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Get user data with working hours
// $usersSystem = "SELECT *  FROM  working_hours";
// $resultUsers = $conn->query($usersSystem);
// $users = [];
// while ($row = $resultUsers->fetch_assoc()) {
//     $users[] = $row;
// }
$usersSystem = "SELECT working_hours.*, users.name FROM working_hours
                INNER JOIN users ON working_hours.user_id = users.id  ORDER BY date_of_day,start_time";

$resultUsers = $conn->query($usersSystem);
$users = [];

while ($row = $resultUsers->fetch_assoc()) {
    $users[] = $row;
}

$today=date('Y-m-d');
?>

<main class="container-fluid">
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>All Users</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($users)) : ?>
                    <table class="table table-responsive-md table-responsive-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>ِArrival time</th>
                              
                                <th>leave time </th>
                                <th>Working Hours</th>
                                <th>The Date</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['start_time'] ?></td>
                                    <td><?= $user['end_time'] ?></td>
                                    <td><?= $user['duration'] ?></td>
                                    <td><?= $user['date_of_day'] ?></td>
                                    <td><a href="update_user_working_hours.php?id=<?= $user['id'] ?>&user_id=<?= $user['user_id'] ?>&date_of_day=<?= $user['date_of_day'] ?>"class="btn btn-sm btn-color">update</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No users found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
include_once 'layouts/footer.php';
?>
