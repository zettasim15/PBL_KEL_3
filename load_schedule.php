<?php
include('koneksi.php');
session_start();

$user_id = $_SESSION['user_id'];

// Ambil jadwal milik user (atau timnya jika perlu)
$sql = "SELECT id, title, description, start FROM schedules WHERE assignee = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$schedules = [];

while ($row = $result->fetch_assoc()) {
    $schedules[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'description' => $row['description']
    ];
}

header('Content-Type: application/json');
echo json_encode($schedules);
?>
