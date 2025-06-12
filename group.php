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
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Jika user tidak ditemukan
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

// Ambil daftar tim yang dimiliki atau diikuti oleh member
// Ambil daftar tim yang dimiliki atau diikuti oleh member
$sql_teams = "
    SELECT t.team_id, t.name AS team_name, created_at, tm.status
    FROM team_members tm
    JOIN teams t ON tm.team_id = t.team_id
    WHERE tm.user_id = ?
";


$stmt_teams = $conn->prepare($sql_teams);

// Periksa apakah prepare berhasil
if (!$stmt_teams) {
    die("Error in prepare statement: " . $conn->error);
}

$stmt_teams->bind_param("i", $user_id);
$stmt_teams->execute();
$result_teams = $stmt_teams->get_result();

$teams = [];
if ($result_teams->num_rows > 0) {
    while ($row = $result_teams->fetch_assoc()) {
        $teams[] = $row;
    }
}
if (!$stmt_teams) {
    die("Prepare failed: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <link rel="stylesheet" href="notif.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                <span class="link_name">Presence</span>
            </a>
            <span class="tooltip">Presence</span>
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

<div class="home-section">
    <h1 class="mb-4">Teams</h1>
    <?php if (count($teams) > 0): ?>
        <div class="row">
            <?php foreach ($teams as $team): ?>
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <!-- Display Team Name -->
                            <h5 class="card-title"><?= htmlspecialchars($team['team_name']); ?></h5>
                            
                            <!-- Display Team Status -->
                            <p class="card-text">
                                <strong>Status:</strong>
                                <?= $team['status'] === 'pending' ? 'Pending' : 'Active'; ?>
                            </p>

                            <!-- Display buttons if status is pending -->
                            <?php if ($team['status'] === 'pending'): ?>
                                <form action="update_status.php" method="POST">
                                    <input type="hidden" name="team_id" value="<?= $team['team_id']; ?>">
                                    <input type="hidden" name="user_id" value="<?= $user_id; ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            You are not part of any team yet.
        </div>
    <?php endif; ?>
</div>



<!-- Add Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="group.js"></script>
</body>
</html>
