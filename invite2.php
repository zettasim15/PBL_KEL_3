<?php
// Dummy data untuk testing (ganti dengan query DB jika sudah connect)
$members = ['Tata', 'Gadiza', 'Zeta'];
$teams = ['Employee', 'Treasurer'];

// Proses form (opsional bisa kamu kembangkan)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['invite'])) {
        $invited = $_POST['username'];
        // Simpan ke database / logika kirim undangan
    } elseif (isset($_POST['create_team'])) {
        $new_team = $_POST['team_name'];
        // Simpan ke database / logika buat tim
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite</title>
    <link rel="stylesheet" href="invite2.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }

        .box {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            color: white;
        }

        .box.blue {
            background-color: #BCDDC3;
        }

        .box.green {
            background-color: #BCDDC3;
        }

        input, select, button {
            display: block;
            margin-top: 10px;
            padding: 8px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #96D3DA;
            color: white;
            border: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .detail-btn {
            background-color: #00cfe8;
            color: black;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo_details">
            <img src="meetingsreminder.png" alt="logo_icon" width="50">
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
                <a href="dashboard.php">
                    <i class="bx bx-home"></i>
                    <span class="link_name">Home</span>
                </a>
                <span class="tooltip">Home</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-calendar-check"></i>
                    <span class="link_name">Manage</span>
                </a>
                <span class="tooltip">Manage</span>
            </li>
            <li>
                <a href="InviteMember.php">
                    <i class="bx bxs-user-plus"></i>
                    <span class="link_name">Invite Member</span>
                </a>
                <span class="tooltip">Invite Member</span>
            </li>
            <li>
                <a href="#">
                    <i class="bx bx-calendar"></i>
                    <span class="link_name">Calendar</span>
                </a>
                <span class="tooltip">Calendar</span>
            </li>
            <li>
            <a href="presence.php">
                <i class='bx bx-user-check'></i>
                <span class="link_name">Attendance</span>
            </a>
            <span class="tooltip">Attendance</span>
        </li>
            <li>
                <a href="task.php">
                    <i class="bx bx-home"></i>
                    <span class="link_name">Task</span>
                </a>
                <span class="tooltip">Task</span>
            </li>
            <li class="profile">
            <div class="profile_details">
    <img src="<?= htmlspecialchars($profile_image); ?>" alt="profile image">
    <div class="profile_content">
        <div class="name"><?= htmlspecialchars($_SESSION['display_username']); ?></div>
        <div class="designation"><?= htmlspecialchars($user['role']); ?></div>
    </div>
</div>

    <div class="content">
        <h1>Invite Member</h1>

        <!-- Form kirim undangan -->
        <div class="box blue">
            <form method="POST">
                <label>Username:</label>
                <select name="username" required>
                    <option value="">Choose member</option>
                    <?php foreach ($members as $member): ?>
                        <option value="<?= $member ?>"><?= $member ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="invite">Send invites</button>
            </form>
        </div>

        <!-- Form buat tim baru -->
        <div class="box green">
            <form method="POST">
                <label>group name:</label>
                <input type="text" name="team_name" placeholder="Enter group name" required>
                <button type="submit" name="create_team">make Team</button>
            </form>
        </div>

        <!-- Daftar Tim -->
        <h2>Group list</h2>
        <table>
            <tr><th>Group name</th><th>Details</th></tr>
            <?php foreach ($teams as $team): ?>
                <tr>
                    <td><?= $team ?></td>
                    <td><button class="detail-btn">Details</button></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script src="presence.js"></script>
</body>
</html>
