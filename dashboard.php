<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

// Count all users
$usersCountQuery = "SELECT COUNT(*) as total_users FROM ems.users";
$stmt = $conn->prepare($usersCountQuery);
$stmt->execute();
$result = $stmt->get_result();
$count_data = $result->fetch_assoc();
$stmt->close();
$total_users = $count_data['total_users'];

// Get Admin names
$adminNamesQuery = "SELECT name FROM ems.users WHERE role_id = 1";
$stmt = $conn->prepare($adminNamesQuery);
$stmt->execute();
$resultAdmin = $stmt->get_result();
$adminNames = [];

while ($admin = $resultAdmin->fetch_assoc()) {
    $adminNames[] = $admin['name'];
}

// Get user data with working hours
$usersSystem = "SELECT *  FROM users WHERE active=1";

$resultUsers = $conn->query($usersSystem);

$users = [];
while ($row = $resultUsers->fetch_assoc()) {
    $users[] = $row;
}
$today=date('Y-m-d');
?>

<main class="container-fluid">
    <!-- ... Existing code for cards ... -->
    <section class="row">
        <div class="col-md-6 col-lg-4">
            <!-- card -->
            <article class="p-4 text-danger rounded shadow border-left mb-4">
                <a href="#" class="d-flex align-items-center text-danger ">
                    <span class="bi bi-box h5"></span>
                    <h5 class="ml-2">Users Count</h5>
                </a>
                <br>
                <p> <?= $total_users ?></p>
            </article>
        </div>
        <div class="col-md-6 col-lg-4">
            <article class="p-4 text-info rounded shadow border-left mb-4">
                <a href="#" class="d-flex align-items-center text-info">
                    <span class="bi bi-person h5"></span>
                    <h5 class="ml-2">Admin</h5>
                </a>
                <br>

                <?php foreach ($adminNames as $adminName) : ?>
                    <h5> Admin Name: <span class="text-danger"><?= $adminName; ?></span></h5>
                <?php endforeach; ?>

            </article>
        </div>
        <div class="col-md-6 col-lg-4">
            <article class="p-4 text-success rounded shadow border-left border-bottom  mb-4">
                <a href="#" class="d-flex align-items-center text-success">
                    <span class="bi bi-date h5"></span>
                    <h5 class="ml-2">Date</h5>
                </a>
                <br>
                <p> The date: <?= $today ?> </p>
            </article>
        </div>
    </section>
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>All Activated Users</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($users)) : ?>
                    <table class="table table-responsive-md table-responsive-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User name</th>
                                <th>Email</th>
                                <th>Is Logged In</th>
                                <th>Time login</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td>
                                        <?php
                                        if ($user['is_logged_in'] == 1) {
                                            echo "<span class='m-2'></span> <span class='position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle'></span>";
                                        } else {
                                            echo '<span class="m-2"></span> <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">        </span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $user['login_time'] ?></td>
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
