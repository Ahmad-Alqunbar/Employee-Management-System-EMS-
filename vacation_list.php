<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$usersVacationSystem = "SELECT u.*, v.vacation_from, v.vacation_to, v.duration, v.status,created_at,v.id as vacation_id
    FROM users u
    INNER JOIN vacation v ON u.id = v.user_id";
$resultUsersVacation = $conn->query($usersVacationSystem);
$usersLeaving = [];
while ($row = $resultUsersVacation->fetch_assoc()) {
    $usersLeaving[] = $row;
}
$today = date('Y-m-d');
?>
<main class="container-fluid">
    <!-- start Modal Accept vacation -->
    <div class="modal fade" id="accept_vacation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-color">
                    <h5 class="modal-title" id="exampleModalLabel">Accept Vacation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="user-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="accept_vacation_button">Accept</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Accept vacation -->
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Users Vacation</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($usersLeaving)) : ?>
                    <table class="table table-responsive-md table-responsive-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Vacation From</th>
                                <th>Vacation To</th>
                                <th>Duration</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usersLeaving as $user) : ?>
                                <tr>
                                    <td><?= $user['vacation_id'] ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['phone'] ?></td>
                                    <td><?= $user['vacation_from'] ?></td>
                                    <td><?= $user['vacation_to'] ?></td>
                                    <td><?= $user['duration'] . "  Day" ?></td>
                                    <td><?= $user['created_at'] ?></td>

                                    <td>
                                        <?php
                                        if ($user['status'] == 1) {
                                            echo '<span class="badge rounded-pill bg-success text-white">Accepted</span>';
                                        } else {
                                        ?>
                                            <button class="btn btn-sm rounded-pill badge bg-danger text-white" data-toggle="modal" data-target="#accept_vacation" data-vacation-id="<?= $user['vacation_id'] ?>">Not Accepted</button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p>No leaving Found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $('#accept_vacation').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var vacationId = button.data('vacation-id');
            // alert(leavingId);
            $('#accept_vacation_button').data('vacation-id', vacationId);
            fetchLeavingData(vacationId);
        });

        $('#accept_vacation_button').click(function() {

            var vacationId = $(this).data('vacation-id');
            // alert(leaveId);
            acceptLeave(vacationId);

        });
    });


    function fetchLeavingData(vacationId) {
        $.ajax({
            url: 'fetch_vacation_details.php',
            type: 'POST',
            data: {
                vacation_id: vacationId
            },
            success: function(data) {
                $('#user-details').html(data);
            }
        });
    }

    function acceptLeave(vacationId) {
        $.ajax({
            url: 'accept_vacation.php',
            type: 'POST',
            data: {
                vacation_id: vacationId
            },
            success: function(data) {
                console.log(data);
                if (data.trim() === 'vacation request accepted successfully!') {
                    $('#accept_vacation').modal('hide');
                    alert('vacation accepted successfully!');
                    location.reload();
                } else {
                    console.log('Unexpected response from the server:', data);
                }
            },
            error: function(error) {
                console.log(error.responseText);
            }
        });
    }
</script>
<?php
include_once 'layouts/footer.php';
?>