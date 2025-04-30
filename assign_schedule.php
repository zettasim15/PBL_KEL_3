<?php
// Include your database connection
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schedule_id = $_POST['schedule_id'];
    $team_id = $_POST['team_id'];

    if (!empty($team_id)) {
        // Update the schedule with the assigned team
        $sql = "UPDATE schedule_meetings SET team_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $team_id, $schedule_id);
        $stmt->execute();

        // Redirect back to manage.php with a success message
        header('Location: manage.php?status=assigned');
    } else {
        // Redirect back to manage.php with an error message
        header('Location: manage.php?status=error');
    }
}
?>
