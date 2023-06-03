<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskId'])) {
    $taskId = $_POST['taskId'];

    // Menghapus tugas dari database berdasarkan taskId
    $query = $conn->prepare('DELETE FROM tasks WHERE id = :task_id');
    $query->bindParam(':task_id', $taskId);
    $query->execute();

    echo 'Task deleted successfully';
} else {
    echo 'Invalid request';
}
?>
    