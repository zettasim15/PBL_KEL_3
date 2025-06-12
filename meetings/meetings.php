<?php
include 'koneksi.php';

// Ambil data meetings dari database
$result = mysqli_query($koneksi, "SELECT * FROM meetings ORDER BY date ASC");

$meetings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $meetings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Meetings</title>
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: Arial;
      background-color: #d6f0f2;
    }
    .sidebar {
      position: fixed;
      left: 0;
      top: 0;
      width: 250px;
      height: 100%;
      background: #62cfcf;
      color: white;
    }
    .logo_details {
      display: flex;
      align-items: center;
      padding: 20px;
    }
    .logo_details img {
      width: 40px;
      margin-right: 10px;
    }
    .nav-list {
      list-style: none;
      padding-left: 0;
      margin: 0;
    }
    .nav-list li {
      padding: 10px 20px;
    }
    .nav-list li a {
      color: white;
      text-decoration: none;
      display: flex;
      align-items: center;
    }
    .nav-list li a i {
      margin-right: 10px;
    }
    .nav-list li.active {
      background-color: white;
    }
    .nav-list li.active a {
      color: black;
    }
    .profile {
      position: absolute;
      bottom: 20px;
      width: 100%;
      padding: 10px 20px;
    }
    .profile img {
      width: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }
    .profile .info {
      display: flex;
      align-items: center;
    }
    .container {
      margin-left: 270px;
      padding: 20px;
    }
    h2 {
      margin-bottom: 10px;
    }
    .add-btn {
      background-color: #007bff;
      color: white;
      padding: 8px 16px;
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    th, td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }
    th {
      background-color: #000;
      color: white;
    }
    .btn {
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
    }
    .edit {
      background-color: #c5ff99;
      color: black;
    }
    .delete {
      background-color: #ff4d4d;
      color: white;
    }
  </style>
</head>
<body>

<!-- SIDEBAR -->
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
        </li>
        <li>
            <a href="dashboard.php">
                <i class="bx bx-home"></i>
                <span class="link_name">Home</span>
            </a>
        </li>
        <li>
            <a href="meeting.php">
                <i class="bx bx-calendar-check"></i>
                <span class="link_name">Manage</span>
            </a>
        </li>
        <li>
            <a href="InviteMember.php">
                <i class="bx bxs-user-plus"></i>
                <span class="link_name">Invite Member</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="bx bx-calendar"></i>
                <span class="link_name">Calendar</span>
            </a>
        </li>
        <li>
            <a href="presence.php">
                <i class="bx bx-calendar-check"></i>
                <span class="link_name">Presence</span>
            </a>
        </li>
        <li>
            <a href="task.php">
                <i class="bx bx-task"></i>
                <span class="link_name">Task</span>
            </a>
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

<!-- MAIN CONTENT -->
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
    <?php foreach ($meetings as $index => $row): ?>
    <tr>
      <td><?= $index + 1 ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['date'] ?></td>
      <td><?= $row['status'] ?></td>
      <td><?= $row['team'] ?></td>
      <td>
        <a class="btn edit" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
        <a class="btn delete" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus?')">Delete</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

</body>
</html>

