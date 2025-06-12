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

// Ambil data jadwal dari tabel schedule_list
$schedules = $conn->query("SELECT * FROM `schedule_list` WHERE 1");
$sched_res = [];
foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
    $row['assignee'] = $row['assignee'] ? getUsernameById($row['assignee']) : 'None'; // Fungsi untuk mendapatkan username assignee
    $sched_res[$row['id']] = $row;
}

// Fungsi untuk mengambil username berdasarkan ID
function getUsernameById($userId) {
    global $conn;
    $result = $conn->query("SELECT username FROM users WHERE id = $userId");
    $user = $result->fetch_assoc();
    return $user['username'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduling</title>
    <link rel="stylesheet" href="kalender.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./fullcalendar/lib/main.min.js"></script>
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
            <li>
            <a href="presence.php">
                <i class='bx bx-user-check'></i>
                <span class="link_name">Attendance</span>
            </a>
            <span class="tooltip">Attendance</span>
        </li>
            <li>
                <a href="task_manager.php">
                    <i class="bx bx-task-x"></i>
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
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="cardt rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Schedule Form</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">Title</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">Description</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Start</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">End</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                </div>
                                <!-- Dropdown untuk memilih assignee -->
                                <div class="form-group mb-2">
                                    <label for="assignee" class="control-label">Assignee (Member)</label>
                                    <select class="form-control form-control-sm rounded-0" name="assignee" id="assignee">
                                        <option value="">Select a member</option>
                                        <?php
                                        // Ambil daftar member dengan role 'member'
                                        $members = $conn->query("SELECT id, username FROM users WHERE role = 'member'");
                                        while ($member = $members->fetch_assoc()) {
                                            echo "<option value='{$member['id']}'>{$member['username']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Save</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php 
$schedules = $conn->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
?>
<?php 
if(isset($conn)) $conn->close();
?>

<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script>
    window.onload = function() {
        const sidebar = document.querySelector(".sidebar");
        const pageContainer = document.querySelector("#page-container"); // Konten utama
        
        // Pastikan sidebar selalu terbuka
        sidebar.classList.add("open");
        pageContainer.classList.add("shifted"); // Menambahkan class untuk menggeser konten utama
    };
</script>

<script src="./js/script.js"></script>
    <script src="group.js"></script>
</body>
</html>
