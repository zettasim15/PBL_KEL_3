<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <link rel="stylesheet" href="dash.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'/>
  <style>
    /* Minimal styling to reflect the layout in the image */
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #d7f2f4;
      display: flex;
    }

    .sidebar {
      width: 240px;
      background-color: #96D3DA ;
      height: 100vh;
      padding-top: 20px;
      color: white;
      position: fixed;
    }

    .logo_details {
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .logo_name {
      font-size: 18px;
      font-weight: bold;
      margin-left: 10px;
    }

    .nav-list {
      list-style: none;
      padding: 0;
      margin-top: 20px;
    }

    .nav-list li {
      padding: 10px 20px;
      display: flex;
      align-items: center;
    }

    .nav-list li a {
      text-decoration: none;
      color: white;
      display: flex;
      align-items: center;
      width: 100%;
    }

    .nav-list i {
      font-size: 20px;
      margin-right: 10px;
    }

    .profile {
      position: absolute;
      bottom: 20px;
      width: 100%;
    }

    .profile_details {
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .profile_details img {
      width: 40px;
      border-radius: 50%;
      margin-right: 10px;
    }

    .main-content {
      margin-left: 240px;
      padding: 30px;
      width: calc(100% - 240px);
    }

    .cards {
      display: flex;
      gap: 20px;
    }

    .card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      width: 200px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
      margin: 0;
      font-size: 18px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      margin: 10px 0 5px;
    }

    .card small {
      color: gray;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <div class="logo_details">
      <img src="meetingsreminder.png" alt="logo_icon" width="40">
      <div class="logo_name">TimesMeet</div>
    </div>
    <ul class="nav-list">
      <li><i class='bx bx-search'></i><input type="text" placeholder="Search..."></li>
      <li><a href="dashboard.html"><i class='bx bx-home'></i><span>Home</span></a></li>
      <li><a href="#"><i class='bx bx-calendar-check'></i><span>Manage</span></a></li>
      <li><a href="#"><i class='bx bx-calendar'></i><span>Calendar</span></a></li>
      <li><a href="InviteMember.html"><i class='bx bxs-user-plus'></i><span>Invite Members</span></a></li>
      <li><a href="#"><i class='bx bx-user-check'></i><span>Presence</span></a></li>
      <li><a href="#"><i class='bx bx-task'></i><span>Tasks</span></a></li>
      <li class="profile">
        <div class="profile_details">
          <img src="fox.jpg" alt="profile image">
          <div>
            <div>Sifulun</div>
            <div class="designation">Member</div>
          </div>
        </div>
        <i class='bx bx-log-out' id="log_out"></i>
      </li>
    </ul>
  </div>

  <div class="main-content">
    <div class="cards">
      <!-- Meetings Card -->
      <div class="card">
        <h3>Meetings</h3>
        <p>
          <?php
          // Example: echo result from database
          echo "0"; 
          ?>
        </p>
        <small>Number of Meetings</small>
      </div>

      <!-- Schedule Card -->
      <div class="card">
        <h3>Schedule</h3>
        <p>
          <?php
          echo "0";
          ?>
        </p>
        <small>Number of Schedule</small>
      </div>

      <!-- Teams Card -->
      <div class="card">
        <h3>Teams</h3>
        <p>
          <?php
          echo "0";
          ?>
        </p>
        <small>Number of Teams</small>
      </div>
    </div>
  </div>

</body>
</html>
