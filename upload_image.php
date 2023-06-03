<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $projectId = $_POST['project_id'];
    $image = $_FILES['image']['name'];
    $file = $_FILES['image']['tmp_name'];

    // Simpan file ke folder 'assets'
    move_uploaded_file($file, "assets/".$image);

    // Perbarui nilai gambar dalam database
    $query = $conn->prepare('UPDATE projects SET image = :image WHERE id = :id');
    $query->bindParam(':image', $image);
    $query->bindParam(':id', $projectId);
    $query->execute();

    header('Location: project.php?id=' . $projectId);
    exit;
}
