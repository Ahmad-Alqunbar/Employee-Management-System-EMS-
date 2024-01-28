<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Get user data with working hours
$usersSystem = "SELECT *  FROM users";
$resultUsers = $conn->query($usersSystem);
$users = [];
while ($row = $resultUsers->fetch_assoc()) {
    $users[] = $row;
}
$today = date('Y-m-d');
?>

<main class="container-fluid">
    <!-- start Modal Active user -->
    <div class="modal fade" id="active_user" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-color">
                    <h5 class="modal-title" id="exampleModalLabel">Active User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="user-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="activate_user">Activate</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Active user -->

    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>User Information</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($users)) : ?>
                    <table class="table table-responsive-md table-responsive-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Is Logged In</th>
                                <th>Activation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['user_name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['phone'] ?></td>
                                    <td>
                                        <?php
                                        if ($user['is_logged_in'] == 1) {
                                            echo "<span class='m-2'></span> <span class='position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle'></span>";
                                        } else {
                                            echo '<span class="m-2"></span> <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php
                                        if ($user['active'] == 1) {
                                            echo '<span class="badge bg-primary text-white">Active</span>';
                                        } else {
                                            ?>
                                        <button class="btn btn-sm badge bg-danger text-white" data-toggle="modal" data-target="#active_user" data-user-id="<?= $user['id'] ?>">Not Active</button>
                                      <?php 
                                       }
                                        ?>
                                    </td> 
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function () {
    $('#active_user').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        fetchUserData(userId);
        
        // Attach the user-id data to the activate_user button
        $('#activate_user').data('user-id', userId);
    });

    $('#activate_user').click(function () {
        var userId = $(this).data('user-id');
        activateUser(userId);
    });
});

function fetchUserData(userId) {
    $.ajax({
        url: 'fetch_user_details.php',
        type: 'POST',
        data: { user_id: userId },
        success: function (data) {
            $('#user-details').html(data);
        }
    });
}
function activateUser(userId) {
    $.ajax({
        url: 'update_user_status.php',
        type: 'POST',
        data: { user_id: userId },
        success: function (data) {
            $('#active_user').modal('hide');
            // You can handle the success response here
            console.log(data);
            
            // Show a success alert
            alert('User activated successfully!');

            // Reload the page after a short delay (e.g., 1000 milliseconds or 1 second)
            setTimeout(function () {
                location.reload();
            }, 1000);
        },
        error: function (error) {
            // You can handle the error response here, e.g., show an error message
            console.log(error.responseText);
        }
    });
}



</script>

<?php
include_once 'layouts/footer.php';
?>
