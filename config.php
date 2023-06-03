<?php

$host = 'localhost';
$db = 'project_management';
$user = 'root';
$password = '';

$conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
