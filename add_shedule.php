<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $start = $_POST["start"];

    $stmt = $conn->prepare("INSERT INTO schedules (title, description, start) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $start);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
}
?>
