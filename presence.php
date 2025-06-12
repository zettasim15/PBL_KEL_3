<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=TimetoMeet", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>


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
            <div class="name"><?= htmlspecialchars($display_name); ?></div>
            <div class="designation"><?= htmlspecialchars($role); ?></div>
        </div>
    </div>
    <form action="logout.php" method="POST">
        <button type="submit" name="logout" id="log_out" style="background: none; border: none; cursor: pointer;">
            <i class="bx bx-log-out"></i>
        </button>
    </form>
</li>
        </ul>
    </div>
    <script src="presence.js"></script>
    <?php
// Inisialisasi array jika form disubmit
$dataAbsensi = [];
$stmt = $pdo->query("SELECT * FROM absensi ORDER BY id DESC");
$dataAbsensi = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $status = $_POST['status'];
    $foto = '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        $foto_name = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . time() . "_" . $foto_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            $foto = $target_file;
        }
    }

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO absensi (tanggal, status, foto) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $tanggal, $status, $foto]);
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

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="date" name="tanggal" required>
        <select name="status" required>
            <option value="">choose status</option>
            <option value="Hadir">Present</option>
            <option value="Tidak Hadir">Absent</option>
            <option value="Izin">Excused</option>
        </select>
         <input type="file" name="foto" accept="image/*" required>
        <button type="submit" name="submit">Add</button>
    </form>

    <?php if (!empty($dataAbsensi)): ?>
    <table class="presence-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Photo</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($dataAbsensi)): ?>
    <?php foreach ($dataAbsensi as $data): ?>
        <tr>
            <td><?php echo htmlspecialchars($data['tanggal']); ?></td>
            <td><?php echo htmlspecialchars($data['status']); ?></td>
            <td>
                <?php if (!empty($data['foto'])): ?>
                    <img src="<?php echo htmlspecialchars($data['foto']); ?>" style="width: 60px; height: 60px;">
                <?php else: ?>
                    No photo
                <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No absence yet.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
    <?php endif; ?>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>

</body>
</html>

