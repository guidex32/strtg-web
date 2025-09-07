<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Данные для подключения к MySQL
$host = '127.0.0.1';
$user = 'user43508';
$password = 'bc4t4fOra9Dr';
$database = 'user43508';
$port = 3306;

// Создаем соединение с базой данных
$conn = new mysqli($host, $user, $password, $database, $port);

// Проверяем соединение
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Устанавливаем кодировку
$conn->set_charset('utf8');

// Получаем действие из запроса
$action = $_GET['action'] ?? '';

// Обрабатываем различные действия
switch ($action) {
    case 'register':
        registerUser($conn);
        break;
    case 'login':
        loginUser($conn);
        break;
    case 'save_country':
        saveCountry($conn);
        break;
    case 'save_target':
        saveTarget($conn);
        break;
    case 'update_country':
        updateCountry($conn);
        break;
    case 'update_target':
        updateTarget($conn);
        break;
    case 'save_all':
        saveAll($conn);
        break;
    case 'load_all':
        loadAll($conn);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
        break;
}

// Закрываем соединение
$conn->close();

// Функция для регистрации пользователя
function registerUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $conn->real_escape_string($data['username']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    
    // Проверяем, существует ли пользователь
    $result = $conn->query("SELECT id FROM users WHERE username = '$username'");
    
    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'User already exists']);
        return;
    }
    
    // Создаем пользователя
    if ($conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// Функция для входа пользователя
function loginUser($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $conn->real_escape_string($data['username']);
    $password = $data['password'];
    
    // Ищем пользователя
    $result = $conn->query("SELECT id, password FROM users WHERE username = '$username'");
    
    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'User not found']);
        return;
    }
    
    $user = $result->fetch_assoc();
    
    // Проверяем пароль
    if (password_verify($password, $user['password'])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid password']);
    }
}

// Функция для сохранения страны
function saveCountry($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $country = $data['country'];
    
    $id = $conn->real_escape_string($country['id']);
    $name = $conn->real_escape_string($country['name']);
    $x = $conn->real_escape_string($country['x']);
    $y = $conn->real_escape_string($country['y']);
    $flag = $conn->real_escape_string($country['flag']);
    $owner = $conn->real_escape_string($country['owner']);
    
    // Проверяем, существует ли страна
    $result = $conn->query("SELECT id FROM countries WHERE id = '$id'");
    
    if ($result->num_rows > 0) {
        // Обновляем существующую страну
        $query = "UPDATE countries SET name = '$name', x = '$x', y = '$y', flag = '$flag', owner = '$owner' WHERE id = '$id'";
    } else {
        // Создаем новую страну
        $query = "INSERT INTO countries (id, name, x, y, flag, owner) VALUES ('$id', '$name', '$x', '$y', '$flag', '$owner')";
    }
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// Функция для сохранения цели
function saveTarget($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $target = $data['target'];
    
    $id = $conn->real_escape_string($target['id']);
    $type = $conn->real_escape_string($target['type']);
    $x = $conn->real_escape_string($target['x']);
    $y = $conn->real_escape_string($target['y']);
    $health = $conn->real_escape_string($target['health']);
    $maxHealth = $conn->real_escape_string($target['maxHealth']);
    $owner = $conn->real_escape_string($target['owner']);
    $ownerName = $conn->real_escape_string($target['ownerName']);
    
    // Проверяем, существует ли цель
    $result = $conn->query("SELECT id FROM targets WHERE id = '$id'");
    
    if ($result->num_rows > 0) {
        // Обновляем существующую цель
        $query = "UPDATE targets SET type = '$type', x = '$x', y = '$y', health = '$health', max_health = '$maxHealth', owner = '$owner', owner_name = '$ownerName' WHERE id = '$id'";
    } else {
        // Создаем новую цель
        $query = "INSERT INTO targets (id, type, x, y, health, max_health, owner, owner_name) VALUES ('$id', '$type', '$x', '$y', '$health', '$maxHealth', '$owner', '$ownerName')";
    }
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// Функция для обновления страны
function updateCountry($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $country = $data['country'];
    
    $id = $conn->real_escape_string($country['id']);
    $health = $conn->real_escape_string($country['health']);
    
    $query = "UPDATE countries SET health = '$health' WHERE id = '$id'";
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// Функция для обновления цели
function updateTarget($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $target = $data['target'];
    
    $id = $conn->real_escape_string($target['id']);
    $health = $conn->real_escape_string($target['health']);
    
    $query = "UPDATE targets SET health = '$health' WHERE id = '$id'";
    
    if ($conn->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

// Функция для сохранения всех данных
function saveAll($conn) {
    $data = json_decode(file_get_contents('php://input'), true);
    $countries = $data['countries'];
    $targets = $data['targets'];
    
    // Очищаем таблицы
    $conn->query("DELETE FROM countries");
    $conn->query("DELETE FROM targets");
    
    // Сохраняем страны
    foreach ($countries as $country) {
        $id = $conn->real_escape_string($country['id']);
        $name = $conn->real_escape_string($country['name']);
        $x = $conn->real_escape_string($country['x']);
        $y = $conn->real_escape_string($country['y']);
        $flag = $conn->real_escape_string($country['flag']);
        $owner = $conn->real_escape_string($country['owner']);
        
        $query = "INSERT INTO countries (id, name, x, y, flag, owner) VALUES ('$id', '$name', '$x', '$y', '$flag', '$owner')";
        if (!$conn->query($query)) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
            return;
        }
    }
    
    // Сохраняем цели
    foreach ($targets as $target) {
        $id = $conn->real_escape_string($target['id']);
        $type = $conn->real_escape_string($target['type']);
        $x = $conn->real_escape_string($target['x']);
        $y = $conn->real_escape_string($target['y']);
        $health = $conn->real_escape_string($target['health']);
        $maxHealth = $conn->real_escape_string($target['maxHealth']);
        $owner = $conn->real_escape_string($target['owner']);
        $ownerName = $conn->real_escape_string($target['ownerName']);
        
        $query = "INSERT INTO targets (id, type, x, y, health, max_health, owner, owner_name) VALUES ('$id', '$type', '$x', '$y', '$health', '$maxHealth', '$owner', '$ownerName')";
        if (!$conn->query($query)) {
            echo json_encode(['success' => false, 'error' => $conn->error]);
            return;
        }
    }
    
    echo json_encode(['success' => true]);
}

// Функция для загрузки всех данных
function loadAll($conn) {
    $countries = [];
    $targets = [];
    
    // Загружаем страны
    $result = $conn->query("SELECT * FROM countries");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $countries[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'x' => $row['x'],
                'y' => $row['y'],
                'flag' => $row['flag'],
                'owner' => $row['owner']
            ];
        }
    }
    
    // Загружаем цели
    $result = $conn->query("SELECT * FROM targets");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $targets[] = [
                'id' => $row['id'],
                'type' => $row['type'],
                'x' => $row['x'],
                'y' => $row['y'],
                'health' => $row['health'],
                'maxHealth' => $row['max_health'],
                'owner' => $row['owner'],
                'ownerName' => $row['owner_name']
            ];
        }
    }
    
    echo json_encode(['success' => true, 'countries' => $countries, 'targets' => $targets]);
}
?>