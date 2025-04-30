<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dash.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="sidebar">
        <div class="logo_details">
            <img src="meetingsreminder.png" alt="logo_icon" width="50">
            <div class="logo_name">Meetings Reminder</div>
            <i class="bx bx-menu" id="btn"></i>
        </div>
        <ul class="nav-list">
            <li>
                <i class="bx bx-search"></i>
                <input type="text" placeholder="Search...">
                <span class="tooltip">Search</span>
            </li>
            <li>
                <a href="dashboard.html">
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
                <a href="InviteMember.html">
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
            <li class="profile">
                <div class="profile_details">
                    <img src="fox.jpg" alt="profile image">
                    <div class="profile_content">
                        <div class="name">Night Fox</div>
                        <div class="designation">Admin</div>
                    </div>
                </div>
                <i class="bx bx-log-out" id="log_out"></i>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="dashboard-content">
            <div class="statistic-cards">
                <div class="card">
                    <h3>Total Meetings Today</h3>
                    <p>5</p>
                </div>
                <div class="card">
                    <h3>Upcoming Meetings</h3>
                    <p>12</p>
                </div>
                <div class="card">
                    <h3>Pending Invitations</h3>
                    <p>3</p>
                </div>
                <div class="card">
                    <h3>Completed Meetings</h3>
                    <p>8</p>
                </div>
            </div>

            <div class="schedule-section">
                <h2>Meeting Schedule</h2>
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Meeting Title</th>
                            <th>Participants</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "meetings_reminder";

                        // Koneksi ke database
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Periksa koneksi
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Query jadwal meeting
                        $sql = "
                            SELECT 
                                s.start_datetime AS time, 
                                s.title AS meeting_title, 
                                GROUP_CONCAT(u.username SEPARATOR ', ') AS participants, 
                                s.description AS status
                            FROM schedule_list s
                            JOIN team_members tm ON s.team_id = tm.team_id
                            JOIN users u ON tm.user_id = u.id  -- Menyesuaikan dengan kolom id di tabel users
                            WHERE tm.status = 'active'
                            GROUP BY s.id
                        ";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . date("h:i A", strtotime($row['time'])) . "</td>
                                    <td>" . $row['meeting_title'] . "</td>
                                    <td>" . $row['participants'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No schedule available</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="group-section">
                <h2>Team Groups</h2>
                <table class="group-table">
                    <thead>
                        <tr>
                            <th>Group Name</th>
                            <th>Members</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Koneksi ulang untuk grup
                        $conn = new mysqli($servername, $username, $password, $dbname);

                        $sql = "
                            SELECT 
                                t.name AS team_name,  -- Menggunakan name dari tabel teams
                                GROUP_CONCAT(u.username SEPARATOR ', ') AS members, 
                                t.created_by AS created_by  -- Menampilkan siapa yang membuat grup
                            FROM teams t
                            JOIN team_members tm ON t.team_id = tm.team_id
                            JOIN users u ON tm.user_id = u.id  -- Menyesuaikan dengan id dari tabel users
                            WHERE tm.status = 'active'
                            GROUP BY t.team_id
                        ";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $row['team_name'] . "</td>
                                    <td>" . $row['members'] . "</td>
                                    <td>" . $row['created_by'] . "</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>No teams available</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</body>
</html>
