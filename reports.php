<?php
session_start();
require_once __DIR__ . '/config/db.php';

function h($v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// Prevent access to arbitrary local files.
ini_set('expose_php', '0');

$message = '';
$downloadName = '';

$inputCode = trim((string)($_POST['report_code'] ?? ''));
$inputPhone = trim((string)($_POST['phone'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportCode = $inputCode;
    $phone = $inputPhone;

    if ($reportCode !== '' && $phone !== '') {
        $sql = "SELECT id, patient_name, phone, file_path, file_name, created_at
                FROM reports
                WHERE report_code = ? AND phone = ?
                LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $reportCode, $phone);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);
            $report = $res ? mysqli_fetch_assoc($res) : null;
            mysqli_stmt_close($stmt);

            if (!$report) {
                $message = 'No report found for the details provided.';
            } else {
                $relativePath = (string)$report['file_path'];
                $relativePath = str_replace('..', '', $relativePath);

                $fullPath = __DIR__ . '/' . $relativePath;
                if (!is_file($fullPath)) {
                    $message = 'No report found for the details provided.';
                } else {
                    $downloadName = basename((string)$report['file_name']);

                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="' . h($downloadName) . '"');
                    header('Content-Length: ' . (string)filesize($fullPath));
                    header('X-Content-Type-Options: nosniff');

                    readfile($fullPath);
                    exit();
                }
            }
        } else {
            $message = 'No report found for the details provided.';
        }
    } else {
        $message = 'No report found for the details provided.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Download Patient Report | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f7f9fc; }
  </style>
</head>
<body>
  <div class="container py-5">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-md-5">
        <div class="text-center mb-4">
          <h2 class="fw-bold mb-1">Download Patient Report</h2>
          <p class="text-muted mb-0">Enter your report code and phone number to verify.</p>
        </div>

        <?php if ($message !== ''): ?>
          <div class="alert alert-danger" role="alert"><?php echo h($message); ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
          <div class="mb-3">
            <label class="form-label fw-semibold">Report Code</label>
            <input type="text" name="report_code" class="form-control" required value="<?php echo h($inputCode); ?>" inputmode="text" style="letter-spacing: 0.08em;">
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Phone Number</label>
            <input type="text" name="phone" class="form-control" required value="<?php echo h($inputPhone); ?>" inputmode="tel">
            <div class="form-text">Use the phone number provided at the time of report registration/upload.</div>
          </div>

          <button type="submit" class="btn btn-primary w-100">Download Report</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

