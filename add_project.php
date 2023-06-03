<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $query = $conn->prepare('INSERT INTO projects (name, start_date) VALUES (:name, CURDATE())');
    $query->bindParam(':name', $name);
    $query->execute();

    header('Location: index.php');
    exit;
}
