<?php
session_start();
$isLoggedIn = $_SESSION['user_id'];
$userRole = $_SESSION['role_id'];

// Check if user is logged in and has admin role (role_id = 1)
if (!$isLoggedIn || $userRole !== 1) {
    $_SESSION['error_message'] = "You do not have permission to access the dashboard.";
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CSS CDN link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="asset/css/dashboard.css">
    <style>
    .btn-color{
    background-color: #0e1c36;
    color: #fff;
    
  }
    </style>
    
</head>
<body>
    <div class="container-fluid">
        <div class="row">
          <!-- sidebar -->
          <div class="col-md-3 col-lg-2 px-0 position-fixed h-100 bg-white shadow-sm sidebar" id="sidebar">
            <div class="list-group rounded-0" >
            <a href="dashboard.php" class="list-group-item list-group-item-action border-0 d-flex align-items-center" style="  background-color: #0e1c36;color: #fff;">
                <span class="bi bi-border-all"></span>
                <span class="ml-2">Dashboard</span>
              </a>
              <button class=" sidebar_item list-group-item  list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#users">
                <div>
                  <span class="bi bi-people-fill"></span>
                  <span class="ml-2">Users </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
              </button>
              <div class="collapse" id="users" data-parent="#sidebar">
                <div class="list-group">
                  <a href="users_list.php" class="list-group-item list-group-item-action border-0 pl-5">User List</a>
                  <a href="user_working_hours.php" class="list-group-item list-group-item-action border-0 pl-5">User working hours</a>
                </div>
              </div>

              <button class=" sidebar_item list-group-item  list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#leaving">
                <div>
                  <span class="bi bi-clock-fill"></span>
                  <span class="ml-2">Leaving </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
              </button>
              <div class="collapse" id="leaving" data-parent="#sidebar">
                <div class="list-group">
                  <a href="leaving_list.php" class="list-group-item list-group-item-action border-0 pl-5">Leaving List</a>
                  <!-- <a href="user_working_hours.php" class="list-group-item list-group-item-action border-0 pl-5">User working hours</a> -->
                </div>
              </div>

              <button class=" sidebar_item list-group-item  list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#vacation">
                <div>
                  <span class="bi bi-calendar-x-fill"></span>
                  <span class="ml-2">Vacation </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
              </button>
              <div class="collapse" id="vacation" data-parent="#sidebar">
                <div class="list-group">
                  <a href="vacation_list.php" class="list-group-item list-group-item-action border-0 pl-5">vacation List</a>
                  <!-- <a href="user_working_hours.php" class="list-group-item list-group-item-action border-0 pl-5">User working hours</a> -->
                </div>
                <!--please this vacation test -->
              </div>

              <button class="sidebar_item list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#reports">
                <div>
                  <span class="bi bi-folder-symlink"></span>
                  <span class="ml-2">Reports  </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
              </button>
              <div class="collapse" id="reports" data-parent="#sidebar">
                <div class="list-group">
                  <a href="../reports/reports.php" class="list-group-item list-group-item-action border-0 pl-5">Reports</a>

                </div>
              </div>

              <button class="sidebar_item list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center" data-toggle="collapse" data-target="#settings">
                <div>
                  <span class="bi bi-gear-wide-connected"></span>
                  <span class="ml-2">Settings  </span>
                </div>
                <span class="bi bi-chevron-down small"></span>
              </button>
              <div class="collapse" id="settings" data-parent="#sidebar">
                <div class="list-group">
                  <a href="../settings/settings.php" class="list-group-item list-group-item-action border-0 pl-5">settings</a>

                </div>
              </div>
            </div>
          </div>
          <div class="w-100 vh-100 position-fixed overlay d-none" id="sidebar-overlay"></div>
          <div class="col-md-9 col-lg-10 ml-md-auto px-0" >
        
          <nav class="w-100 d-flex px-4 mb-4 shadow" style="padding-bottom: 8px;background-color: var(--header-color); color: var(--header-text-color);">

                <button class="btn py-0 d-lg-none" id="open-sidebar">
                    <span class="bi bi-list text-info h3"></span>
                </button>

                <?php if ($isLoggedIn): ?>
                    <div class="ml-auto">
                        <form method="post" action="logout.php">
                            <button class="btn btn-danger btn-sm mt-2" type="submit">Logout</button>
                        </form>
                    </div>
                <?php endif; ?>
                </nav>