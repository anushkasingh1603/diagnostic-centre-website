<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_report'])) {
    $patient_name = trim($_POST['patient_name'] ?? '');
    $service = trim($_POST['service'] ?? '');

    if ($patient_name === '' || $service === '') {
        $error = 'Patient name and service are required.';
    } else {
        if (!isset($_FILES['report_file']) || $_FILES['report_file']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Please choose a valid file.';
        } else {
            $file = $_FILES['report_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['pdf','png','jpg','jpeg'];

            if (!in_array($ext, $allowed, true)) {
                $error = 'Allowed file types: pdf, png, jpg, jpeg.';
            } else {
                $maxSize = 8 * 1024 * 1024; // 8MB
                if ($file['size'] > $maxSize) {
                    $error = 'File too large. Max 8MB.';
                } else {
                    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', basename($file['name']));
                    $newName = 'report_' . $_SESSION['user_id'] . '_' . time() . '_' . $safeName;
                    $destDir = __DIR__ . '/uploads';
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0777, true);
                    }
                    $destPath = $destDir . '/' . $newName;

                    if (move_uploaded_file($file['tmp_name'], $destPath)) {
                        $stmt = mysqli_prepare(
                            $conn,
                            'INSERT INTO reports (user_id, patient_name, service, file_path, file_name) VALUES (?, ?, ?, ?, ?)'
                        );
                        if (!$stmt) {
                            $error = 'Database error.';
                        } else {
                            $relativePath = 'uploads/' . $newName;
                            mysqli_stmt_bind_param(
                                $stmt,
                                'issss',
                                $_SESSION['user_id'],
                                $patient_name,
                                $service,
                                $relativePath,
                                $file['name']
                            );
                            if (mysqli_stmt_execute($stmt)) {
                                $message = 'Report uploaded successfully.';
                            } else {
                                $error = 'Could not save upload.';
                            }
                            mysqli_stmt_close($stmt);
                        }
                    } else {
                        $error = 'Upload failed. Try again.';
                    }
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Upload Report | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Upload Report</h2>
      <a href="login.php" class="btn btn-outline-secondary">Home</a>
    </div>

    <?php if ($message !== ''): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Patient Name</label>
        <input type="text" name="patient_name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Service</label>
        <select name="service" class="form-select" required>
          <option value="MRI Scan">MRI Scan</option>
          <option value="CT Scan">CT Scan</option>
          <option value="Ultrasound">Ultrasound</option>
          <option value="ECG">ECG</option>
          <option value="Digital X-Ray">Digital X-Ray</option>
          <option value="Pathology Tests">Pathology Tests</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Select File</label>
        <input type="file" name="report_file" class="form-control" accept="application/pdf,image/*" required>
        <div class="form-text">PDF/JPG/PNG up to 8MB.</div>
      </div>

      <button type="submit" name="upload_report" class="btn btn-primary">Upload</button>
    </form>
  </div>
</body>
</html>

