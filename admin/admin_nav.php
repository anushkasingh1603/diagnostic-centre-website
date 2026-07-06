<?php
// Shared admin navbar. Include at the top of <body> on each admin page.
$current = basename($_SERVER['PHP_SELF']);
function navActive($file, $current) { return $file === $current ? 'active fw-semibold' : ''; }
?>
<nav class="navbar navbar-expand-lg admin-navbar sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="dashboard.php">
      <i class="fa-solid fa-stethoscope me-2"></i> Admin Panel
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link <?php echo navActive('dashboard.php',$current); ?>" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('appointments.php',$current); ?>" href="appointments.php">Appointments</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('notices.php',$current); ?>" href="notices.php">Notices</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('doctors.php',$current); ?>" href="doctors.php">Doctors</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('services.php',$current); ?>" href="services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('careers.php',$current); ?>" href="careers.php">Careers</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('packages.php',$current); ?>" href="packages.php">Packages</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('reports.php',$current); ?>" href="reports.php">Reports</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('gallery.php',$current); ?>" href="gallery.php">Gallery</a></li>
        <li class="nav-item"><a class="nav-link <?php echo navActive('feedback.php',$current); ?>" href="feedback.php">Feedback</a></li>

      </ul>
      <a href="../logout.php" class="btn btn-light btn-sm rounded-pill px-3"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</a>
    </div>
  </div>
</nav>

<style>
  .admin-navbar {
    background: linear-gradient(90deg,#1d4ed8,#2563eb);
    box-shadow: 0 4px 14px rgba(0,0,0,.12);
    padding: 12px 0;
  }
  .admin-navbar .nav-link {
    color: rgba(255,255,255,.85);
    margin: 0 4px;
    border-radius: 8px;
    padding: 6px 14px !important;
    transition: background .15s ease, color .15s ease;
  }
  .admin-navbar .nav-link:hover { color:#fff; background: rgba(255,255,255,.15); }
  .admin-navbar .nav-link.active { color:#fff; background: rgba(255,255,255,.22); }
</style>

