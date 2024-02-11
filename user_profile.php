<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page or perform other actions
    header("Location: index.php");
    exit();
}

// Include your database configuration file and establish a connection
require_once 'config/Database.php';
$db = new Database();
$conn = $db->getConnection();

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch all user data based on user ID
$query = "SELECT * FROM ems.users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

$currentMonth = date('Y-m');

$queryVacations = "SELECT * FROM vacation WHERE user_id = ? AND DATE_FORMAT(vacation_from, '%Y-%m') = ?";
$stmtVacations = $conn->prepare($queryVacations);
$stmtVacations->bind_param('is', $user_id, $currentMonth);
$stmtVacations->execute();
$resultVacations = $stmtVacations->get_result();
$vacations = $resultVacations->fetch_all(MYSQLI_ASSOC);
$stmtVacations->close();

$queryleaveing = "SELECT * FROM leaving WHERE user_id = ? AND DATE_FORMAT(leaving_from, '%Y-%m') = ?";
$stmtleaving = $conn->prepare($queryleaveing);
$stmtleaving->bind_param('is', $user_id, $currentMonth);
$stmtleaving->execute();
$resultLeaving = $stmtleaving->get_result();
$leaving = $resultLeaving->fetch_all(MYSQLI_ASSOC);
$stmtleaving->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="asset/css/bootstrap.min.css">
    <style>

.counter-container {
  width: 200px;
  height: 200px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.counter {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  background-color: #f1f1f1;
}

.counter-progress {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  /* background-color: #ff6b6b; */
  transform-origin: center;
  transform: rotate(0deg);
  transition: transform 1s linear;
}

.counter-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-family: Arial, sans-serif;
  font-size: 24px;
  /* color: #333; */
}

.counter-value {
  font-weight: bold;
}


    </style>
</head>

<body>

    <div class="container mt-4">
        <!-- Modal vacation -->
        <div class="modal fade" id="vacation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="exampleModalLabel">Request Vacation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form action="" class="form">
                                <div class="mb-3">
                                    <label for="vacation_from" class="form-label">Vacation From:</label>
                                    <input type="date" class="form-control" id="vacation_from" name="vacation_from">
                                </div>

                                <div class="mb-3">
                                    <label for="vacation_from" class="form-label">Vacation To:</label>
                                    <input type="date" class="form-control" id="vacation_to" name="vacation_to">
                                </div>
                                <div class="mb-3">
                                    <label for="vacation " class="form-label">Reason for Vacation</label>
                                    <textarea class="form-control" id="reason_vacation" name="reason_vacation"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning" id="vacation-submit" onclick="submitVacationRequest('vacation')">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Leaving  -->
        <div class="modal fade" id="leaving" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="exampleModalLabel">Request Leaving</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" class="form">
                            <div class="mb-3">
                                <label for="leaving_date" class="form-label">Leaving Date</label>
                                <input type="date" class="form-control" id="leaving_date" name="leaving_date">
                            </div>
                            <div class="mb-3">
                                <label for="leaving_from" class="form-label">Leaving From:</label>
                                <input type="time" class="form-control" id="leaving_from" name="leaving_from">
                            </div>

                            <div class="mb-3">
                                <label for="leaving_from" class="form-label">Leaving To:</label>
                                <input type="time" class="form-control" id="leaving_to" name="leaving_to">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" onclick="submitRequest('leaving')">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="contanier card text-center">
                    <div class="card-header" style="background-color: #0e1c36; color: #fff;">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#vacation">Vacation</button>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#leaving">Leaving</button>

                            <a href="logout.php" class="btn btn-sm btn-danger" onclick="stopTimer()">Logout</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-6 mt-5">

                <div class="card">
                    <div class="card-header">
                        Your Information
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Name : <?php echo $user_data['name']; ?></li>
                        <li class="list-group-item">User name : <?php echo $user_data['user_name']; ?></li>
                        <li class="list-group-item">Email : <?php echo $user_data['email']; ?> </li>
                        <li class="list-group-item">Phone : <?php echo $user_data['phone']; ?> </li>

                    </ul>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                
                <div class="card">
                    <div class="card-header text-center" style="background-color: #0e1c36; color: #fff;">
                    Elapsed Time
                    </div>
                    <div class="card-body">
                    <div class=" d-flex justify-content-center">
                    <div class="counter "style="background-color: #0e1c36; color: #fff;">
                        <div class="counter-progress"></div>
                                <div class="counter-text">
                                <span  class="counter-value" id="elapsed-time"></span>
                                </div>
                        </div>
                    </div>
               </div>
                    </div>
              
                </div>
              
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">
                        Leaving
                    </div>
                    <div class="table-responsive" style="max-height:350px; overflow-y: auto;overflow-x: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> From</th>
                                    <th> To</th>
                                    <th>Duration</th>
                                    <th>Date</th>
                                    <th>status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaving as $leave) : ?>
                                    <tr>
                                        <td><?php echo $leave['leaving_from']; ?></td>
                                        <td><?php echo $leave['leaving_to']; ?></td>
                                        <td><?php echo ($leave['duration']); ?> minutes </td>
                                        <td><?php echo $leave['leaving_date']; ?></td>
                                        <td><?php if ($leave['status'] == 1) {
                                                echo '<span class="badge rounded-pill bg-success text-white">Accepted</span>';
                                            } else {
                                                echo '<span class="badge rounded-pill bg-danger text-white">Not Accepted</span>';
                                            }

                                            ?>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-5">
                <div class="card">
                    <div class="card-header">
                        Vacation
                    </div>
                    <div class="table-responsive" style="max-height:350px; overflow-y: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th> From</th>
                                    <th> To</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vacations as $vacation) : ?>
                                    <tr>
                                        <td><?php echo $vacation['vacation_from']; ?></td>
                                        <td><?php echo $vacation['vacation_to']; ?></td>
                                        <td><?php echo $vacation['duration']; ?> days</td>
                                        <td><?php if ($leave['status'] == 1) {
                                                echo '<span class="badge rounded-pill bg-success text-white">Accepted</span>';
                                            } else {
                                                echo '<span class="badge rounded-pill bg-danger text-white">Not Accepted</span>';
                                            }

                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>



    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script src="asset/js/bootstrap.bundle.min.js"></script>
    <!-- Include this script on your working page -->
    <!-- ... Your HTML head and body tags remain unchanged ... -->

    <script>
        let timer;

        function startTimer() {
            let startTime = <?php echo $_SESSION['login_time'] ?? 0; ?>;
            if (startTime) {
                timer = setInterval(function() {
                    let currentTime = Math.floor(Date.now() / 1000);
                    let elapsedSeconds = currentTime - startTime;
                    displayElapsedTime(elapsedSeconds);
                }, 1000);
            }
        }

        function displayElapsedTime(seconds) {
            // Convert seconds to hours, minutes, and remaining seconds
            let hours = Math.floor(seconds / 3600);
            let minutes = Math.floor((seconds % 3600) / 60);
            let remainingSeconds = seconds % 60;

            // Format the time as HH:MM:SS
            let formattedTime = `${padZero(hours)}:${padZero(minutes)}:${padZero(remainingSeconds)}`;

            // Update your HTML element to display the elapsed time
            document.getElementById('elapsed-time').innerHTML = formattedTime;
        }

        function stopTimer() {
            clearInterval(timer);
            // Set the logout time in the session when the user logs out
            <?php $_SESSION['logout_time'] = time(); ?>;
            // Redirect to logout.php to perform the logout logic
            window.location.href = 'logout.php';
        }

        // Function to pad zero for single-digit numbers
        function padZero(num) {
            return num < 10 ? `0${num}` : num;
        }

        // Call startTimer() when the page loads
        startTimer();

        function submitRequest(type) {
            let leaving_date = $('#' + type + '_date').val();
            let leaving_from = $('#' + type + '_from').val();
            let leaving_to = $('#' + type + '_to').val();

            // Validate the form data
            if (!leaving_date || !leaving_from || !leaving_to) {
                alert('Please fill in all fields.');
                return;
            }

            // Additional validations
            let today = new Date().toISOString().split('T')[0];
            if (leaving_date < today) {
                alert('Leaving date cannot be in the past.');
                return;
            }

            if (leaving_from >= leaving_to) {
                alert('Leave time "From" should be before "To".');
                return;
            }

            // AJAX request to insert data
            $.ajax({
                url: 'insert_leaving.php', // Adjust the URL to your server-side script
                method: 'POST',
                data: {
                    type: type,
                    leaving_date: leaving_date,
                    leaving_from: leaving_from,
                    leaving_to: leaving_to
                },
                success: function(response) {
                    // Handle the response, e.g., show a success message
                    console.log(response);
                    // Optionally close the modal
                    $('#' + type).modal('hide');
                },
                error: function(error) {
                    // Handle errors, e.g., show an error message
                    console.error(error.responseText);
                }
            });
        }




        // Attach a click event to the "Submit" button in the vacation modal
        function submitVacationRequest() {
            let vacationFrom = $('#vacation_from').val();
            let vacationTo = $('#vacation_to').val();
            let reasonVacation = $('#reason_vacation').val();

            // Validate the form data
            if (!vacationFrom || !vacationTo || !reasonVacation) {
                alert('Please fill in all fields.');
                return;
            }

            // Additional validations
            let today = new Date().toISOString().split('T')[0];
            if (vacationFrom < today || vacationTo < today) {
                alert('Vacation dates cannot be in the past.');
                return;
            }

            if (vacationFrom >= vacationTo) {
                alert('Vacation "From" date should be before "To" date.');
                return;
            }

            // Calculate duration
            let duration = calculateDuration(vacationFrom, vacationTo);

            // AJAX request to insert data
            $.ajax({
                url: 'insert_vacation.php', // Adjust the URL to your server-side script
                method: 'POST',
                data: {
                    vacation_from: vacationFrom,
                    vacation_to: vacationTo,
                    duration: duration,
                    reason_vacation: reasonVacation
                },
                success: function(response) {
                    // Handle the response, e.g., show a success message
                    console.log(response);
                    // Optionally close the modal
                    $('#vacation').modal('hide');
                },
                error: function(error) {
                    // Handle errors, e.g., show an error message
                    console.error(error.responseText);
                }
            });
        }

        function calculateDuration(fromDate, toDate) {
            // Parse the date strings to Date objects
            let fromDateObj = new Date(fromDate);
            let toDateObj = new Date(toDate);

            // Calculate the duration in days
            let durationInDays = Math.floor((toDateObj - fromDateObj) / (24 * 60 * 60 * 1000));

            // Add 1 to include both the start and end dates
            return durationInDays + 1;
        }

        // Reload the page every 5 minutes (300,000 milliseconds)
  setInterval(function () {
    location.reload();
  }, 300000);
    </script>


</body>

</html>