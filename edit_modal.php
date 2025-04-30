<?php
// This file will handle displaying the modal content dynamically
if (isset($_GET['id'])) {
    include('koneksi.php');  // Database connection
    $id = $_GET['id'];

    // Fetch meeting details from the database
    $sql = "SELECT * FROM meetings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $meeting = $result->fetch_assoc();
}
?>

<!-- Edit Meeting Modal (Popup) -->
<div class="modal fade" id="editMeetingModal" tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="meeting_actions.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMeetingModalLabel">Edit Meeting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $meeting['id']; ?>">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="update_meeting" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
