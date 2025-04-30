<?php
// Include your database connection
include('koneksi.php');

if (isset($_GET['id'])) {
    $schedule_id = $_GET['id'];

    // Delete the schedule from the 'schedule_meetings' table
    $sql = "DELETE FROM schedule_meetings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $schedule_id);
    $stmt->execute();

    // Redirect back to manage.php with a success message
    header('Location: manage.php?status=deleted');
}
?>
