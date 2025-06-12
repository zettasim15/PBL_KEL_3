<?php
include 'koneksi.php';

if (isset($_POST['id']) && isset($_POST['action'])) {
    $ids = $_POST['id'];
    $actions = $_POST['action'];

    for ($i = 0; $i < count($ids); $i++) {
        $id = mysqli_real_escape_string($conn, $ids[$i]);
        $action = mysqli_real_escape_string($conn, $actions[$i]);

        $update = "UPDATE tasks SET action='$action' WHERE id='$id'";
        mysqli_query($conn, $update);
    }

    header("Location: task2.php?status=success");
} else {
    echo "Data tidak lengkap.";
}
