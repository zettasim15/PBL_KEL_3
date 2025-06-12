<?php
// Koneksi ke database dengan PDO
$host = 'localhost';
$dbname = 'TimetoMeet'; // Ganti dengan nama database kamu
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Simpan data jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO tasks (nama, deadline, status, deskripsi) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $deadline, $status, $deskripsi]);

    // Redirect agar form tidak tersubmit ulang saat refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Ambil data tugas dari database
$stmt = $conn->query("SELECT * FROM tasks ORDER BY deadline ASC");
$tugas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                <img src="<?= isset($profile_image) ? htmlspecialchars($profile_image) : 'default.png'; ?>" alt="profile image">
                <div class="profile_content">
                    <div class="name"><?= isset($_SESSION['display_username']) ? htmlspecialchars($_SESSION['display_username']) : 'Guest'; ?></div>
                    <div class="designation"><?= isset($user['role']) ? htmlspecialchars($user['role']) : ''; ?></div>
                </div>
            </div>
            <form action="logout.php" method="POST">
                <button type="submit" id="log_out" class="bx bx-log-out"></button>
            </form>
        </li>
    </ul>
</div>

<div class="content">
    <h2>Daftar Task</h2>

    <form method="POST" action="simpan_action.php">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Task</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($sconn, "SELECT * FROM tasks");
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['task']); ?></td>
                        <td>
                            <input type="hidden" name="id[]" value="<?= $row['id']; ?>">
                            <select name="action[]">
                                <option value="Task Manage" <?= ($row['action'] == 'Task Manage') ? 'selected' : '' ?>>Task Manage</option>
                                <option value="Task Member" <?= ($row['action'] == 'Task Member') ? 'selected' : '' ?>>Task Member</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <div style="text-align:center;">
            <button type="submit">Simpan Perubahan</button>
        </div>
    </form>
</div>
