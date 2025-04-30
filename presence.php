<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>presence</title>
    <link rel="stylesheet" href="presence.css">
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
            <li>
            <li class="profile">
                <div class="profile_details">
                    <img src="fox.jpg" alt="profile image">
                    <div class="profile_content">
                        <div class="name"><?php echo "Night Fox"; ?></div>
                        <div class="designation"><?php echo "Admin"; ?></div>
                    </div>
                </div>
                <i class="bx bx-log-out" id="log_out"></i>
            </li>
        </ul>
    </div>
    <script src="presence.js"></script>
    <?php
// Inisialisasi array jika form disubmit
$dataAbsensi = [];

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];

    // Simpan ke array
    $dataAbsensi[] = [
        'nama' => $nama,
        'tanggal' => $tanggal,
        'status' => $status
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Presence</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Presence list</h2>

    <form action="" method="POST">
        <input type="text" name="nama" placeholder="Nama Anggota" required>
        <input type="date" name="tanggal" required>
        <select name="status" required>
            <option value="">choose status</option>
            <option value="Hadir">Present</option>
            <option value="Tidak Hadir">Absent</option>
            <option value="Izin">Excused</option>
        </select>
        <button type="submit" name="submit">Add</button>
    </form>

    <?php if (!empty($dataAbsensi)): ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataAbsensi as $data): ?>
                <tr>
                    <td><?php echo $data['nama']; ?></td>
                    <td><?php echo $data['tanggal']; ?></td>
                    <td><?php echo $data['status']; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No absence yet.</td>
                </tr>
        </tbody>
    </table>
    <?php endif; ?>
</body>
</html>

</body>
</html>

