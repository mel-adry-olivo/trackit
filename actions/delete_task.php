<?php
session_start();
require '../database/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $task_id = $_POST['id'];

    // Prepare and execute the delete query
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $task_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php"); // Redirect to dashboard after deletion
        exit();
    } else {
        echo "Error deleting task: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
