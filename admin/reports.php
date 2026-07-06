<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$message = '';
$error = '';

// Handle delete (admin only)
$deleteId = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
if ($deleteId > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM reports WHERE id = ?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $deleteId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: reports.php');
    exit();
}

function generateReportCode(mysqli $conn): string {
    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    for ($i = 0; $i < 20; $i++) {
        $candidate = '';
        for ($j = 0; $j < 8; $j++) {
            $candidate .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        $check = mysqli_prepare($conn, 'SELECT id FROM reports WHERE report_code = ? LIMIT 1');
        if ($check) {
            mysqli_stmt_bind_param($check, 's', $candidate);
            mysqli_stmt_execute($check);
            $res = mysqli_stmt_get_result($check);
            $exists = $res ? (mysqli_num_rows($res) > 0) : true;
            mysqli_stmt_close($check);

            if (!$exists) {
                return $candidate;
            }
        }
    }

    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_report'])) {
    $patientName = trim((string)($_POST['patient_name'] ?? ''));
    $phone = trim((string)($_POST['phone'] ?? ''));
    $service = trim((string)($_POST['service'] ?? ''));

    if ($patientName === '' || $phone === '' || $service === '') {
        $error = 'Patient name, phone number and service are required.';
    } elseif (!isset($_FILES['report_file']) || $_FILES['report_file']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please choose a valid file.';
    } else {
        $file = $_FILES['report_file'];
        $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
        $allowed = ['pdf'];

        if (!in_array($ext, $allowed, true)) {
            $error = 'Only PDF reports are allowed.';
        } else {
            $maxSize = 12 * 1024 * 1024; // 12MB
            if ($file['size'] > $maxSize) {
                $error = 'File too large. Max 12MB.';
            } else {
                $safeOriginal = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename((string)$file['name']));
                $uniqueSuffix = time() . '_' . bin2hex(random_bytes(4));
                $newName = 'report_' . $uniqueSuffix . '_' . $safeOriginal;

                $destDir = __DIR__ . '/../uploads/reports';
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0777, true);
                }

                // Deny executable extensions
                $lowerName = strtolower($newName);
                $blocked = ['.php', '.phtml', '.php3', '.php4', '.php5', '.phps', '.phar', '.asp', '.aspx', '.jsp'];
                foreach ($blocked as $extBlock) {
                    if (str_ends_with($lowerName, $extBlock)) {
                        $error = 'Invalid file name.';
                        break;
                    }
                }

                if ($error === '') {
                    $destPath = $destDir . '/' . $newName;
                    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
                        $error = 'Upload failed. Try again.';
                    } else {
                        $relativePath = 'uploads/reports/' . $newName;
                        $reportCode = generateReportCode($conn);

                        if ($reportCode === '') {
                            $error = 'Could not generate report code. Try again.';
                        } else {
                            $stmt = mysqli_prepare(
                                $conn,
                                'INSERT INTO reports (user_id, report_code, patient_name, phone, service, file_path, file_name)
                                 VALUES (?, ?, ?, ?, ?, ?, ?)'
                            );

                            if (!$stmt) {
                                $error = 'Database error.';
                            } else {
                                $nullUserId = null;
                                mysqli_stmt_bind_param(
                                    $stmt,
                                    'issssss',
                                    $nullUserId,
                                    $reportCode,
                                    $patientName,
                                    $phone,
                                    $service,
                                    $relativePath,
                                    $file['name']
                                );

                                if (mysqli_stmt_execute($stmt)) {
                                    $message = 'Report uploaded successfully. Report code: ' . $reportCode;
                                } else {
                                    $error = 'Could not save report metadata.';
                                }

                                mysqli_stmt_close($stmt);
                            }
                        }
                    }
                }
            }
        }
    }
}

// Load reports list for admin
$sql = "SELECT r.id, r.user_id, r.report_code, r.patient_name, r.phone, r.service, r.file_path, r.file_name, r.created_at
        FROM reports r
        ORDER BY r.created_at DESC";

$result = mysqli_query($conn, $sql);
$reports = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Reports | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Admin - Upload Patient Report</h2>

    </div>

    <?php if ($message !== ''): ?>
      <div class="alert alert-success"><?php echo h($message); ?></div>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <div class="alert alert-danger"><?php echo h($error); ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body p-4">
        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label fw-semibold">Patient Name</label>
              <input type="text" name="patient_name" class="form-control" required value="">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Phone Number</label>
              <input type="text" name="phone" class="form-control" required value="">
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Service</label>
              <input type="text" name="service" class="form-control" required value="">
            </div>
            <div class="col-md-2">
              <label class="form-label fw-semibold">PDF</label>
              <input type="file" name="report_file" class="form-control" accept="application/pdf" required>
            </div>
          </div>

          <div class="mt-3 d-flex gap-2">
            <button type="submit" name="upload_report" class="btn btn-primary">Upload</button>
            <a href="reports.php" class="btn btn-outline-secondary">Refresh</a>
          </div>
        </form>
      </div>
    </div>

    <h3 class="h5 mb-3">Uploaded Reports</h3>
    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Report Code</th>
            <th>Patient</th>
            <th>Phone</th>
            <th>Service</th>
            <th>Uploaded</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($reports) === 0): ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No reports found.</td></tr>
          <?php else: ?>
            <?php foreach ($reports as $r): ?>
              <tr>
                <td><?php echo (int)$r['id']; ?></td>
                <td><?php echo h($r['report_code'] ?? ''); ?></td>
                <td><?php echo h($r['patient_name']); ?></td>
                <td><?php echo h($r['phone']); ?></td>
                <td><?php echo h($r['service']); ?></td>
                <td><?php echo h($r['created_at']); ?></td>
                <td>
                  <a class="btn btn-sm btn-success" href="../download_report.php?id=<?php echo (int)$r['id']; ?>" target="_blank">Download</a>
                  <a class="btn btn-sm btn-danger ms-1" href="reports.php?delete_id=<?php echo (int)$r['id']; ?>" onclick="return confirm('Delete this report?')">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

