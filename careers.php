<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$stmt = mysqli_prepare($conn, 'SELECT id, title, description, posted_at FROM careers WHERE is_active = 1 ORDER BY posted_at DESC, id DESC');
$careers = [];
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($result && $row = mysqli_fetch_assoc($result)) {
        $careers[] = $row;
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Careers | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Careers</h2>
      <a href="index.php" class="btn btn-outline-secondary btn-sm">Home</a>
    </div>

    <?php if (count($careers) === 0): ?>
      <div class="alert alert-info">No active careers at the moment.</div>
    <?php else: ?>
      <div class="row g-4">
        <?php foreach ($careers as $c): ?>
          <div class="col-12 col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <h5 class="fw-bold"><?php echo h($c['title']); ?></h5>
                <?php if (!empty($c['description'])): ?>
                  <p class="text-muted mt-2 mb-0"><?php echo nl2br(h($c['description'])); ?></p>
                <?php endif; ?>
<?php if (!empty($c['posted_at'])): ?>
                  <div class="small text-muted mt-3">Posted: <?php echo h($c['posted_at']); ?></div>
                <?php endif; ?>
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

