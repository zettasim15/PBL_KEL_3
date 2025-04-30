<?php
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
    // If the user is not found
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

// Process the form to invite a member
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invite_member'])) {
    $invited_username = $_POST['username'];

    // Check if the invited username exists in the database
    $sql_check = "SELECT id FROM users WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $invited_username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $invited_user = $result_check->fetch_assoc();
        $invited_user_id = $invited_user['id'];

        // Get the last created team ID
        $sql_team_id = "SELECT team_id FROM teams WHERE created_by = ? ORDER BY created_at DESC LIMIT 1";
        $stmt_team_id = $conn->prepare($sql_team_id);
        $stmt_team_id->bind_param("i", $user_id);
        $stmt_team_id->execute();
        $team_result = $stmt_team_id->get_result();
        $team = $team_result->fetch_assoc();
        $team_id = $team['team_id'];

        // Insert the invite into the team_members table
        $sql_invite = "INSERT INTO team_members (team_id, user_id, role, status) VALUES (?, ?, 'member', 'pending')";
        $stmt_invite = $conn->prepare($sql_invite);
        $stmt_invite->bind_param("ii", $team_id, $invited_user_id);

        if ($stmt_invite->execute()) {
            $message = "Invite berhasil dikirim ke $invited_username.";
        } else {
            $message = "Terjadi kesalahan saat mengirim undangan.";
        }
    } else {
        $message = "Username tidak ditemukan.";
    }
}

// Process the form to create a new team
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_team'])) {
    $team_name = $_POST['team_name'];

    // Insert the new team into the teams table
    $sql_create_team = "INSERT INTO teams (name, created_by, created_at) VALUES (?, ?, NOW())";
    $stmt_create_team = $conn->prepare($sql_create_team);
    $stmt_create_team->bind_param("si", $team_name, $user_id);

    if ($stmt_create_team->execute()) {
        $team_message = "Tim '$team_name' berhasil dibuat.";
    } else {
        $team_message = "Terjadi kesalahan saat membuat tim.";
    }
}

// Fetch the list of teams created by the manager
$sql_teams = "SELECT * FROM teams WHERE created_by = ?";
$stmt_teams = $conn->prepare($sql_teams);
$stmt_teams->bind_param("i", $user_id);
$stmt_teams->execute();
$teams_result = $stmt_teams->get_result();

// Fetch all users with the 'member' role
$sql_members = "SELECT username FROM users WHERE role = 'member'";
$stmt_members = $conn->prepare($sql_members);
$stmt_members->execute();
$members_result = $stmt_members->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Responsive Styles -->
    <style>

        .home-section {
    margin-left: 5px; 
    padding: 20px;
    min-height: 100vh;
    width: 100%;
    background-color: #f8f9fa; 
    transition: all 0.3s ease; 
        }

    </style>
</head>

<div>
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

    <!-- Main Content -->
    <div class="home-section container py-5">
        <h3>Invite Member</h3>

        <!-- Notification Message -->
        <?php if (isset($message)): ?>
            <div class="alert alert-info text-center"><?= htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Invite Member Form -->
        <div class="card shadow mb-4" style="width: 100%;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Kirim Undangan ke Member</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username yang diundang:</label>
                        <select id="username" name="username" class="form-select" required>
                            <option value="">Pilih Member</option>
                            <?php while ($member = $members_result->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($member['username']); ?>">
                                    <?= htmlspecialchars($member['username']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" name="invite_member" class="btn btn-primary w-100">Kirim Undangan</button>
                </form>
            </div>
        </div>

        <!-- Create Team Form -->
        <div class="card shadow mb-4" style="width: 100%;">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Buat Tim Baru</h5>
            </div>
            <div class="card-body">
                <?php if (isset($team_message)): ?>
                    <div class="alert alert-success text-center"><?= htmlspecialchars($team_message); ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label for="team_name" class="form-label">Nama Tim:</label>
                        <input type="text" id="team_name" name="team_name" class="form-control" placeholder="Masukkan nama tim" required>
                    </div>
                    <button type="submit" name="create_team" class="btn btn-success w-100">Buat Tim</button>
                </form>
            </div>
        </div>

        <!-- Teams List -->
        <h2 class="text-center mb-4">Daftar Tim</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Tim</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($team = $teams_result->fetch_assoc()): ?>
                        <?php
                        $team_id = $team['team_id'];
                        $sql_team_members = "SELECT users.username FROM users JOIN team_members ON users.id = team_members.user_id WHERE team_members.team_id = ?";
                        $stmt_team_members = $conn->prepare($sql_team_members);
                        $stmt_team_members->bind_param("i", $team_id);
                        $stmt_team_members->execute();
                        $team_members_result = $stmt_team_members->get_result();
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($team['name']); ?></td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#teamDetailsModal<?= $team_id; ?>">
                                    Details
                                </button>
                            </td>
                        </tr>

                        <!-- Modal for Team Details -->
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal HTML Structure -->
    <div class="modal fade" id="teamDetailsModal<?= $team_id; ?>" tabindex="-1" aria-labelledby="teamDetailsLabel<?= $team_id; ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="teamDetailsLabel<?= $team_id; ?>">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Anggota Tim:</h6>
                    <ul>
                        <?php while ($member = $team_members_result->fetch_assoc()): ?>
                            <li><?= htmlspecialchars($member['username']); ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
                        </div>
                        </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="group.js"></script>
    </script>
</body>

</html>
