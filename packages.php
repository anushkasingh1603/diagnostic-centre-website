<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Active packages only, ordered by sort_order
$stmt = mysqli_prepare(
    $conn,
    'SELECT id, name, description, tests_included, price, is_active, sort_order, created_at
     FROM packages
     WHERE is_active = 1
     ORDER BY sort_order ASC'
);
$packages = [];
if ($stmt) {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $packages[] = $row;
        }
    }
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Packages | Burdwan Scan Centre</title>

  <meta name="description" content="View and search diagnostic packages offered by Burdwan Scan Centre." />
  <meta name="robots" content="index,follow" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="assets/css/style.css">

  <style>
    .package-card{ height:100%; }
    .package-desc{ display:-webkit-box; -webkit-line-clamp:4; -webkit-box-orient:vertical; overflow:hidden; }
    .package-price{ font-weight:800; color:#0d6efd; }
  </style>
</head>
<body>
  <!-- Navbar (same style as index.php) -->
  <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3 sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="index.php">
        <i class="fa-solid fa-stethoscope me-2"></i>
        Burdwan Scan Centre
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#services">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#gallery">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
          <li class="nav-item">
            <a class="nav-link" href="login.php"><i class="fa-solid fa-user-shield me-1"></i>Admin Login</a>
          </li>
        </ul>

        <div class="d-none d-lg-flex ms-lg-3 gap-2">
          <a href="appointment.php" class="btn btn-primary rounded-pill px-4">
            <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Page Header / Search -->
  <section class="py-5" style="background:#eef7ff;">
    <div class="container">
      <div class="text-center mb-4">
        <h2 class="fw-bold section-title">Packages / Price List</h2>
        <p class="text-muted mb-0">Search by package name to quickly find the right test.</p>
      </div>

      <div class="row justify-content-center">
        <div class="col-12 col-md-8">
          <div class="input-group input-group-lg">
            <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
            <input id="packageSearch" type="search" class="form-control" placeholder="Search packages..." aria-label="Search packages" />
          </div>
          <div class="form-text">Tip: type a keyword like “Full Body” or “Heart”.</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Packages List -->
  <section class="py-5">
    <div class="container">
      <?php if (count($packages) === 0): ?>
        <div class="alert alert-info text-center">No active packages at the moment.</div>
      <?php else: ?>
        <div class="row g-4" id="packagesGrid">
          <?php foreach ($packages as $p): ?>
            <?php
              $name = h($p['name'] ?? '');
              $testsIncluded = h($p['tests_included'] ?? '');
              $description = h($p['description'] ?? '');
              $price = h($p['price'] ?? '');
              $dataName = strtolower(trim(htmlspecialchars_decode($name, ENT_QUOTES)));
            ?>

            <div class="col-12 col-md-6 col-lg-4 package-item" data-name="<?= h($dataName); ?>">
              <div class="card package-card shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                  <div>
                    <h5 class="fw-bold package-title"><?= $name; ?></h5>

                    <?php if ($testsIncluded !== ''): ?>
                      <div class="text-muted mt-2"><strong>Tests:</strong> <?= $testsIncluded; ?></div>
                    <?php endif; ?>

                    <?php if ($description !== ''): ?>
                      <p class="text-muted mt-2 mb-2 package-desc"><?= nl2br($description); ?></p>
                    <?php endif; ?>

                    <?php if ($price !== ''): ?>
                      <div class="package-price mt-auto pt-2">₹ <?= $price; ?></div>
                    <?php endif; ?>
                  </div>

                  <div class="mt-3">
                    <a href="appointment.php" class="btn btn-primary rounded-pill w-100">
                      <i class="fa-solid fa-calendar-check me-2"></i>Book Appointment
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer (same style as index.php) -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-12 col-lg-4">
          <h4 class="fw-bold mb-3"><i class="fa-solid fa-stethoscope me-2"></i>Burdwan Scan Centre</h4>
          <p class="text-white-50 mb-0">Trusted healthcare diagnostics with fast and secure report access.</p>
        </div>

        <div class="col-6 col-lg-2">
          <h5 class="text-white fw-semibold mb-3">Quick Links</h5>
          <ul class="list-unstyled text-white-50">
            <li class="mb-2"><a class="footer-link" href="index.php">Home</a></li>
            <li class="mb-2"><a class="footer-link" href="appointment.php">Book Appointment</a></li>
            <li class="mb-2"><a class="footer-link" href="reports.php">Reports</a></li>
            <li class="mb-2"><a class="footer-link" href="contact.html">Contact</a></li>
          </ul>
        </div>

        <div class="col-6 col-lg-3">
          <h5 class="text-white fw-semibold mb-3">Services</h5>
          <ul class="list-unstyled text-white-50">
            <li class="mb-2"><a class="footer-link" href="services.html">MRI</a></li>
            <li class="mb-2"><a class="footer-link" href="services.html">CT Scan</a></li>
            <li class="mb-2"><a class="footer-link" href="services.html">Ultrasound</a></li>
            <li class="mb-2"><a class="footer-link" href="services.html">ECG / Echo</a></li>
          </ul>
        </div>

        <div class="col-12 col-lg-3">
          <h5 class="text-white fw-semibold mb-3">Contact Info</h5>
          <div class="text-white-50">
            <div class="mb-2"><i class="fa-solid fa-phone me-2"></i>+91 9332029268 / 8373070572 / 8373070566</div>
            <div class="mb-2"><i class="fa-solid fa-phone me-2"></i>18008896257</div>
            <div class="mb-2"><i class="fa-solid fa-location-dot me-2"></i>7, R.B. Ghosh Road, Khoshbagan, Burdwan – 713101, West Bengal</div>
            <div class="mb-2"><i class="fa-solid fa-envelope me-2"></i>bscpl_bdn@rediffmail.com</div>
          </div>
        </div>
      </div>

      <div class="border-top border-white-25 mt-4 pt-4 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="text-white-50">© <span id="year"></span> Burdwan Scan Centre. All rights reserved.</div>
        <div class="d-flex gap-3">
          <a class="text-white-50" href="#" aria-label="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a class="text-white-50" href="#" aria-label="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
          <a class="text-white-50" href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a class="text-white-50" href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Floating WhatsApp Button (same behavior as index.php) -->
  <?php
    $whatsappCentreNumber = '919332029268';
    $whatsappMessage = 'Hello Burdwan Scan Centre, I would like to book an appointment.';
    $whatsappMessageEncoded = rawurlencode($whatsappMessage);
    $whatsappHref = 'https://wa.me/' . $whatsappCentreNumber . '?text=' . $whatsappMessageEncoded;
  ?>
  <style>
    .whatsapp-float{
      position:fixed;
      bottom:20px;
      right:20px;
      z-index:9999;
      width:56px;
      height:56px;
      border-radius:50%;
      background:#25D366;
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow:0 10px 25px rgba(0,0,0,.25);
      text-decoration:none;
    }
    .whatsapp-float i{ color:#fff; font-size:24px; line-height:1; }
  </style>
  <a class="whatsapp-float" href="<?= htmlspecialchars($whatsappHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp (Book appointment)">
    <i class="fa-brands fa-whatsapp"></i>
  </a>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    (function () {
      var input = document.getElementById('packageSearch');
      var items = document.querySelectorAll('#packagesGrid .package-item');
      if (!input || !items.length) return;

      input.addEventListener('input', function () {
        var q = (input.value || '').toLowerCase().trim();
        items.forEach(function (el) {
          var name = (el.getAttribute('data-name') || '').toLowerCase();
          el.style.display = (!q || name.indexOf(q) !== -1) ? '' : 'none';
        });
      });
    })();
  </script>
</body>
</html>

