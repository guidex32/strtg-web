<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Здесь должен быть код для подключения к MySQL и загрузки данных
// $host = $data['host'];
// $user = $data['user'];
// $password = $data['password'];
// $database = $data['database'];

// После успешной загрузки:
echo json_encode([
    'success' => true,
    'countries' => [], // загруженные страны
    'targets' => [],   // загруженные цели
    'projectiles' => [] // загруженные снаряды
]);
?>