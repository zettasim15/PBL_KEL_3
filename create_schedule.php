<?php
// Include your database connection
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $meeting_name = $_POST['meeting_name'];
    $due_date = $_POST['due_date'];

    // Insert new schedule into the 'schedule_meetings' table
    $sql = "INSERT INTO schedule_meetings (meeting_name, due_date) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $meeting_name, $due_date);
    $stmt->execute();

    // Redirect back to manage.php with a success message
    header('Location: manage.php?status=created');
}
?>
