<?php
// Mengimpor file koneksi.php
include('koneksi.php');

// Mulai sesi untuk mendapatkan ID pengguna
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil informasi pengguna dari database
$sql = "SELECT username, role, profile_image, is_first_login FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);  // Show error if query preparation fails
}
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

// Periksa apakah ini adalah login pertama
if ($user['is_first_login'] == 1) {
    header('Location: customize_profile.php');
    exit();
}

// Jika pengguna tidak memiliki gambar profil, gunakan gambar default
$profile_image = isset($user['profile_image']) && $user['profile_image'] ? $user['profile_image'] : 'default_profile.png';

// Query untuk mendapatkan jumlah meetings
$sql_meetings = "SELECT COUNT(*) as meetings_count FROM meetings WHERE assignee = ?";
$stmt_meetings = $conn->prepare($sql_meetings);
if ($stmt_meetings === false) {
    die('MySQL prepare error: ' . $conn->error);  // Show error if query preparation fails
}
$stmt_meetings->bind_param("i", $user_id);
$stmt_meetings->execute();
$result_meetings = $stmt_meetings->get_result();
$meetings_data = $result_meetings->fetch_assoc();
$meetings_count = $meetings_data['meetings_count'];

// Query untuk mendapatkan jumlah schedule list
$sql_schedule_check = "SELECT COUNT(*) as schedule_count 
                       FROM schedule_list s
                       JOIN team_members tm ON tm.user_id = ? AND tm.status = 'active'
                       WHERE s.assignee = tm.user_id";
$stmt_schedule_check = $conn->prepare($sql_schedule_check); // Corrected the variable name
if ($stmt_schedule_check === false) {
    die('MySQL prepare error: ' . $conn->error);  // Show error if query preparation fails
}
$stmt_schedule_check->bind_param("i", $user_id);
$stmt_schedule_check->execute();
$result_schedule = $stmt_schedule_check->get_result(); // Use the correct prepared statement variable
$schedule_data = $result_schedule->fetch_assoc();
$schedule_count = $schedule_data['schedule_count'];

// Query untuk mendapatkan jumlah teams
$sql_teams = "SELECT COUNT(*) as team_count FROM team_members WHERE user_id = ? AND status = 'active'";
$stmt_teams = $conn->prepare($sql_teams);
if ($stmt_teams === false) {
    die('MySQL prepare error: ' . $conn->error);  // Show error if query preparation fails
}
$stmt_teams->bind_param("i", $user_id);
$stmt_teams->execute();
$result_teams = $stmt_teams->get_result();
$teams_data = $result_teams->fetch_assoc();
$teams_count = $teams_data['team_count'];
?>


<!-- Rest of the HTML content -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Member</title>
    <link rel="stylesheet" href="notif.css">
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
            <a href="member_dashboard.php">
                <i class="bx bxs-home"></i>
                <span class="link_name">Home</span>
            </a>
            <span class="tooltip">Home</span>
        </li>
        <li>
            <a href="schedule.php">
                <i class="bx bxs-calendar-check"></i>
                <span class="link_name">Schedule</span>
            </a>
            <span class="tooltip">Schedule</span>
        </li>
        <li>
            <a href="kalendermember.php">
                <i class="bx bxs-calendar"></i>
                <span class="link_name">Calendar</span>
            </a>
            <span class="tooltip">Calendar</span>
        </li>
        <li>
            <a href="group.php">
                <i class="bx bxs-group"></i>
                <span class="link_name">Teams</span>
            </a>
            <span class="tooltip">Teams</span>
        </li>
        <li>
            <a href="presence.php">
                <i class='bx bx-user-check'></i>
                <span class="link_name">Attendance</span>
            </a>
            <span class="tooltip">Attendance</span>
        </li>
        <li>
            <a href="task2.php">
                <i class='bx bx-task-x'></i>
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

<!-- Dashboard Content -->
 <div class="home-section">
<div class="container mt-5">
    <h3 class>Member Dashboard</h3>

    <div class="row">
        <!-- Meetings Card -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Meetings</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $meetings_count; ?> Meetings</h5>
                    <p class="card-text">Meetings assigned to you</p>
                </div>
            </div>
        </div>

        <!-- Schedule Card -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Schedule</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $schedule_count; ?> Schedules</h5>
                    <p class="card-text">Schedules for your team</p>
                </div>
            </div>
        </div>

        <!-- Teams Card -->
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Teams</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $teams_count; ?> Teams</h5>
                    <p class="card-text">Teams you are a part of</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="group.js"></script>
</body>
</html>
