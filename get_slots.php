<?php
header('Content-Type: application/json');
include 'config/db.php';

$date = $_GET['date'] ?? '';

// Validate date format YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    echo json_encode(['error' => 'Invalid date']);
    exit;
}

// Build all slots from 7:30 to 19:30 in 30-min steps
$all_slots = [];
$start = strtotime('07:30');
$end   = strtotime('19:30');
for ($t = $start; $t <= $end; $t += 1800) {
    $all_slots[] = date('H:i:s', $t);
}

// Find already-booked slots for this date (ignore cancelled)
$booked = [];
$stmt = mysqli_prepare(
    $conn,
    "SELECT appointment_time FROM appointments WHERE appointment_date = ? AND status != 'Cancelled'"
);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $date);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($res)) {
        $booked[] = substr($row['appointment_time'], 0, 5) . ':00';
    }
    mysqli_stmt_close($stmt);
}

// Build response: each slot with label and availability
$slots = [];
foreach ($all_slots as $slot) {
    $slots[] = [
        'value'  => $slot,
        'label'  => date('g:i A', strtotime($slot)),
        'booked' => in_array($slot, $booked, true),
    ];
}

echo json_encode(['slots' => $slots]);
?>

