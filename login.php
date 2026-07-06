<?php
session_start();

require_once __DIR__ . '/config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username !== '' && $password !== '') {
        // Parameterized query against admins table (health_centre DB)
        $sql = "SELECT id, username, password FROM admins WHERE username = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);

                // Password hashing verification (admins.password should store password_hash output)
                if (password_verify($password, (string)$row['password'])) {
                    $_SESSION['user_id'] = (int)$row['id'];
                    $_SESSION['role'] = 'admin';

                    // Also set the required session vars for admin UX
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_username'] = (string)$row['username'];

                    header('Location: admin/dashboard.php');
                    exit();
                }

            }

            $stmt->close();
        }
    }

    $error = 'Invalid username or password';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Burdwan Scan Centre</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light">

  <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="index.php">
        <i class="fa-solid fa-stethoscope me-2"></i>
        Burdwan Scan Centre
      </a>
    </div>
  </nav>

  <section class="py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-4">
                <img src="assets/images/logo.png" alt="Burdwan Scan Centre logo" style="height:56px; width:auto; object-fit:contain;" class="mb-3">
                <h2 class="fw-bold mb-1">Admin Login</h2>
                <p class="text-muted mb-0">Sign in to manage reports and appointments</p>
              </div>

              <?php if ($error !== ''): ?>
                <div class="alert alert-danger text-center" role="alert">
                  <i class="fa-solid fa-circle-exclamation me-2"></i>
                  <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
              <?php endif; ?>

              <form method="post" action="" autocomplete="off">
                <div class="mb-3">
                  <label class="form-label fw-semibold">Username</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="username" class="form-control" required autofocus>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Password</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                  <i class="fa-solid fa-right-to-bracket me-2"></i>Login
                </button>
              </form>

              <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none text-muted">
                  <i class="fa-solid fa-house me-1"></i>Back to site
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

