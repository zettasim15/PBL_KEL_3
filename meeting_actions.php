<?php
// Include the database connection
include('koneksi.php');

// Start the session to ensure the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get the user ID from session

// Add Meeting
if (isset($_POST['create_meeting'])) {
    $name = $_POST['name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $sql_create = "INSERT INTO meetings (name, due_date, status, created_by) VALUES (?, ?, ?, ?)";
    $stmt_create = $conn->prepare($sql_create);
    $stmt_create->bind_param("sssi", $name, $due_date, $status, $user_id);
    $stmt_create->execute();

    // Redirect after insertion
    header("Location: Manage.php");
    exit();
}

// Update Meeting
if (isset($_POST['update_meeting'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $sql_update = "UPDATE meetings SET name = ?, due_date = ?, status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $name, $due_date, $status, $id);
    $stmt_update->execute();

    // Redirect after updating
    header("Location: Manage.php");
    exit();
}

// Delete Meeting
if (isset($_POST['delete_meeting'])) {
    $id = $_POST['id'];

    $sql_delete = "DELETE FROM meetings WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();

    // Redirect after deletion
    header("Location: Manage.php");
    exit();
}
// Check if form is submitted for updating the meeting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_meeting'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    // Prepare update query
    $sql_update = "UPDATE meetings SET name = ?, due_date = ?, status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssi", $name, $due_date, $status, $id);

    // Execute the update query
    if ($stmt_update->execute()) {
        // Redirect back to the Manage Meetings page or display success
        header("Location: Manage.php?status=success");
        exit();
    } else {
        // Handle error if the update fails
        echo "Error: " . $stmt_update->error;
    }
}

?>
