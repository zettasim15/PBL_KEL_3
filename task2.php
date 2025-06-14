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

<!-- Tabel tugas -->
<div class="container">
    <h2>Tambah Tugas</h2>
    <form method="POST" action="">
        <input type="text" name="nama" placeholder="Nama Tugas" required>
        <input type="date" name="deadline" required>
        <select name="status" required>
            <option value="Belum Selesai">Belum Selesai</option>
            <option value="Dalam Proses">Dalam Proses</option>
            <option value="Selesai">Selesai</option>
        </select>
        <input type="text" name="deskripsi" placeholder="Deskripsi">
        <button type="submit" name="submit">Tambah</button>
    </form>

    <h1>Task List</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Task</th>
            <th>Deadline</th>
            <th>Status</th>
            <th>Description</th>
        </tr>
        <?php if (!empty($tugas)) : ?>
            <?php foreach ($tugas as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['nama']) ?></td>
                    <td><?= htmlspecialchars($t['deadline']) ?></td>
                    <td>
                        <select disabled>
                            <option <?= $t['status'] == 'Belum Selesai' ? 'selected' : '' ?>>Belum Selesai</option>
                            <option <?= $t['status'] == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                            <option <?= $t['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </td>
                    <td><?= htmlspecialchars($t['deskripsi']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">Belum ada tugas.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script src="presence.js"></script>
</body>
</html>
