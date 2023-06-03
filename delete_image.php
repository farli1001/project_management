<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['image'])) {
    $projectId = $_GET['id'];
    $image = $_GET['image'];

    // Perbarui nilai gambar dalam database menjadi NULL
    $query = $conn->prepare('UPDATE projects SET image = NULL WHERE id = :id');
    $query->bindParam(':id', $projectId);
    $query->execute();

    header('Location: project.php?id=' . $projectId);
    exit;
} else {
    header('Location: index.php');
    exit;
}
