<?php
session_start();
require_once __DIR__ . '/config/db.php';

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Public endpoint: no session required.
// Supports secure forced download based on a report id.

if (!isset($_GET['id'])) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Invalid request.';
    exit();
}

$reportId = (int)$_GET['id'];
if ($reportId <= 0) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Invalid request.';
    exit();
}

// Fetch metadata only; never expose file path in URL.
// reports columns per your schema: id, user_id, patient_name, phone, service, file_path, file_name, created_at
$sql = "SELECT id, file_path, file_name
        FROM reports
        WHERE id = ?
        LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    http_response_code(500);
    echo 'Database error.';
    exit();
}

mysqli_stmt_bind_param($stmt, 'i', $reportId);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$report = $res ? mysqli_fetch_assoc($res) : null;
mysqli_stmt_close($stmt);

if (!$report) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Report not found.';
    exit();
}

$relativePath = (string)$report['file_path'];
$relativePath = str_replace('..', '', $relativePath);
$fullPath = __DIR__ . '/' . $relativePath;

if (!is_file($fullPath)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Report not found.';
    exit();
}

$downloadName = basename((string)$report['file_name']);

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . h($downloadName) . '"');
header('Content-Description: File Transfer');
header('Content-Length: ' . (string)filesize($fullPath));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, no-cache, must-revalidate');

readfile($fullPath);
exit();

