<?php
// backend/auth-login.php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username === '' || $password === '') {
    echo json_encode(['error' => 'Credenciales requeridas']);
    exit;
}

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Error de conexión: ' . $mysqli->connect_error]);
    exit;
}

$stmt = $mysqli->prepare("SELECT id, password, fullname, role FROM users WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo json_encode(['error' => 'Usuario no encontrado']);
    $stmt->close();
    $mysqli->close();
    exit;
}
$row = $res->fetch_assoc();
if (password_verify($password, $row['password'])) {
    // Login exitoso -> crear sesión
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $username;
    $_SESSION['fullname'] = $row['fullname'];
    $_SESSION['role'] = $row['role'];

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Contraseña incorrecta']);
}
$stmt->close();
$mysqli->close();
