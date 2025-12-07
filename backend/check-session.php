<?php
// backend/check-session.php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'logged' => true,
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username'] ?? null,
        'fullname' => $_SESSION['fullname'] ?? null,
        'role' => $_SESSION['role'] ?? 'user'
    ]);
} else {
    echo json_encode(['logged' => false]);
}
