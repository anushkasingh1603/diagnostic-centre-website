<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Fetch booked appointments (admin)
$sql = "SELECT id, user_id, patient_name, email, phone, service, appointment_date, appointment_time, status, created_at
        FROM appointments
        ORDER BY appointment_date DESC, appointment_time DESC, id DESC";

$stmt = mysqli_prepare($conn, $sql);
$appointments = [];

if ($stmt) {
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $appointments[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
} else {
    // Let the page render even if query fails
    // (display_errors is enabled at top)
}

$statusOptions = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Appointments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Admin Panel - Appointments</h2>

    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Patient</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Booked At</th>
            <th style="width:220px;">Update Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($appointments) === 0): ?>
            <tr><td colspan="11" class="text-center text-muted py-4">No appointments found.</td></tr>
          <?php else: ?>
            <?php foreach ($appointments as $a): ?>
              <tr>
                <td><?php echo (int)$a['id']; ?></td>
                <td><?php echo (int)($a['user_id'] ?? 0); ?></td>
                <td><?php echo h($a['patient_name'] ?? ''); ?></td>
                <td><?php echo h($a['email'] ?? ''); ?></td>
                <td><?php echo h($a['phone'] ?? ''); ?></td>
                <td><?php echo h($a['service'] ?? ''); ?></td>
                <td><?php echo h($a['appointment_date'] ?? ''); ?></td>
                <td><?php echo h($a['appointment_time'] ?? ''); ?></td>
                <td>
                  <span class="badge <?php echo (($a['status'] ?? '') === 'Confirmed') ? 'text-bg-success' : ((($a['status'] ?? '') === 'Pending') ? 'text-bg-warning' : 'text-bg-secondary'); ?>">
                    <?php echo h($a['status'] ?? ''); ?>
                  </span>
                </td>
                <td><?php echo h($a['created_at'] ?? ''); ?></td>
                <td>
                  <form method="POST" action="appointments_update.php" class="d-flex gap-2 align-items-center">
                    <input type="hidden" name="appointment_id" value="<?php echo (int)$a['id']; ?>" />
                    <select name="new_status" class="form-select form-select-sm" aria-label="New status">
                      <?php foreach ($statusOptions as $opt): ?>
                        <option value="<?php echo h($opt); ?>" <?php echo (($a['status'] ?? '') === $opt) ? 'selected' : ''; ?>>
                          <?php echo h($opt); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

