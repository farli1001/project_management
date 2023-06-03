<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$projectId = $_GET['id'];

// Update status arsip pada projek menjadi false
$query = $conn->prepare('UPDATE projects SET archived = 0 WHERE id = :id');
$query->bindParam(':id', $projectId);
$query->execute();

// Redirect kembali ke halaman projek
header('Location: project.php?id=' . $projectId);
exit;
?>
