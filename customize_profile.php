<?php
// Mengimpor file koneksi.php
include('koneksi.php');

// Mulai sesi untuk mendapatkan ID pengguna
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $conn->prepare("SELECT id, role, is_first_login FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_assoc();

// Setelah verifikasi login berhasil
$_SESSION['id'] = $users['id'];
$_SESSION['role'] = $users['role'];
$_SESSION['is_first_login'] = $users['is_first_login'];

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $display_username = $_POST['username'];
    $target_dir = "uploads/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
// Update is_first_login setelah profil disesuaikan
$sql_update = "UPDATE users SET is_first_login = 0 WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("i", $user_id);
$stmt_update->execute();

    if (isset($_FILES['profile_image'])) {
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $stmt = $conn->prepare("UPDATE users SET profile_image = ?, display_username = ?, is_first_login = 0 WHERE id = ?");
                $stmt->bind_param("ssi", $target_file, $display_username, $user_id);
                $stmt->execute();

                $_SESSION['display_username'] = $display_username;

                if ($_SESSION['role'] === 'Member') {
                    header('Location: member_dashboard.php');
                } else {
                    header('Location: manager_dashboard.php');
                }
                exit();
            } else {
                echo "Terjadi kesalahan saat mengunggah gambar.";
            }
        } else {
            echo "Hanya file gambar yang diperbolehkan!";
        }
    }
}

// Ambil informasi pengguna dari database
$sql = "SELECT username, role, display_username, profile_image FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Jika pengguna tidak memiliki gambar profil, gunakan gambar default
$profile_image = $user['profile_image'] ? $user['profile_image'] : 'default_profile.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customize Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #96d3da;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: #FFFFFF;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
        }
        .container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #000000;
        }
        .container img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover;
        }
        .container p {
            font-size: 14px;
            color: #666666;
            margin-bottom: 20px;
        }
        .container input[type="text"],
        .container input[type="file"] {
            width: 80%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #CCCCCC;
            border-radius: 20px;
            font-size: 14px;
        }
        .container button {
            background-color: #000000;
            color: #FFFFFF;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
        }
        .container button:hover {
            background-color: #333333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Customize Your Profile!</h1>
    <img id="profilePreview" src="uploads/<?= htmlspecialchars($profile_image); ?>" alt="Profile Picture" />
    <p>Upload Your Profile Picture</p>
    <form action="customize_profile.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="username" value="<?= htmlspecialchars($user['display_username']); ?>" required />
        <input type="file" name="profile_image" accept="image/*" onchange="previewImage(event)" required />
        <button type="submit">CONFIRM</button>
    </form>
</div>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('profilePreview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
</body>
</html>
