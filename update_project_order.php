<?php
require_once 'config.php';

// Mendapatkan urutan proyek dari permintaan POST
$projectIds = $_POST['projectIds'];

// Perbarui sort_order untuk setiap proyek
$updateQuery = $conn->prepare('UPDATE projects SET sort_order = :sort_order WHERE id = :id');
$order = 1;
foreach ($projectIds as $projectId) {
    $updateQuery->bindValue(':sort_order', $order);
    $updateQuery->bindValue(':id', $projectId);
    $updateQuery->execute();
    $order++;
}

// Mengambil daftar proyek dari database dengan urutan yang baru
$query = $conn->query('SELECT * FROM projects ORDER BY sort_order ASC');
$projects = $query->fetchAll(PDO::FETCH_ASSOC);

// Menampilkan daftar proyek yang diperbarui

?>

<script>
    // Menyegarkan halaman menggunakan JavaScript
    window.location.reload();
</script>
