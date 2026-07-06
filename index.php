<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/db.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$today = date('Y-m-d');

// Fetch notices for homepage
$notices = [];
$sqlNotices = "SELECT id, title, message, display_from, display_to, sort_order, is_active
               FROM notices
               WHERE is_active = 1
                 AND (display_from IS NULL OR display_from <= ?)
                 AND (display_to IS NULL OR display_to >= ?)
               ORDER BY sort_order ASC, id DESC
               LIMIT 6";
$stmt = mysqli_prepare($conn, $sqlNotices);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ss', $today, $today);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) $notices[] = $row;
    }
    mysqli_stmt_close($stmt);
}

// Fetch doctors for homepage
$doctors = [];
$sqlDoctors = "SELECT id, name, specialization, bio, image_path, tags, sort_order
                FROM doctors
                WHERE is_active = 1
                ORDER BY sort_order ASC, id DESC
                LIMIT 9";
$res = mysqli_query($conn, $sqlDoctors);
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) $doctors[] = $row;
}
// Fetch services for homepage
$services = [];
$resS = mysqli_query($conn, "SELECT id, name, short_description FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
if ($resS) {
    while ($row = mysqli_fetch_assoc($resS)) $services[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Burdwan Scan Centre | Accurate Diagnostics</title>


  <meta name="description" content="Book appointments and get accurate diagnostic reports faster. Secure report portal, expert doctors, and advanced imaging services." />
  <meta name="keywords" content="diagnostic centre, MRI, CT Scan, X-Ray, Ultrasound, ECG, pathology, lab tests, report download" />
  <meta name="robots" content="index,follow" />

  <!-- Open Graph -->
  <meta property="og:title" content="Burdwan Scan Centre | Accurate Diagnostics" />
  <meta property="og:description" content="Book appointments and access reports securely online." />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="http://localhost/diagnostic-centre-website/index.php" />
  <meta property="og:image" content="http://localhost/diagnostic-centre-website/assets/images/xray.jpg" />

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
            // Marquee style continuous scroll
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
          <img src="assets/images/badges/logo.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="Burdwan Scan Centre logo" loading="lazy">

        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/nabl.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="NABL Accredited Laboratory" loading="lazy">

        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/smf.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="State Medical Faculty Affiliated Training Institution" loading="lazy">

        </div>
        <div class="col-6 col-sm-3 d-flex justify-content-center">
          <img src="assets/images/badges/30years.png" class="img-fluid" style="height:80px; width:auto; object-fit:contain;" alt="30 Years of Diagnostic Excellence" loading="lazy">

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
          <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="services_list.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="packages.php">Packages</a></li>




          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>

          <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>

          <li class="nav-item">
            <a class="nav-link" href="login.php"><i class="fa-solid fa-user-shield me-1"></i>Admin Login</a>
          </li>
        </ul>


      </div>
    </div>
  </nav>

  <!-- Hero Carousel -->
  <header id="home" class="hero">
    <div class="container">

      <div class="hero-band text-center py-5">
        <h1 class="hero-title mb-3">Accurate Diagnostics. Faster Reports.</h1>
        <p class="hero-subtitle mb-0">Trusted by Burdwan for over 31 years — advanced imaging, NABL-accredited lab, and secure online reports.</p>
      </div>


      <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">

        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="assets/images/slider/slide1.jpg" class="d-block w-100 hero-img" alt="Slide 1">
          </div>

        <div class="carousel-item">
            <img src="assets/images/slider/slide2.jpg" class="d-block w-100 hero-img" alt="Slide 2" loading="lazy">

        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide3.jpg" class="d-block w-100 hero-img" alt="Slide 3">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide4.jpg" class="d-block w-100 hero-img" alt="Slide 4">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide5.jpg" class="d-block w-100 hero-img" alt="Slide 5">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide6.jpg" class="d-block w-100 hero-img" alt="Slide 6">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide7.jpg" class="d-block w-100 hero-img" alt="Slide 7">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide8.jpg" class="d-block w-100 hero-img" alt="Slide 8">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide9.jpg" class="d-block w-100 hero-img" alt="Slide 9">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide10.jpg" class="d-block w-100 hero-img" alt="Slide 10">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide11.jpg" class="d-block w-100 hero-img" alt="Slide 11">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide12.jpg" class="d-block w-100 hero-img" alt="Slide 12">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide13.jpg" class="d-block w-100 hero-img" alt="Slide 13">
        </div>
        <div class="carousel-item">
          <img src="assets/images/slider/slide14.jpg" class="d-block w-100 hero-img" alt="Slide 14">
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </button>
    </div>
  </header>

  <!-- Quick Action Cards -->
  <section class="quick-actions py-5" aria-label="Quick actions">
    <div class="container">
      <div class="row g-3 justify-content-center">
        <div class="col-12 col-md-4">
          <a href="reports.php" class="action-card card h-100 text-decoration-none">
            <div class="card-body">
              <div class="action-icon bg-success text-white mb-3"><i class="fa-solid fa-file-medical"></i></div>
              <h5 class="card-title fw-bold">View Report</h5>
              <p class="card-text text-muted mb-0">Download securely online.</p>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-4">
          <a href="appointment.php" class="action-card card h-100 text-decoration-none">
            <div class="card-body">
              <div class="action-icon bg-primary text-white mb-3"><i class="fa-solid fa-calendar-check"></i></div>
              <h5 class="card-title fw-bold">Book Appointment</h5>
              <p class="card-text text-muted mb-0">Choose your date and time slot.</p>
            </div>
          </a>
        </div>

        <div class="col-12 col-md-4">
          <a href="payment.php" class="action-card card h-100 text-decoration-none">
            <div class="card-body">
              <div class="action-icon bg-warning text-dark mb-3"><i class="fa-solid fa-credit-card"></i></div>
              <h5 class="card-title fw-bold">Pay Online</h5>
              <p class="card-text text-muted mb-0">Pay securely online via UPI, card, or netbanking.</p>
            </div>
          </a>
        </div>

      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-5">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <img src="assets/images/about.jpg" class="img-fluid rounded-4 shadow" alt="Diagnostics">
        </div>
        <div class="col-lg-6">
          <h2 class="fw-bold mb-3 section-title">About Burdwan Scan Centre</h2>
          <p class="text-muted lead">Burdwan Scan Centre Pvt. Ltd. is a centre of diagnostic excellence at 7, R.B. Ghosh Road, Khoshbagan, Burdwan – 713101, West Bengal. For more than 31 years, we have been committed to delivering accurate, high-quality diagnostic services across both imaging and pathology at a cost our patients can afford. Over this journey we have continually invested in advanced, high-end equipment and the latest technologies, supported by a team of highly qualified specialists, to ensure reliable results for every patient. To uphold the highest standards in our diagnostic services, our laboratory is accredited by NABL. Our well-established infrastructure also supports paramedical training courses affiliated with the State Medical Faculty, Government of West Bengal.</p>

          <div class="row g-3 mt-3">
            <div class="col-12 col-md-6">
              <div class="info-box p-3 rounded-4 border bg-light h-100">
                <div class="d-flex align-items-start gap-3">
                  <div class="info-icon bg-primary text-white rounded-3"><i class="fa-solid fa-bullseye"></i></div>
                  <div>
                    <h6 class="fw-bold mb-1">Mission</h6>
                    <p class="text-muted mb-0">Accurate tests, faster reports, and compassionate support for every patient.</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="info-box p-3 rounded-4 border bg-light h-100">
                <div class="d-flex align-items-start gap-3">
                  <div class="info-icon bg-success text-white rounded-3"><i class="fa-solid fa-eye"></i></div>
                  <div>
                    <h6 class="fw-bold mb-1">Vision</h6>
                    <p class="text-muted mb-0">Become the most trusted diagnostic partner through quality, safety, and innovation.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4 d-flex gap-3 flex-wrap">
            <div class="badge-box border rounded-4 p-3 bg-white">
              <i class="fa-solid fa-shield-heart text-primary mb-2 d-block"></i>
              <div class="fw-semibold">Accredited Lab</div>
              <div class="text-muted small">Quality standards</div>
            </div>
            <div class="badge-box border rounded-4 p-3 bg-white">
              <i class="fa-solid fa-user-doctor text-success mb-2 d-block"></i>
              <div class="fw-semibold">Expert Team</div>
              <div class="text-muted small">Specialist support</div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="choose-us bg-light py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title">Why Choose Us</h2>
        <p class="text-muted mb-0">Modern care with fast and accurate diagnostics.</p>
      </div>

      <div class="row g-4">
        <div class="col-12 col-md-6 col-lg-3">
          <div class="feature-card p-4 h-100 text-center">
            <div class="feature-icon mx-auto mb-3"><i class="fa-solid fa-microscope"></i></div>
            <h5 class="fw-bold">Experienced Doctors</h5>
            <p class="text-muted mb-0">Qualified specialists guide every case.</p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="feature-card p-4 h-100 text-center">
            <div class="feature-icon mx-auto mb-3"><i class="fa-solid fa-radiation"></i></div>
            <h5 class="fw-bold">Advanced Equipment</h5>
            <p class="text-muted mb-0">State-of-the-art imaging for clarity.</p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="feature-card p-4 h-100 text-center">
            <div class="feature-icon mx-auto mb-3"><i class="fa-solid fa-bolt"></i></div>
            <h5 class="fw-bold">Fast Reports</h5>
            <p class="text-muted mb-0">Quick processing and secure access.</p>
          </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
          <div class="feature-card p-4 h-100 text-center">
            <div class="feature-icon mx-auto mb-3"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <h5 class="fw-bold">Affordable Pricing</h5>
            <p class="text-muted mb-0">Transparent options for every patient.</p>
          </div>
        </div>

        <div class="col-12">
          <div class="feature-wide p-4 mt-2 d-flex align-items-center justify-content-center gap-3 rounded-4 bg-white border">
            <div class="feature-icon-small"><i class="fa-solid fa-clock"></i></div>
            <div class="fw-semibold">24/7 Support for Appointment & Report Help</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="services py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title">Services</h2>
        <p class="text-muted mb-0">Explore imaging and diagnostic tests.</p>
      </div>

  
    <div class="row g-4">
        <?php if (count($services) === 0): ?>
          <div class="col-12 text-center text-muted">No services available.</div>
        <?php else: ?>
          <?php foreach (array_slice($services, 0, 4) as $s): ?>

            <div class="col-12 col-md-6 col-lg-4">
              <div class="service-card card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                  <div class="service-icon mb-3 bg-primary text-white"><i class="fa-solid fa-notes-medical"></i></div>
                  <h5 class="fw-bold"><?php echo h($s['name']); ?></h5>
                  <p class="text-muted"><?php echo h($s['short_description']); ?></p>
                  <a href="services.php?id=<?php echo (int)$s['id']; ?>" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fa-solid fa-arrow-right me-2"></i>Learn More</a>
                </div>
              </div>
            </div>
         <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php if (count($services) > 4): ?>
        <div class="text-center mt-5">
          <a href="services_list.php" class="btn btn-primary btn-lg rounded-pill px-4">
            <i class="fa-solid fa-list me-2"></i>View All Services
          </a>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <!-- Statistics Counters -->
  <section class="stats py-5 bg-light">
    <div class="container">
      <div class="row g-4 text-center">
        <div class="col-6 col-md-3">
          <div class="stat-box p-4 rounded-4 border bg-white">
            <div class="stat-icon mb-2"><i class="fa-solid fa-user-check"></i></div>
            <div class="stat-value" data-target="50000">0</div>
            <div class="text-muted">Patients Served</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-box p-4 rounded-4 border bg-white">
            <div class="stat-icon mb-2"><i class="fa-solid fa-file-medical"></i></div>
            <div class="stat-value" data-target="35000">0</div>
            <div class="text-muted">Reports Generated</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-box p-4 rounded-4 border bg-white">
            <div class="stat-icon mb-2"><i class="fa-solid fa-stethoscope"></i></div>
            <div class="stat-value" data-target="35">0</div>
            <div class="text-muted">Doctors</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="stat-box p-4 rounded-4 border bg-white">
            <div class="stat-icon mb-2"><i class="fa-solid fa-hourglass"></i></div>
            <div class="stat-value" data-target="30">0</div>
            <div class="text-muted">Years Experience</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Carousel -->
  <section class="testimonials py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title">Patient Testimonials</h2>
        <p class="text-muted mb-0">What patients say about our services.</p>
      </div>

      <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="testimonial-card p-4 rounded-4 border bg-white">
              <div class="stars text-warning mb-2">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <p class="mb-3 text-muted">“Excellent diagnostic services and very fast reports. Support was quick and helpful.”</p>
              <div class="fw-semibold">— Patient, West Bengal</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="testimonial-card p-4 rounded-4 border bg-white">
              <div class="stars text-warning mb-2">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <p class="mb-3 text-muted">“Clean environment, friendly staff, and accurate test results. Highly recommended.”</p>
              <div class="fw-semibold">— Patient, India</div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="testimonial-card p-4 rounded-4 border bg-white">
              <div class="stars text-warning mb-2">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
              </div>
              <p class="mb-3 text-muted">“Easy report download portal. Admin team guided me properly.”</p>
              <div class="fw-semibold">— Patient, West Bengal</div>
            </div>
          </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev" aria-label="Previous">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next" aria-label="Next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
      </div>
    </div>
  </section>

  <!-- Gallery Preview -->
  <section id="gallery" class="gallery py-5">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
          <h2 class="fw-bold section-title mb-1">Gallery Preview</h2>
          <p class="text-muted mb-0">Latest images from our diagnostic centre.</p>
        </div>
        <a href="gallery.php" class="btn btn-primary rounded-pill">
          <i class="fa-solid fa-images me-2"></i>View Full Gallery
        </a>
      </div>

      <div class="row g-3" id="galleryPreview">
        <div class="col-12 text-center text-muted">Loading gallery...</div>
      </div>
    </div>
  </section>

  <!-- Latest Notices/News (Dynamic) -->
  <section class="notices py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title">Latest Notices</h2>
        <p class="text-muted mb-0">Announcements and service updates.</p>
      </div>

      <div class="row g-4" id="noticesCards">
        <?php if (count($notices) === 0): ?>
          <div class="col-12 text-center text-muted">No active notices at the moment.</div>
        <?php else: ?>
          <?php foreach (array_slice($notices, 0, 6) as $n): ?>
            <?php
              $title = h($n['title']);
              $msg = h($n['message']);
              $idx = ((int)$n['sort_order'] ?? 0) % 3;
              $badgeClass = $idx === 0 ? 'text-primary' : ($idx === 1 ? 'text-success' : 'text-warning');
              $icon = $idx === 0 ? 'fa-calendar-day' : ($idx === 1 ? 'fa-clock' : 'fa-truck-fast');
            ?>
            <div class="col-12 col-md-6 col-lg-4">
              <div class="notice-card p-4 border rounded-4 bg-white h-100">
                <div class="notice-date <?php echo $badgeClass; ?> fw-semibold mb-2"><i class="fa-solid <?php echo $icon; ?> me-2"></i><?php echo $idx === 0 ? 'Today' : ($idx === 1 ? 'This week' : 'Ongoing'); ?></div>
                <h5 class="fw-bold"><?php echo $title; ?></h5>
                <p class="text-muted mb-0"><?php echo $msg; ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="contact py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold section-title">Contact Us</h2>
        <p class="text-muted mb-0">For appointments, report downloads, or any assistance.</p>
      </div>

      <div class="row g-4 align-items-stretch">
        <div class="col-12 col-lg-5">
          <div class="contact-card border rounded-4 shadow-sm h-100 p-4 bg-white">
            <h4 class="fw-bold mb-3"><i class="fa-solid fa-phone me-2 text-primary"></i>Reach Us</h4>

              <div class="d-flex gap-3 mb-3">
                <div class="contact-icon bg-primary text-white rounded-3"><i class="fa-solid fa-location-dot"></i></div>
                <div>
                  <div class="fw-semibold">Main Office</div>
                  <div class="text-muted">7, R.B. Ghosh Road, Khoshbagan, Burdwan – 713101, WB (Just behind the Khoshbagan Masjid)</div>
                  <div class="fw-semibold mt-2">Phone</div>
                  <div class="text-muted">+91-0342-2550829, 2567533</div>
                  <div class="fw-semibold mt-2">Mobile</div>
                  <div class="text-muted">+91-8373070572, 9332029268</div>
                  <div class="fw-semibold mt-2">Email</div>
                  <div class="text-muted">bscpl_bdn@rediffmail.com</div>
                  <div class="fw-semibold mt-2">Opening Time</div>
                  <div class="text-muted">Everyday 7:30am to 7:30pm</div>
                </div>
              </div>

              <div class="d-flex gap-3 mb-3">
                <div class="contact-icon bg-primary text-white rounded-3"><i class="fa-solid fa-location-dot"></i></div>
                <div>
                  <div class="fw-semibold">Branch Office</div>
                  <div class="text-muted">8, Keshabganj Chatti, G.T. Road, Burdwan – 713104, WB (Near Raja Petrol Pump)</div>
                  <div class="fw-semibold mt-2">Phone</div>
                  <div class="text-muted">+91-0342-2960218, 2960219</div>
                  <div class="fw-semibold mt-2">Mobile</div>
                  <div class="text-muted">8373070566</div>
                  <div class="fw-semibold mt-2">Opening Time</div>
                  <div class="text-muted">Everyday 7:30am to 7:30pm</div>
                </div>
              </div>


          </div>
        </div>

        <div class="col-12 col-lg-7">
          <div class="map-card border rounded-4 shadow-sm h-100 overflow-hidden">
            <div class="map-header p-3 bg-light border-bottom">
              <div class="fw-semibold"><i class="fa-solid fa-map me-2 text-primary"></i>Google Maps</div>
              <div class="text-muted small">Map placeholder</div>
            </div>
            <div class="map-placeholder" aria-label="Google Map">
              <iframe
                src="https://www.google.com/maps?q=7,+R.B.+Ghosh+Road,+Khoshbagan,+Burdwan,+West+Bengal+713101,+India&output=embed"
                width="100%"
                height="450"
                style="border:0;"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
              ></iframe>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
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

  <!-- Floating WhatsApp Button (clearly visible; fixed bottom-right) -->
  <?php
    // WhatsApp floating button (pre-filled booking message)
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
    .whatsapp-float i{
      color:#fff;
      font-size:24px;
      line-height:1;
    }
    .whatsapp-float:focus{
      outline:3px solid rgba(37,211,102,.35);
      outline-offset:2px;
    }
  </style>
  <a class="whatsapp-float" href="<?= htmlspecialchars($whatsappHref, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp (Book appointment)">
    <i class="fa-brands fa-whatsapp"></i>
  </a>



  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="assets/js/main.js"></script>
  <script src="assets/js/gallery_preview.js"></script>
  <script>
    // Force reload of styles to avoid caching issues during local dev.
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

