<?php
include_once 'layouts/header.php';
require_once 'config/Database.php';

$db = new Database();
$conn = $db->getConnection();

$usersLeavingSystem = "SELECT u.*, l.leaving_date, l.leaving_from, l.leaving_to, l.duration, l.status, l.id as leave_id
    FROM users u
    INNER JOIN leaving l ON u.id = l.user_id";

$resultUsersLeaving = $conn->query($usersLeavingSystem);

$usersLeaving = [];
while ($row = $resultUsersLeaving->fetch_assoc()) {
    $usersLeaving[] = $row;
}
$today = date('Y-m-d');
?>

<main class="container-fluid">
    <!-- start Modal Reject Leave -->
    <div class="modal fade" id="reject_leave" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-color">
                    <h5 class="modal-title" id="exampleModalLabel">Reject Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="user-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="reject_leave_button">Reject</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Reject Leave -->

    <!-- start Modal Accept Leave -->
    <div class="modal fade" id="accept_leave" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-color">
                    <h5 class="modal-title" id="exampleModalLabel">Accept Leave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="user-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="accept_leave_button">Accept</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal Accept Leave -->
    <div class="container">
        <div class="card">
            <div class="card-header btn-color">
                <h4>Users Leaving</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($usersLeaving)) : ?>
                    <table class="table table-responsive-md table-responsive-sm table-responsive-lg">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Leaving From</th>
                                <th>Leaving To</th>
                                <th>Duration</th>
                                <th>Leaving Date</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usersLeaving as $user) : ?>
                                <tr>
                                    <td><?= $user['leave_id'] ?></td>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['phone'] ?></td>
                                    <td><?= $user['leaving_from'] ?></td>
                                    <td><?= $user['leaving_to'] ?></td>
                                    <td><?= $user['duration'] . "  minutes" ?></td>
                                    <td><?= $user['leaving_date'] ?></td>

                                    <td>
                                        <?php
                                        if ($user['status'] == 0) {
                                        ?>
                                            <button class="btn btn-sm rounded-pill badge bg-danger text-white" data-toggle="modal" data-target="#accept_leave" data-leave-id="<?= $user['leave_id'] ?>">Not Accepted</button>

                                        <?php

                                        } else {
                                        ?>
                                            <button class="btn btn-sm rounded-pill badge bg-success text-white" data-toggle="modal" data-target="#reject_leave" data-leave-id="<?= $user['leave_id'] ?>"> Accepted</button>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <a href="update_user_leaving.php?id=<?= $user['id'] ?> & leave_id=<?= $user['leave_id'] ?> & leaving_date=<?= $user['leaving_date'] ?>" class="btn btn-sm btn-color">Update</a>

                                        <button class="btn btn-sm btn-danger" onclick="confirmDeleteLeave(<?= $user['id'] ?>, <?= $user['leave_id'] ?>)">Delete</button>

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
        $('#accept_leave').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var leavingId = button.data('leave-id');
            $('#accept_leave_button').data('leave-id', leavingId);
            fetchLeavingData(leavingId, 'accept_leave');
        });

        $('#accept_leave_button').click(function() {
            var leaveId = $(this).data('leave-id');
            acceptOrRejectLeave(leaveId, 1);
        });

        $('#reject_leave').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var leavingId = button.data('leave-id');
            $('#reject_leave_button').data('leave-id', leavingId);
            fetchLeavingData(leavingId, 'reject_leave');
        });

        $('#reject_leave_button').click(function() {
            var leaveId = $(this).data('leave-id');
            acceptOrRejectLeave(leaveId, 0);
        });


    });


    function fetchLeavingData(leavingId) {
        $.ajax({
            url: 'fetch_leaving_details.php',
            type: 'POST',
            data: {
                leave_id: leavingId
            },
            success: function(data) {
                $('#user-details').html(data);
            }
        });
    }

    function fetchLeavingData(leavingId, modalId) {
        $.ajax({
            url: 'fetch_leaving_details.php',
            type: 'POST',
            data: {
                leave_id: leavingId
            },
            success: function(data) {
                $('#' + modalId + ' .modal-body #user-details').html(data);
            }
        });
    }

    function acceptOrRejectLeave(leaveId, status) {
        $.ajax({
            url: 'accept_leave.php',
            type: 'POST',
            data: {
                leave_id: leaveId,
                status: status
            },
            success: function(data) {
                console.log(data);
                if (data.trim() === 'Leave request accepted successfully!') {
                    $('#' + (status === 1 ? 'accept_leave' : 'reject_leave')).modal('hide');
                    alert('Leave ' + (status === 1 ? 'accepted' : 'rejected') + ' successfully!');
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

    function confirmDeleteLeave(userId, leaveId) {
        if (confirm('Are you sure you want to delete this leaving request?')) {
            deleteLeave(userId, leaveId);
        }
    }

    function deleteLeave(userId, leaveId) {
        $.ajax({
            url: 'delete_user_leaving.php',
            type: 'POST',
            data: {
                user_id: userId,
                leave_id: leaveId
            },
            success: function(data) {
                console.log(data);
                if (data.trim() === 'leave deleted successfully!') {
                    alert('Leaving request deleted successfully!');
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