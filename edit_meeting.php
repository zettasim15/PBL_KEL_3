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

// Fetch all teams
$sql_teams = "SELECT * FROM teams";
$stmt_teams = $conn->prepare($sql_teams);
$stmt_teams->execute();
$result_teams = $stmt_teams->get_result();
$teams = $result_teams->fetch_all(MYSQLI_ASSOC);

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
    if ($stmt_update->execute()) {
        echo "Meeting successfully updated.";
    } else {
        echo "Error: " . $stmt_update->error;
    }
}

// Fetch the meeting details for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql_meeting = "SELECT * FROM meetings WHERE id = ?";
    $stmt_meeting = $conn->prepare($sql_meeting);
    $stmt_meeting->bind_param("i", $id);
    $stmt_meeting->execute();
    $result_meeting = $stmt_meeting->get_result();
    $meeting = $result_meeting->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Meeting</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="modal fade" id="editMeetingModal" tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMeetingModalLabel">Edit Meeting</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($meeting['id']); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Meeting Name</label>
                            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($meeting['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" name="due_date" value="<?= htmlspecialchars($meeting['due_date']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" required>
                                <option value="To Do" <?= $meeting['status'] == 'To Do' ? 'selected' : ''; ?>>To Do</option>
                                <option value="In Progress" <?= $meeting['status'] == 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Done" <?= $meeting['status'] == 'Done' ? 'selected' : ''; ?>>Done</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="assignee" class="form-label">Assign to Team</label>
                            <select class="form-control" name="assignee">
                                <option value="">Unassigned</option>
                                <?php foreach ($teams as $team): ?>
                                    <option value="<?= $team['team_id']; ?>" <?= $meeting['assignee'] == $team['team_id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($team['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_meeting" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
                                </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>