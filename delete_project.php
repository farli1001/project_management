<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$projectId = $_GET['id'];

// Hapus proyek dari database
$query = $conn->prepare('DELETE FROM projects WHERE id = :id');
$query->bindParam(':id', $projectId);
$query->execute();

// Hapus tugas terkait dari database
$query = $conn->prepare('DELETE FROM tasks WHERE project_id = :project_id');
$query->bindParam(':project_id', $projectId);
$query->execute();

// Kembali ke halaman indeks atau tampilan lain yang sesuai
header('Location: index.php');
exit;
?>
