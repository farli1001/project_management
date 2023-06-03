<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['end_date'])) {
    $projectId = $_GET['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $endDate = $_POST['end_date'];

    // Periksa jika tanggal akhir lebih kecil dari tanggal awal
    $queryStartDate = $conn->prepare('SELECT start_date FROM projects WHERE id = :id');
    $queryStartDate->bindParam(':id', $projectId);
    $queryStartDate->execute();
    $startDate = $queryStartDate->fetchColumn();

    // Periksa jika tanggal akhir kosong dan atur nilainya menjadi NULL
if (empty($endDate)) {
    $endDate = NULL;
}

    if ($endDate < $startDate) {
        echo '<script>alert("Tanggal akhir harus lebih besar dari tanggal awal. Silakan isi ulang.");</script>';
        echo '<script>document.getElementById("end_date").focus();</script>';
        exit;
    }


    // Perbarui nilai proyek dalam database
    $queryUpdate = $conn->prepare('UPDATE projects SET name = :name, description = :description, end_date = :end_date WHERE id = :id');
    $queryUpdate->bindParam(':name', $name);
    $queryUpdate->bindParam(':description', $description);
    $queryUpdate->bindParam(':end_date', $endDate);
    $queryUpdate->bindParam(':id', $projectId);
    $queryUpdate->execute();

    header('Location: project.php?id=' . $projectId);
    exit;
}
?>
