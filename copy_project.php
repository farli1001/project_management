<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$projectId = $_GET['id'];

// Mengambil data proyek dari database
$query = $conn->prepare('SELECT * FROM projects WHERE id = :id');
$query->bindParam(':id', $projectId);
$query->execute();
$project = $query->fetch(PDO::FETCH_ASSOC);

// Mendapatkan tanggal saat ini
$currentDate = date('Y-m-d');

// Menyalin project dan task-tasknya ke database
$query = $conn->prepare('INSERT INTO projects (name, start_date, end_date, image, description) VALUES (:name, :start_date, :end_date, :image, :description)');
$query->bindParam(':name', $project['name']);
$query->bindParam(':start_date', $currentDate);
$query->bindParam(':end_date', $project['end_date']);
$query->bindParam(':image', $project['image']);
$query->bindParam(':description', $project['description']);
$query->execute();

$newProjectId = $conn->lastInsertId();

$query = $conn->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
$query->bindParam(':project_id', $projectId);
$query->execute();
$tasks = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($tasks as $task) {
    $query = $conn->prepare('INSERT INTO tasks (project_id, name, percentage, deadline, description) VALUES (:project_id, :name, :percentage, :deadline, :description)');
    $query->bindParam(':project_id', $newProjectId);
    $query->bindParam(':name', $task['name']);
    $query->bindParam(':percentage', $task['percentage']);
    $query->bindParam(':deadline', $task['deadline']);
    $query->bindParam(':description', $task['description']);
    $query->execute();
}

// Kembali ke halaman index.php setelah menyalin project
header('Location: index.php');
exit;
?>
