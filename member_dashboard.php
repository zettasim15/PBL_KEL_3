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

// Ambil daftar tugas dari database berdasarkan user yang login
$sql_tasks = "SELECT * FROM tasks WHERE member_id = ?";
$stmt_tasks = $conn->prepare($sql_tasks);
$stmt_tasks->bind_param("i", $user_id);
$stmt_tasks->execute();
$result_tasks = $stmt_tasks->get_result();


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

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Tugas & Kalender</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- FullCalendar & jQuery -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.10.2/dist/fullcalendar.min.js"></script>

  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 50;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 20px;
      border-radius: 8px;
      width: 90%;
      max-width: 500px;
    }
    .btn {
      padding: 0.5rem 1rem;
      margin-right: 0.5rem;
      border-radius: 4px;
      cursor: pointer;
    }
    .btn-success { background-color: #38a169; color: white; }
    .btn-danger { background-color: #e53e3e; color: white; }
    .btn-secondary { background-color: #718096; color: white; }
  </style>
</head>
<body class="bg-gray-100">

  <main class="ml-64 p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div id="calendar" class="bg-white rounded-lg shadow-md p-4"></div>
    <div class="bg-white rounded-lg shadow-md p-4 w-full">
      <div class="flex items-center justify-between mb-4">
        <select class="border p-1 rounded text-sm"><option>Next 7 days</option><option>Today</option></select>
        <select class="border p-1 rounded text-sm"><option>Sort by dates</option><option>Sort by name</option></select>
        <input type="text" placeholder="Search by task or name" class="border p-1 rounded text-sm w-1/3">
      </div>
      <table class="w-full text-sm border border-gray-200">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="p-2 border-b">Tanggal</th>
            <th class="p-2 border-b">Tugas</th>
            <th class="p-2 border-b">Deskripsi</th>
            <th class="p-2 border-b">Waktu</th>
            <th class="p-2 border-b">Status</th>
            <th class="p-2 border-b">Aksi</th>
          </tr>
        </thead>
        <tbody>
<?php while ($row = $result_tasks->fetch_assoc()) : ?>
  <tr class="<?= ($row['deadline'] < date('Y-m-d')) ? 'bg-red-50 border-l-4 border-red-500' : ''; ?>">
    <td class="p-2"><?= date('l, j F Y', strtotime($row['deadline'])); ?></td>
    <td class="p-2 font-semibold"><?= htmlspecialchars($row['nama']); ?></td>
    <td class="p-2 text-gray-600"><?= htmlspecialchars($row['deskripsi']); ?></td>
    <td class="p-2 text-xs text-gray-500">20:00</td>
    <td class="p-2">
      <span class="text-xs px-2 py-0.5 rounded
        <?= ($row['status'] === 'Selesai') ? 'bg-green-500 text-white' :
             (($row['deadline'] < date('Y-m-d')) ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white'); ?>">
        <?= $row['status']; ?>
      </span>
    </td>
    <td class="p-2">
      <a href="submit_task.php?id=<?= $row['id']; ?>" class="text-blue-600 border border-blue-600 px-2 py-1 rounded hover:bg-blue-50">
        Add submission
      </a>
    </td>
  </tr>
<?php endwhile; ?>
</tbody>

      </table>
      <button class="mt-4 text-blue-600 hover:underline text-sm">Show more activities</button>
    </div>
  </main>

  <div id="schedule-modal" class="modal">
    <div class="modal-content">
      <h4 class="text-lg font-bold mb-2">Schedule Details</h4>
      <p id="schedule-description" class="mb-4"></p>
      <button id="accept-schedule" class="btn btn-success">Accept</button>
      <button id="reject-schedule" class="btn btn-danger">Reject</button>
      <button id="close-modal" class="btn btn-secondary">Close</button>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#calendar').fullCalendar({
        events: 'load_schedules.php',
        eventClick: function(event) {
          $('#schedule-description').text(event.description || '(tidak ada deskripsi)');
          $('#accept-schedule').attr('data-schedule-id', event.id);
          $('#reject-schedule').attr('data-schedule-id', event.id);
          $('#schedule-modal').show();
        }
      });

      $('#accept-schedule').click(function() {
        var id = $(this).data('schedule-id');
        $.post('accept_reject_schedule.php', { schedule_id: id, action: 'accept' }, function(res) {
          $('#schedule-modal').hide();
          location.reload();
        });
      });

      $('#reject-schedule').click(function() {
        var id = $(this).data('schedule-id');
        $.post('accept_reject_schedule.php', { schedule_id: id, action: 'reject' }, function(res) {
          $('#schedule-modal').hide();
          location.reload();
        });
      });

      $('#close-modal').click(function() {
        $('#schedule-modal').hide();
      });
    });
  </script>

  <script src="group.js"></script>
  <script src="./js/script.js"></script>
  <script src="kalender.js"></script>
</body>
</html>
