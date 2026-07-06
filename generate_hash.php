<?php
// One-time helper script to generate a password hash for the admins.password column.
// Usage:
//   1) Put this file in the project root (this location).
//   2) Open in browser: http://localhost/diagnostic-centre-website/generate_hash.php
//      or run via CLI.
//
//   It will output password_hash($plain, PASSWORD_DEFAULT).

$error = '';
$plain = '';
$hash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plain = (string)($_POST['password'] ?? '');

    if ($plain === '') {
        $error = 'Enter a password.';
    } else {
        $hash = password_hash($plain, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Generate Admin Password Hash</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-7 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="mb-3">Generate password_hash() for admins.password</h3>
            <p class="text-muted">After generating, copy the hash and update the database manually (do not change any existing admin rows automatically).</p>

            <?php if ($error !== ''): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
              <label class="form-label fw-semibold">Plain password</label>
              <input type="password" name="password" class="form-control" required autofocus />
              <div class="mt-3">
                <button type="submit" class="btn btn-primary w-100">Generate Hash</button>
              </div>
            </form>

            <?php if ($hash !== ''): ?>
              <div class="mt-4">
                <label class="form-label fw-semibold">Generated hash (copy this)</label>
                <textarea class="form-control" rows="4" readonly><?php echo htmlspecialchars($hash, ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>

