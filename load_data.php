<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Здесь должен быть код для подключения к MySQL и сохранения данных
// $host = $data['host'];
// $user = $data['user'];
// $password = $data['password'];
// $database = $data['database'];

// После успешного сохранения:
echo json_encode(['success' => true]);
?>