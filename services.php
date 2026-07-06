<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$service = null;
if ($id > 0) {
$stmt = mysqli_prepare($conn, 'SELECT id, name, full_description, prep_instructions, price FROM services WHERE id = ? AND is_active = 1');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $service = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
    }
}

$flash = '';
if ($id <= 0) {
    $flash = 'Invalid service id.';
} elseif (!$service) {
    $flash = 'Service not found.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $service ? h($service['name']) : 'Service'; ?> | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Service Details</h2>
      <a href="services_list.php" class="btn btn-outline-secondary btn-sm">Back to Services</a>
    </div>

    <?php if ($flash !== ''): ?>
      <div class="alert alert-info"><?php echo h($flash); ?></div>
    <?php else: ?>
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="fw-bold mb-2"><?php echo h($service['name']); ?></h3>
          <?php if (isset($service['price'])): ?>
            <div class="mb-3 text-muted">
              <strong>Price:</strong> <?php echo h($service['price']); ?>
            </div>
          <?php endif; ?>

          <?php if (!empty($service['full_description'])): ?>
            <h5 class="mt-4">Overview</h5>
            <p class="text-muted mb-0"><?php echo nl2br(h($service['full_description'])); ?></p>
          <?php endif; ?>

          <?php if (!empty($service['prep_instructions'])): ?>
            <h5 class="mt-4">Preparation Instructions</h5>
            <div class="text-muted"><?php echo nl2br(h($service['prep_instructions'])); ?></div>
          <?php endif; ?>

          <div class="mt-4">
            <a href="appointment.php?service_id=<?php echo (int)$service['id']; ?>" class="btn btn-primary rounded-pill">
              <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
            </a>
          </div>

        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

