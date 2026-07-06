<?php
session_start();
require_once __DIR__ . '/config/db.php';

$sql = "SELECT id, image_path, image_name, created_at
        FROM gallery
        ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$images = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gallery | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
    <div class="container">
      <a class="navbar-brand fw-bold text-primary" href="index.php">
        <i class="fa-solid fa-stethoscope me-2"></i>Burdwan Scan Centre
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item"><a class="nav-link" href="services_list.php">Services</a></li>
          <li class="nav-item"><a class="nav-link active" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa-solid fa-user-shield me-1"></i>Admin Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Gallery</h2>
    </div>


    <?php if (count($images) === 0): ?>
      <div class="alert alert-info">No images uploaded yet.</div>
    <?php else: ?>
      <div class="row g-3">
        <?php foreach ($images as $img): ?>
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100">
              <img
                src="<?php echo htmlspecialchars($img['image_path']); ?>"
                class="card-img-top"
                alt="<?php echo htmlspecialchars($img['image_name']); ?>"
                style="height:180px; object-fit:cover;"
              />
              <div class="card-body p-2">
                <div class="small text-muted"><?php echo htmlspecialchars($img['image_name']); ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>

