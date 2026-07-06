<?php
require_once __DIR__ . '/config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>About Us | Burdwan Scan Centre</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <!-- Top Notice Marquee -->
  <div class="notice-bar py-2 border-bottom">
    <div class="container d-flex align-items-center gap-3">
      <div class="notice-label text-white px-3 py-1 rounded-2 bg-primary fw-semibold">
        <i class="fa-solid fa-bullhorn me-2"></i> Updates
      </div>
      <div class="notice-marquee flex-grow-1" aria-label="Scrolling announcements" style="overflow:hidden; white-space:nowrap;">
        <div class="marquee-content" role="marquee" style="display:flex; white-space:nowrap; overflow:hidden; animation: marquee-scroll 20s linear infinite;">
          <?php
            $announcement = "Burdwan Scan Centre remains open every day from 7:30am to 7:30pm | Home collection & report delivery facilities are also available at toll free no. 18008896257 | Customer Care Nos. +91 9332029268 / 8373070572 / 8373070566 | Job vacancy is now available — see details in the Careers section.";
            echo '<span class="me-5">' . h($announcement) . '</span>';
            echo '<span class="me-5">' . h($announcement) . '</span>';
            echo '<span class="me-5">' . h($announcement) . '</span>';
          ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Badge / Logo Strip -->
  <section class="badge-strip py-3 bg-white" aria-label="Accreditations">
    <div class="container">
      <div class="row g-3 justify-content-center align-items-center">
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/logo.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="Burdwan Scan Centre logo">
        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/nabl.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="NABL Accredited Laboratory">
        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/smf.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="State Medical Faculty Affiliated Training Institution">
        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/30years.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="30 Years of Diagnostic Excellence">
        </div>
      </div>
    </div>
  </section>

  <!-- Navbar -->
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
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#services">Services</a></li>

          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
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

  <!-- Page Header -->
  <header class="py-5" style="background: linear-gradient(180deg, rgba(13,110,253,0.08), rgba(13,110,253,0));">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <h1 class="fw-bold display-5 mb-3">About Burdwan Scan Centre</h1>
          <p class="lead text-muted mb-0">Accurate diagnostics with a patient-first approach.</p>
        </div>
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="d-flex align-items-start gap-3">
                <div class="feature-icon-small bg-primary text-white rounded-3 d-flex align-items-center justify-content-center" style="width:46px; height:46px;">
                  <i class="fa-solid fa-microscope"></i>
                </div>
                <div>
                  <div class="fw-semibold">Quality • Trust • Care</div>
                  <div class="text-muted small">Modern diagnostic facilities in Burdwan, West Bengal</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Company Profile -->
  <section class="py-5">
    <div class="container">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
          <h2 class="fw-bold mb-3"><i class="fa-solid fa-building me-2 text-primary"></i>Company Profile</h2>
          <p class="text-muted lead mb-0">
            Burdwan Scan Centre Pvt. Ltd. is the pioneer of providing modern diagnostic facilities in the District of Burdwan, West Bengal. It was established in 1992 under the West Bengal Clinical Establishment Act 1950. Since establishment, BSCPL has worked to provide quality and standard diagnostic solutions across both imaging and non-imaging divisions under one roof. We continually invest in advanced, high-end equipment and computer-controlled automated systems to ensure accuracy, precision, and reliability in our reports, supported by experienced medical professionals and a strong quality-control system, all dedicated to optimum patient care.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Our Leadership -->
  <section class="py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title mb-2">Our Leadership</h2>
        <p class="text-muted mb-0">Dedicated direction and clinical excellence</p>
      </div>

      <div class="row g-4">
        <div class="col-12 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
              <div class="d-flex justify-content-center mb-3">
                <img src="assets/images/director1.jpg" class="rounded-circle shadow-sm" style="width:140px; height:140px; object-fit:cover;" alt="Dr. Subhomoy Nag">
              </div>
              <h4 class="fw-bold mb-2">Dr. Subhomoy Nag</h4>
              <div class="text-muted mb-2">MBBS, MD (Radiodiagnosis)</div>
              <div class="fw-semibold text-primary"><i class="fa-solid fa-user-tie me-2"></i>Founder &amp; Managing Director</div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 text-center">
              <div class="d-flex justify-content-center mb-3">
                <img src="assets/images/director2.jpg" class="rounded-circle shadow-sm" style="width:140px; height:140px; object-fit:cover;" alt="Mrs. Anamika Nag">
              </div>
              <h4 class="fw-bold mb-2">Mrs. Anamika Nag</h4>
              <div class="fw-semibold text-primary"><i class="fa-solid fa-user-tie me-2"></i>Director</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Mission & Vision -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title mb-2">Mission & Vision</h2>
        <p class="text-muted mb-0">Focused on quality, reliability, and continuous improvement</p>
      </div>

      <div class="row g-4">
        <div class="col-12 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="feature-icon mx-auto mb-3" style="width:56px; height:56px; display:flex; align-items:center; justify-content:center; background:#0d6efd20; color:#0d6efd; border-radius:16px;">
                <i class="fa-solid fa-crosshairs"></i>
              </div>
              <h4 class="fw-bold mb-3"><i class="fa-solid fa-eye me-2 text-success"></i>Vision</h4>
              <p class="text-muted mb-0">To become a leader in the diagnostic healthcare sector by providing world-class diagnostic services through specialized technology and innovation, working closely with patients and clinicians to meet their individual needs.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="feature-icon mx-auto mb-3" style="width:56px; height:56px; display:flex; align-items:center; justify-content:center; background:#19875420; color:#198754; border-radius:16px;">
                <i class="fa-solid fa-shield-heart"></i>
              </div>
              <h4 class="fw-bold mb-3"><i class="fa-solid fa-user-check me-2 text-warning"></i>Quality</h4>
              <p class="text-muted mb-0">Our work is continuously monitored by our internal quality-control team. We are committed to excellence in scientific diagnosis across imaging and non-imaging services, and to continuously meeting and exceeding our patients' expectations.</p>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="feature-icon mx-auto mb-3" style="width:56px; height:56px; display:flex; align-items:center; justify-content:center; background:#ffc10720; color:#ffc107; border-radius:16px;">
                <i class="fa-solid fa-bullseye"></i>
              </div>
              <h4 class="fw-bold mb-3"><i class="fa-solid fa-hand-holding-medical me-2 text-danger"></i>Mission</h4>
              <p class="text-muted mb-0">To provide accurate and precise diagnostic services in a timely manner, maintaining world-class standards and quality, and delivering the best diagnostic care to patients at the most economical cost.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer (same as index.php) -->
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

  <!-- Floating WhatsApp Button -->
  <a class="whatsapp-float" href="https://wa.me/919332029268" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
  </a>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="assets/js/main.js"></script>
  <script>
    (function () {
      const links = document.querySelectorAll('link[rel="stylesheet"][href*="assets/css/style.css"]');
      links.forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && href.indexOf('v=') === -1) {
          link.setAttribute('href', href + (href.indexOf('?') === -1 ? '?v=' : '&v=') + Date.now());
        }
      });
    })();
  </script>
</body>

</html>

