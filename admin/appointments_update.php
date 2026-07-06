<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['appointment_id'], $_POST['new_status'])) {
    http_response_code(400);
    exit('Invalid request');
}

$appointmentId = (int)$_POST['appointment_id'];
$newStatus = trim($_POST['new_status']);
$allowedStatuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
if (!in_array($newStatus, $allowedStatuses, true)) {
    http_response_code(400);
    exit('Invalid status');
}

$stmt = mysqli_prepare($conn, 'UPDATE appointments SET status = ? WHERE id = ?');
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'si', $newStatus, $appointmentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: appointments.php');
exit();

