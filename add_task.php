<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_GET['project_id'];
    $name = $_POST['name'];
    $percentage = $_POST['percentage'];
    $deadline = $_POST['deadline'];
    $description = $_POST['description'];

    $query = $conn->prepare('INSERT INTO tasks (project_id, name, percentage, deadline, description) VALUES (:project_id, :name, :percentage, :deadline, :description)');
    $query->bindParam(':project_id', $projectId);
    $query->bindParam(':name', $name);
    $query->bindParam(':percentage', $percentage);
    $query->bindParam(':deadline', $deadline);
    $query->bindParam(':description', $description);
    $query->execute();
 
    header('Location: project.php?id=' . $projectId);
    exit;
}
