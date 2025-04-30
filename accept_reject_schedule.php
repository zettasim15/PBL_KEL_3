<?php
include('koneksi.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $schedule_id = $_POST['schedule_id'];
    $action = $_POST['action'];

    if ($action == 'accept') {
        // Tandai jadwal sebagai diterima
        $sql = "UPDATE schedule_list SET status = 'accepted' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
    } elseif ($action == 'reject') {
        // Tandai jadwal sebagai ditolak
        $sql = "UPDATE schedule_list SET status = 'rejected' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $schedule_id);
        $stmt->execute();
    }

    header("Location: kalender.php");
}
?>
