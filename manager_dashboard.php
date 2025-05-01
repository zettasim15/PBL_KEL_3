<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Import the database connection
include('koneksi.php');

// Start session to get the user ID
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information from the database
$sql = "SELECT username, role, profile_image, is_first_login FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if it's the first login
if ($user['is_first_login'] == 1) {
    header('Location: customize_profile.php');
    exit();
}

// If the user doesn't have a profile image, use a default image
$profile_image = isset($user['profile_image']) && $user['profile_image'] ? $user['profile_image'] : 'default_profile.png';

// Query to get the number of schedules
$sql_schedules = "SELECT COUNT(*) as schedule_count FROM schedule_list WHERE assignee = ?";
$stmt_schedules = $conn->prepare($sql_schedules);
if ($stmt_schedules === false) {
    die("MySQL prepare error: " . $conn->error . " | Query: " . $sql_schedules);
}
$stmt_schedules->bind_param("i", $user_id);
$stmt_schedules->execute();
$result_schedules = $stmt_schedules->get_result();
$schedule_data = $result_schedules->fetch_assoc();
$schedule_count = $schedule_data['schedule_count'] ?? 0;

// Query to get the number of teams
$sql_teams = "SELECT COUNT(*) as team_count FROM teams WHERE created_by = ?";
$stmt_teams = $conn->prepare($sql_teams);
if ($stmt_teams === false) {
    die("MySQL prepare error: " . $conn->error . " | Query: " . $sql_teams);
}
$stmt_teams->bind_param("i", $user_id);
$stmt_teams->execute();
$result_teams = $stmt_teams->get_result();
$team_data = $result_teams->fetch_assoc();
$team_count = $team_data['team_count'] ?? 0;

// Query to get the number of invited members
$sql_members = "SELECT COUNT(*) as member_count FROM team_members WHERE team_id IN (SELECT team_id FROM teams WHERE created_by = ?)";
$stmt_members = $conn->prepare($sql_members);
if ($stmt_members === false) {
    die("MySQL prepare error: " . $conn->error . " | Query: " . $sql_members);
}
$stmt_members->bind_param("i", $user_id);
$stmt_members->execute();
$result_members = $stmt_members->get_result();
$member_data = $result_members->fetch_assoc();
$member_count = $member_data['member_count'] ?? 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="sidebar">
    <div class="logo_details">
        <img src="meetingsreminder.png" alt="logo_icon">
        <div class="logo_name">TimetoMeet</div>
        <i class="bx bx-menu" id="btn"></i>
    </div>
    <ul class="nav-list">
    <li>
                <i class="bx bx-search"></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="manager_dashboard.php">
                    <i class="bx bxs-home"></i>
                    <span class="link_name">Home</span>
                </a>
                <span class="tooltip">Home</span>
            </li>
            <li>
                <a href="Manage.php">
                    <i class="bx bxs-folder-plus"></i>
                    <span class="link_name">Manage</span>
                </a>
                <span class="tooltip">Manage</span>
            </li>
            <li>
                <a href="kalender.php">
                    <i class="bx bxs-calendar"></i>
                    <span class="link_name">Calendar</span>
                </a>
                <span class="tooltip">Calendar</span>
            </li>
            <li>
                <a href="invitemember.php">
                    <i class="bx bxs-group"></i>
                    <span class="link_name">Invite Member</span>
                </a>
                <span class="tooltip">Invite Member</span>
            </li>
            <li>
                <a href="presence_manager.php">
                    <i class="bx bx-user-check"></i>
                    <span class="link_name">Presence</span>
                </a>
                <span class="tooltip">Presence</span>
            </li>
            <li>
                <a href="task_manager.php">
                    <i class="bx bx-task-x"></i>
                    <span class="link_name">Tasks</span>
                </a>
                <span class="tooltip">Tasks</span>
            </li>
        <li class="profile">
            <div class="profile_details">
                <img src="<?= htmlspecialchars($profile_image); ?>" alt="profile image">
                <div class="profile_content">
                    <div class="name"><?= htmlspecialchars($_SESSION['display_username']); ?></div>
                    <div class="designation"><?= htmlspecialchars($user['role']); ?></div>
                </div>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" id="log_out" class="bx bx-log-out"></button>
            </form>
        </li>
    </ul>
</div>  
<div class="home-section" style="left : 5px">
    <div class="container mt-5">
        <h3>Manager Dashboard</h3>
        <div class="row">
            <!-- Schedules Card -->
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Schedules</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $schedule_count; ?> Schedules</h5>
                        <p class="card-text">Total schedules assigned by you</p>
                    </div>
                </div>
            </div>
            <!-- Teams Card -->
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Teams</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $team_count; ?> Teams</h5>
                        <p class="card-text">Teams you manage</p>
                    </div>
                </div>
            </div>
            <!-- Members Card -->
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">Members</div>
                    <div class="card-body">
                        <h5 class="card-title"><?= $member_count; ?> Members</h5>
                        <p class="card-text">Members invited to your teams</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="man.js"></script>
</body>
</html>