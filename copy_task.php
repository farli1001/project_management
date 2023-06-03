<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['taskId'])) {
    $taskId = $_POST['taskId'];

    // Mengambil data tugas dari database berdasarkan taskId
    $query = $conn->prepare('SELECT * FROM tasks WHERE id = :task_id');
    $query->bindParam(':task_id', $taskId);
    $query->execute();
    $task = $query->fetch(PDO::FETCH_ASSOC);

    // Menyalin tugas ke dalam database dengan projectId yang sama
    $query = $conn->prepare('INSERT INTO tasks (project_id, name, percentage, deadline, description) VALUES (:project_id, :name, :percentage, :deadline, :description)');
    $query->bindParam(':project_id', $task['project_id']);
    $query->bindParam(':name', $task['name']);
    $query->bindParam(':percentage', $task['percentage']);
    $query->bindParam(':deadline', $task['deadline']);
    $query->bindParam(':description', $task['description']);
    $query->execute();

    echo 'Task copied successfully';
} else {
    echo 'Invalid request';
}
?>
