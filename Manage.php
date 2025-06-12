<?php
// Import the database connection
include('koneksi.php');

// Start session to get the user ID
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user information from the database
$sql = "SELECT username, role, profile_image, is_first_login FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // If the user is not found
    session_destroy();
    header("Location: login.php");
    exit();
}

// Check if it's the first login
if ($user['is_first_login'] == 1) {
    header('Location: customize_profile.php');
    exit();
}

// If the user doesn't have a profile image, use a default image
$profile_image = isset($user['profile_image']) && $user['profile_image'] ? $user['profile_image'] : 'default_profile.png';

// Fetch all teams
$sql_teams = "SELECT * FROM teams";
$stmt_teams = $conn->prepare($sql_teams);
$stmt_teams->execute();
$result_teams = $stmt_teams->get_result();
$teams = $result_teams->fetch_all(MYSQLI_ASSOC);

// Handle Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_meeting'])) {
    $name = $_POST['name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $assignee = $_POST['assignee']; // Ambil nilai assignee

    $sql_create = "INSERT INTO meetings (name, due_date, status, created_by, assignee) VALUES (?, ?, ?, ?, ?)";
    $stmt_create = $conn->prepare($sql_create);
    $stmt_create->bind_param("ssssi", $name, $due_date, $status, $user_id, $assignee);
    $stmt_create->execute();
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_meeting'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];
    $assignee = $_POST['assignee']; // Ambil nilai assignee

    $sql_update = "UPDATE meetings SET name = ?, due_date = ?, status = ?, assignee = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssii", $name, $due_date, $status, $assignee, $id);
    $stmt_update->execute();
}

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_meeting'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM meetings WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();
}

// Fetch all meetings created by the logged-in manager
$sql_read = "SELECT * FROM meetings WHERE created_by = ?";
$stmt_read = $conn->prepare($sql_read);
$stmt_read->bind_param("i", $user_id);
$stmt_read->execute();
$result = $stmt_read->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Meetings</title>
    <link rel="stylesheet" href="man.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 
    <style>
        .table {
            margin-top: 20px;
        }
        .welcome-text {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            text-align: center;
        }
        .home-section {
        margin-left: 250px; /* Sesuaikan dengan lebar sidebar */
        padding: 20px;
        min-height: 100vh;
        background-color: #f8f9fa; /* Warna latar belakang */
        transition: all 0.3s ease; /* Animasi untuk menu responsif */
        }
        .table-container {
            overflow-x: auto; /* Memastikan tabel dapat digulir pada layar kecil */
            background-color: #fff; /* Warna latar belakang tabel */
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%; /* Tabel akan mengambil seluruh ruang yang tersedia */
            border-collapse: collapse;
        }
        .table th, .table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .table th {
            background-color: #343a40;
            color: white;
        }
        .status-todo {
            background-color: #f39c12; /* Orange for 'To Do' */
            color: white;
        }
        .status-in-progress {
            background-color: #3498db; /* Blue for 'In Progress' */
            color: white;
        }
        .status-done {
            background-color: #2ecc71; /* Green for 'Done' */
            color: white;
        }
        .container {
        max-width: 100%;
        }
    </style>
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
<div class="home-section">
    <div class="container mt-4">
        <h3>Manage Meetings</h3>
        
        <!-- Button to Open Add Meeting Modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMeetingModal">
            Add Meeting
        </button>

        <!-- Meetings Table -->
        <table class="table table -bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Meeting Name</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Assigned Team</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['due_date']); ?></td>
                            <td class="status-<?= strtolower(str_replace(" ", "-", $row['status'])); ?>"><?= htmlspecialchars($row['status']); ?></td>
                            <td>
                                <?php
                                // Fetch the team name based on the assignee ID
                                $assignee_id = $row['assignee'];
                                $sql_team = "SELECT name FROM teams WHERE team_id = ?";
                                $stmt_team = $conn->prepare($sql_team);
                                $stmt_team->bind_param("i", $assignee_id);
                                $stmt_team->execute();
                                $result_team = $stmt_team->get_result();
                                $team_name = $result_team->fetch_assoc();
                                echo htmlspecialchars($team_name['name'] ?? 'Unassigned');
                                ?>
                            </td>
                            <td>
                                <!-- Edit Button (opens modal) -->
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editMeetingModal<?= $row['id']; ?>">Edit</button>
                                
                                <!-- Delete Button -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="delete_meeting" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Meeting Modal -->
                        <div class="modal fade" id="editMeetingModal<?= $row['id']; ?>" tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editMeetingModalLabel">Edit Meeting</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Meeting Name</label>
                                                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($row['name']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="due_date" class="form-label">Due Date</label>
                                                <input type="date" class="form-control" name="due_date" value="<?= htmlspecialchars($row['due_date']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select class="form-control" name="status" required>
                                                    <option value="To Do" <?= $row['status'] == 'To Do' ? 'selected' : ''; ?>>To Do</option>
                                                    <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                                    <option value="Done" <?= $row['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="assignee" class="form-label">Assign to Team</label>
                                                <select class="form-control" name="assignee">
                                                    <option value="">Unassigned</option>
                                                    <?php foreach ($teams as $team): ?>
                                                        <option value="<?= $team['team_id']; ?>" <?= $row['assignee'] == $team['team_id'] ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($team['name']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss ="modal">Close</button>
                                            <button type="submit" name="update_meeting" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No meetings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Add Meeting Modal -->
        <div class="modal fade" id="addMeetingModal" tabindex="-1" aria-labelledby="addMeetingModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addMeetingModalLabel">Add New Meeting</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Meeting Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" name="due_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="To Do">To Do</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignee" class="form-label">Assign to Team</label>
                                <select class="form-control" name="assignee">
                                    <option value="">Unassigned</option>
                                    <?php foreach ($teams as $team): ?>
                                        <option value="<?= $team['team_id']; ?>"><?= htmlspecialchars($team['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="create_meeting" class="btn btn-primary">Add Meeting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="invitemember.js"></script>
</body>
</html>