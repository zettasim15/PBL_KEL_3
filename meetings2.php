<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>TimetoMeet</title>


<link rel="stylesheet" href="presence.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            <li>
                <a href="task.php">
                    <i class="bx bx-home"></i>
                    <span class="link_name">task</span>
                </a>
                <span class="tooltip">task</span>
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
  </style>
</head>
<body>

  

  <div class="main-content">
    <!-- Di sini isi halaman utama -->

<?php
// Simulasi data, biasanya dari database
$meetings = [
    ['name' => 'PBL', 'date' => '2024-12-05', 'status' => 'To Do', 'team' => 'Kel 6'],
    ['name' => 'Presentasi', 'date' => '2024-12-14', 'status' => 'In Progress', 'team' => 'unassigned'],
    ['name' => 'Darurat', 'date' => '2024-12-04', 'status' => 'Done', 'team' => 'unassigned'],
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Meetings</title>
    <style>
        body { font-family: Arial; background-color: #d6f0f2; }
        .container { margin: 30px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #000; color: white; }
        .btn { padding: 5px 10px; cursor: pointer; border: none; border-radius: 5px; }
        .edit { background-color: #c5ff99; }
        .delete { background-color: #ff4d4d; color: white; }
        .add-btn { background-color: #007bff; color: white; padding: 8px 16px; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>
<body>
   
            
          

    <script>
        function addMeeting() {
            alert("Tambah meeting baru (fungsi belum dibuat)");
        }

        function editMeeting(index) {
            alert("Edit meeting ke-" + (index + 1));
        }

        function deleteMeeting(index) {
            if (confirm("Yakin ingin menghapus meeting ke-" + (index + 1) + "?")) {
                alert("Meeting dihapus (fungsi belum dihubungkan ke server)");
            }
        }
        
    </script>
    <?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Meetings</title>
    <style>
        body { font-family: Arial; background-color: #d6f0f2; }
        .container { margin: 30px; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
        th { background-color: #000; color: white; }
        .btn { padding: 5px 10px; cursor: pointer; border: none; border-radius: 5px; }
        .edit { background-color: #c5ff99; }
        .delete { background-color: #ff4d4d; color: white; }
        .add-btn { background-color:rgb(0, 251, 255); color: white; padding: 8px 16px; border-radius: 5px; margin-bottom: 10px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Meetings</h2>
        <a class="add-btn" href="add.php">Add Meeting</a>
        <table>
            <tr>
                <th>No</th>
                <th>Meeting Name</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Assigned Team</th>
                <th>Action</th>
            </tr>
            <?php
            $result = $conn->query("SELECT * FROM meetings");
            $i = 1;
            while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['due_date'] ?></td>
                <td><?= $row['status'] ?></td>
                <td><?= $row['team'] ?></td>
                <td>
                    <a class="btn edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                    <a class="btn delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus meeting ini?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

</body>
</html>
