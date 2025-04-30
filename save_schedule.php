<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $assignee = $_POST['assignee'];  // Ambil assignee dari form

    $sql = "INSERT INTO schedule_list (title, description, start_datetime, end_datetime, assignee) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $description, $start_datetime, $end_datetime, $assignee);
    $stmt->execute();

    header("Location: kalender.php");
}
?>
