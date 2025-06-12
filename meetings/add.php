<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $status = $_POST['status'];
    $team = $_POST['team'];

    // Insert data ke tabel meetings
    $query = "INSERT INTO meetings (name, date, status, team) VALUES ('$name', '$date', '$status', '$team')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: meetings.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
<!-- HTML form kamu tetap di sini -->


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>add meetings</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #d6f0f2;
      margin: 0;
      padding: 0;
    }
    .form-container {
      max-width: 500px;
      margin: 50px auto;
      background-color: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
    }
    input[type="text"], input[type="date"], select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
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
    }
    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Tambah Meeting</h2>
    <form method="post">
      <input type="text" name="name" placeholder="Meeting Name" required>
      <input type="date" name="date" required>
      <select name="status" required>
          <option value="To Do">To Do</option>
          <option value="In Progress">In Progress</option>
          <option value="Done">Done</option>
      </select>
      <input type="text" name="team" placeholder="Team" required>
      <button type="submit">Simpan</button>
    </form>
  </div>
</body>
</html>
