<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$services = [];

$stmt = mysqli_prepare($conn, 'SELECT id, name, short_description, price, is_active FROM services WHERE is_active = 1 ORDER BY created_at DESC, id DESC');
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($result && $row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Services | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Services</h2>
      <a href="index.php" class="btn btn-outline-secondary btn-sm">Home</a>
    </div>

    <?php if (count($services) === 0): ?>
      <div class="alert alert-info">No services available.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($services as $s): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="fw-bold"><?php echo h($s['name']); ?></h5>
                <?php if (!empty($s['price'])): ?>
                  <div class="text-muted mb-3"><strong>Price:</strong> <?php echo h($s['price']); ?></div>
                <?php endif; ?>
                <?php if (!empty($s['short_description'])): ?>
                  <p class="text-muted mb-3"><?php echo nl2br(h($s['short_description'])); ?></p>
                <?php endif; ?>

                <a href="services.php?id=<?php echo (int)$s['id']; ?>" class="btn btn-primary rounded-pill">
                  <i class="fa-solid fa-arrow-right me-2"></i>Learn More
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

