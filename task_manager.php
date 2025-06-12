<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include('koneksi.php');

$user_id = $_SESSION['user_id'];

// Ambil data user
$sql_user = "SELECT username, role, profile_image FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$profile_image = $user['profile_image'] ?? 'default_profile.png';
$_SESSION['display_username'] = $user['username'];

// Ambil semua tugas dari member di tim manager ini
$sql_tasks = "
SELECT t.nama, t.deadline, t.status, t.deskripsi, u.username 
FROM tasks t
JOIN users u ON t.member_id = u.id
WHERE t.member_id IN (
    SELECT member_id FROM team_members
    WHERE team_id IN (
        SELECT team_id FROM teams WHERE created_by = ?
    )
)
ORDER BY t.deadline ASC
";

$stmt_tasks = $conn->prepare($sql_tasks);
$stmt_tasks->bind_param("i", $member_id);
$stmt_tasks->execute();
$tugas_result = $stmt_tasks->get_result();
$tugas_data = $tugas_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Task 2</title>
    <link rel="stylesheet" href="task2.css">
    <link rel="stylesheet" href="presence.css"> <!-- sidebar style -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                <i class='bx bx-user-check'></i>
                <span class="link_name">Attendance</span>
            </a>
            <span class="tooltip">Attendance</span>
        </li>
            <li>
                <a href="task2.php">
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
    <!-- Konten tugas -->
    <div class="content">
        <h1>Task list</h1>
        <!-- Tugas dari semua member -->
<!-- Tugas dari semua member -->
<table>
  <tr>
    <th>Nama Member</th>
    <th>Task</th>
    <th>Deadline</th>
    <th>Status</th>
    <th>Deskripsi</th>
  </tr>

  <?php if (!empty($tugas_data)): ?>
      <?php foreach ($tugas_data as $t): ?>
          <tr>
              <td><?= htmlspecialchars($t['username']) ?></td>
              <td><?= htmlspecialchars($t['nama']) ?></td>
              <td><?= htmlspecialchars($t['deadline']) ?></td>
              <td><?= htmlspecialchars($t['status']) ?></td>
              <td><?= htmlspecialchars($t['deskripsi']) ?></td>
          </tr>
      <?php endforeach; ?>
  <?php else: ?>
      <tr>
          <td colspan="5">Tidak ada data tugas yang tersedia.</td>
      </tr>
  <?php endif; ?>

</table>


    </div>

    <script src="presence.js"></script>
</body>
</html>
