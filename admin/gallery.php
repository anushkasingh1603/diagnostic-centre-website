<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

$message = '';
$error = '';

$uploadDir = __DIR__ . '/../uploads/gallery';
$publicDir = 'uploads/gallery';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_gallery'])) {
    if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please choose an image file.';
    } else {
        $file = $_FILES['image_file'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed, true)) {
            $error = 'Allowed file types: jpg, jpeg, png.';
        } else {
            $maxSize = 6 * 1024 * 1024; // 6MB
            if ($file['size'] > $maxSize) {
                $error = 'File too large. Max 6MB.';
            } else {
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', basename($file['name']));
                $newName = 'gallery_' . $_SESSION['user_id'] . '_' . time() . '_' . $safeName;
                $destPath = $uploadDir . '/' . $newName;

                if (move_uploaded_file($file['tmp_name'], $destPath)) {
                    $stmt = mysqli_prepare(
                        $conn,
                        'INSERT INTO gallery (uploaded_by, image_path, image_name) VALUES (?, ?, ?)'
                    );
                    if (!$stmt) {
                        $error = 'Database error.';
                    } else {
                        $relativePath = $publicDir . '/' . $newName;

                        // uploaded_by has a FK to users(id); avoid FK violations by inserting NULL.
                        $nullUploadedBy = null;
                        mysqli_stmt_bind_param($stmt, 'iss', $nullUploadedBy, $relativePath, $file['name']);

                        if (mysqli_stmt_execute($stmt)) {
                            $message = 'Image uploaded successfully.';
                        } else {
                            $error = 'Could not save image.';
                        }
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $error = 'Upload failed.';
                }
            }
        }
    }
}

// Delete
$deleteId = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
if ($deleteId > 0) {
    // Find file path first
    $sel = mysqli_prepare($conn, 'SELECT image_path FROM gallery WHERE id = ?');
    if ($sel) {
        mysqli_stmt_bind_param($sel, 'i', $deleteId);
        mysqli_stmt_execute($sel);
        $res = mysqli_stmt_get_result($sel);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($sel);

        if ($row && isset($row['image_path'])) {
            $fullPath = __DIR__ . '/../' . str_replace('..', '', $row['image_path']);
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }

    $del = mysqli_prepare($conn, 'DELETE FROM gallery WHERE id = ?');
    if ($del) {
        mysqli_stmt_bind_param($del, 'i', $deleteId);
        mysqli_stmt_execute($del);
        mysqli_stmt_close($del);
    }

    header('Location: gallery.php');
    exit();
}

// Fetch
$sql = "SELECT id, image_path, image_name, created_at
        FROM gallery
        ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
$images = [];
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $images[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Gallery | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0">Admin - Gallery</h2>

  </div>

  <?php if ($message !== ''): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <?php if ($error !== ''): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3">Upload Image</h5>
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <input type="file" name="image_file" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" name="upload_gallery" class="btn btn-primary">Upload</button>
      </form>
    </div>
  </div>

  <div class="row g-3">
    <?php if (count($images) === 0): ?>
      <div class="col-12">
        <div class="alert alert-info">No gallery images yet.</div>
      </div>
    <?php else: ?>
      <?php foreach ($images as $img): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <div class="card h-100">
            <img src="<?php echo htmlspecialchars($img['image_path']); ?>" class="card-img-top" style="height:180px; object-fit:cover;" alt="<?php echo htmlspecialchars($img['image_name']); ?>">
            <div class="card-body p-2">
              <div class="small text-muted text-truncate"><?php echo htmlspecialchars($img['image_name']); ?></div>
              <div class="mt-2">
                <a class="btn btn-sm btn-danger w-100" href="gallery.php?delete_id=<?php echo (int)$img['id']; ?>" onclick="return confirm('Delete this image?')">Delete</a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

