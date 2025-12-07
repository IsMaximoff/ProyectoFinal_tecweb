<?php
// backend/auth-register.php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';

if ($username === '' || $password === '') {
    echo json_encode(['error' => 'Usuario y contraseña requeridos']);
    exit;
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Error de conexión: ' . $mysqli->connect_error]);
    exit;
}

// Verificar si ya existe el usuario
$stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['error' => 'El usuario ya existe']);
    $stmt->close();
    $mysqli->close();
    exit;
}
$stmt->close();

// Insertar usuario con hash
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $mysqli->prepare("INSERT INTO users (username, password, fullname) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(['error' => 'Prepare failed: ' . $mysqli->error]);
    $mysqli->close();
    exit;
}
$stmt->bind_param('sss', $username, $hash, $fullname);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
} else {
    echo json_encode(['error' => $stmt->error]);
}
$stmt->close();
$mysqli->close();
