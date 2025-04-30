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
        <table>
            <tr>
                <th>Task</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
            <?php
            $tugas = [
                [
                    'nama' => 'Membuat laporan mingguan',
                    'deadline' => '2025-04-25',
                    'status' => 'Belum Selesai',
                    'deskripsi' => 'Laporan harus mencakup data kehadiran dan performa.'
                ],
                [
                    'nama' => 'Update Website',
                    'deadline' => '2025-04-27',
                    'status' => 'Dalam Proses',
                    'deskripsi' => 'Perbarui landing page dan tambahkan fitur login.'
                ],
                [
                    'nama' => 'Meeting dengan klien',
                    'deadline' => '2025-04-23',
                    'status' => 'Selesai',
                    'deskripsi' => 'Bahas kemajuan proyek dan feedback dari klien.'
                ]
            ];

            foreach ($tugas as $t): ?>
                <tr>
                    <td><?= $t['nama'] ?></td>
                    <td><?= $t['deadline'] ?></td>
                    <td>
                        <select>
                            <option <?= $t['status'] == 'Not finished' ? 'selected' : '' ?>>Not finished</option>
                            <option <?= $t['status'] == 'In proggres' ? 'selected' : '' ?>>In proggres</option>
                            <option <?= $t['status'] == 'Finished' ? 'selected' : '' ?>>Finished</option>
                        </select>
                    </td>
                    <td><?= $t['deskripsi'] ?></td>
                    <td><button class="edit-btn">Edit</button></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script src="presence.js"></script>
</body>
</html>
