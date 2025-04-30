<?php
include('koneksi.php');
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Periksa apakah file gambar diunggah
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $image_name = $_FILES['profile_image']['name'];
    $image_tmp = $_FILES['profile_image']['tmp_name'];
    $image_size = $_FILES['profile_image']['size'];
    $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

    // Validasi ekstensi file
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array(strtolower($image_ext), $allowed_ext)) {
        die("Invalid file type.");
    }

    // Tentukan lokasi penyimpanan gambar
    $upload_dir = 'uploads/profile_pics/';
    $new_image_name = $user_id . '.' . $image_ext;
    $upload_path = $upload_dir . $new_image_name;

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($image_tmp, $upload_path)) {
        // Simpan nama gambar di database
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $new_image_name, $user_id);
        if ($stmt->execute()) {
            header("Location: dashboard.php"); // Arahkan ke dashboard setelah upload berhasil
            exit();
        } else {
            echo "Error updating profile image.";
        }
    } else {
        echo "Error uploading image.";
    }
}
?>
