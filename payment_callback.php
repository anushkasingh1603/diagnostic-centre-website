<?php
require 'payment_config.php';
require 'config/db.php';

$payment_id = $_POST['razorpay_payment_id'] ?? '';
$order_id   = $_POST['razorpay_order_id'] ?? '';
$signature  = $_POST['razorpay_signature'] ?? '';

$expected = hash_hmac('sha256', $order_id . '|' . $payment_id, RAZORPAY_KEY_SECRET);

if (hash_equals($expected, $signature)) {
    $stmt = $conn->prepare("UPDATE payments SET status='paid', razorpay_payment_id=? WHERE razorpay_order_id=?");
    $stmt->bind_param('ss', $payment_id, $order_id);
    $stmt->execute();
    $stmt->close();
    $msg = "Payment successful! Thank you. Your payment ID is " . htmlspecialchars($payment_id);
    $ok = true;
} else {
    $stmt = $conn->prepare("UPDATE payments SET status='failed' WHERE razorpay_order_id=?");
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $stmt->close();
    $msg = "Payment verification failed. Please contact us.";
    $ok = false;
}
?>
<!DOCTYPE html>
<html><head><title>Payment Status</title>
<style>body{font-family:sans-serif;text-align:center;padding:60px;}
.box{max-width:500px;margin:auto;padding:30px;border-radius:12px;}
.ok{background:#e6ffed;color:#03543f;} .fail{background:#ffe6e6;color:#9b1c1c;}</style>
</head><body>
<div class="box <?php echo $ok ? 'ok' : 'fail'; ?>">
<h2><?php echo $ok ? '✅ Success' : '❌ Failed'; ?></h2>
<p><?php echo $msg; ?></p>
<a href="index.php">Back to Home</a>
</div></body></html>

