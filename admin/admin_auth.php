<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Role-based enforcement (requires users.role with values: user/admin)
$role = $_SESSION['role'] ?? 'user';
if ($role !== 'admin') {
    header('Location: ../index.html');
    exit();
}

