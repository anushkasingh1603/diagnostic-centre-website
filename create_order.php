<?php
header('Content-Type: application/json');
require 'payment_config.php';
require 'config/db.php';

$patient_name   = trim($_POST['patient_name'] ?? '');
$amount         = floatval($_POST['amount'] ?? 0);
$appointment_id = !empty($_POST['appointment_id']) ? intval($_POST['appointment_id']) : null;
$method         = trim($_POST['method'] ?? 'UPI');

if ($patient_name === '' || $amount <= 0) {
    echo json_encode(['error' => 'Please enter a valid patient name and amount.']);
    exit;
}

$amount_paise = intval(round($amount * 100));

$order_data = json_encode([
    'amount'   => $amount_paise,
    'currency' => 'INR',
    'receipt'  => 'rcpt_' . time(),
]);

$ch = curl_init('https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    echo json_encode(['error' => 'Could not create order. Check API keys.']);
    exit;
}

$order = json_decode($response, true);
$order_id = $order['id'];

$stmt = $conn->prepare("INSERT INTO payments (patient_name, appointment_id, amount, method, razorpay_order_id, status) VALUES (?, ?, ?, ?, ?, 'created')");
$stmt->bind_param('sidss', $patient_name, $appointment_id, $amount, $method, $order_id);
$stmt->execute();
$stmt->close();

echo json_encode([
    'order_id' => $order_id,
    'amount'   => $amount_paise,
    'key_id'   => RAZORPAY_KEY_ID,
    'name'     => $patient_name,
]);
?>

