<?php
include 'koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    // Jika id tidak ada, langsung redirect ke meetings.php
    header("Location: meetings.php");
    exit;
}

// Ambil data meeting berdasar id
$result = mysqli_query($koneksi, "SELECT * FROM meetings WHERE id = $id");
$row = mysqli_fetch_assoc($result);

if (!$row) {
    // Jika data tidak ditemukan, redirect juga
    header("Location: meetings.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $team = $_POST['team'];

    // Update data meeting
    $update = mysqli_query($koneksi, "UPDATE meetings SET name='$name', date='$date', status='$status', team='$team' WHERE id=$id");

    if ($update) {
        header("Location: meetings.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Meeting</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #d6f0f2;
            padding: 20px;
        }
        .form-container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Meeting</h2>
        <form method="post">
            <label for="name">Meeting Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($row['name']) ?>" required />

            <label for="date">Date</label>
            <input type="date" id="date" name="date" value="<?= htmlspecialchars($row['date']) ?>" required />

            <label for="status">Status</label>
            <select id="status" name="status" required>
                <option value="To Do" <?= $row['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="Done" <?= $row['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
            </select>

            <label for="team">Team</label>
            <input type="text" id="team" name="team" value="<?= htmlspecialchars($row['team']) ?>" required />

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
