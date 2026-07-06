<?php
require_once __DIR__ . "/config/db.php";

// Basic server-side validation
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    http_response_code(400);
    echo "Missing required fields.";
    exit;
}

// Prepare insert (avoid SQL injection)
$subject = "Enquiry: " . ($service !== '' ? $service : 'General');

$stmt = mysqli_prepare($conn, "INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo "Database error.";
    exit;
}

mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $subject, $message);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo "Could not save your request.";
    exit;
}

mysqli_stmt_close($stmt);

// Redirect back with status
header("Location: contact.html?success=1");
exit;

