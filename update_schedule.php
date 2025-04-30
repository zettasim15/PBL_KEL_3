<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name_meeting = $_POST['name_meeting'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];
    $assignee_id = intval($_POST['assignee_id']);

    $sql = "UPDATE schedules 
            SET name_meeting = '$name_meeting', status = '$status', due_date = '$due_date', assignee_id = $assignee_id 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        echo "Schedule updated successfully";
        header("Location: manage_schedule.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
