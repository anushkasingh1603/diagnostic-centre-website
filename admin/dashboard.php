<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Basic analytics counts (health_centre DB)
$stats = [
    'appointments' => 0,
    'notices'      => 0,
    'doctors'      => 0,
    'services'     => 0,
    'careers'      => 0,
    'packages'     => 0,
];

$queries = [
    'appointments' => 'SELECT COUNT(*) AS c FROM appointments',
    'notices'      => 'SELECT COUNT(*) AS c FROM notices',
    'doctors'      => 'SELECT COUNT(*) AS c FROM doctors',
    'services'     => 'SELECT COUNT(*) AS c FROM services',
    'careers'      => 'SELECT COUNT(*) AS c FROM careers',
    'packages'     => 'SELECT COUNT(*) AS c FROM packages',
];

foreach ($queries as $key => $sql) {
    $res = mysqli_query($conn, $sql);
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        if ($row && isset($row['c'])) {
            $stats[$key] = (int)$row['c'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard | Burdwan Scan Centre</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS (matches main site styling) -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

  <?php include __DIR__ . '/admin_nav.php'; ?>

  <section class="py-4">

    <div class="container">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
          <h2 class="fw-bold mb-1">Admin Dashboard</h2>
          <p class="text-muted mb-0">Quick overview of your centre data</p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="d-flex align-items-center justify-content-between">
                <div class="bg-primary text-white rounded-3 p-3">
                  <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
                </div>
              </div>
              <div class="mt-3">
                <div class="text-muted">Appointments</div>
                <div class="display-6 fw-bold"><?= (int)$stats['appointments'] ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="bg-success text-white rounded-3 p-3">
                <i class="fa-solid fa-bullhorn" aria-hidden="true"></i>
              </div>
              <div class="mt-3">
                <div class="text-muted">Notices</div>
                <div class="display-6 fw-bold"><?= (int)$stats['notices'] ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="bg-warning text-dark rounded-3 p-3">
                <i class="fa-solid fa-user-doctor" aria-hidden="true"></i>
              </div>
              <div class="mt-3">
                <div class="text-muted">Doctors</div>
                <div class="display-6 fw-bold"><?= (int)$stats['doctors'] ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="bg-info text-white rounded-3 p-3">
                <i class="fa-solid fa-list-check" aria-hidden="true"></i>
              </div>
              <div class="mt-3">
                <div class="text-muted">Services</div>
                <div class="display-6 fw-bold"><?= (int)$stats['services'] ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="bg-danger text-white rounded-3 p-3">
                <i class="fa-solid fa-briefcase" aria-hidden="true"></i>
              </div>
              <div class="mt-3">
                <div class="text-muted">Careers</div>
                <div class="display-6 fw-bold"><?= (int)$stats['careers'] ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
              <div class="bg-secondary text-white rounded-3 p-3">
                <i class="fa-solid fa-tags" aria-hidden="true"></i>
              </div>
              <div class="mt-3">
                <div class="text-muted">Packages</div>
                <div class="display-6 fw-bold"><?= (int)$stats['packages'] ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </section>

  <footer class="bg-dark text-white py-5 mt-4">

    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="text-white-50">© <?= date('Y') ?> Burdwan Scan Centre. All rights reserved.</div>
        <div class="text-white-50">Admin Panel</div>
      </div>
    </div>
  </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

