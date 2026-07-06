<?php
require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Toggle show_on_homepage
$toggleId = isset($_GET['toggle_id']) ? (int)$_GET['toggle_id'] : 0;
if ($toggleId > 0) {
    mysqli_query($conn, "UPDATE feedback SET show_on_homepage = 1 - show_on_homepage WHERE id = " . $toggleId);
    header('Location: feedback.php');
    exit();
}

// Delete
$deleteId = isset($_GET['delete_id']) ? (int)$_GET['delete_id'] : 0;
if ($deleteId > 0) {
    $stmt = mysqli_prepare($conn, 'DELETE FROM feedback WHERE id = ?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $deleteId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: feedback.php');
    exit();
}

$sql = "SELECT id, name, email, rating, message, show_on_homepage, created_at
        FROM feedback
        ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
$feedback = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $feedback[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Feedback | Diagnostic Centre</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

  <div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h2 class="mb-0">Admin - Feedback</h2>
    </div>

    <p class="text-muted">Tick "Show on Homepage" to display a review in the Patient Testimonials section.</p>

    <div class="table-responsive">
      <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Rating</th>
            <th>Message</th>
            <th>Created</th>
            <th>On Homepage</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($feedback) === 0): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">No feedback found.</td></tr>
          <?php else: ?>
            <?php foreach ($feedback as $f): ?>
              <tr>
                <td><?php echo (int)$f['id']; ?></td>
                <td><?php echo h($f['name']); ?></td>
                <td><?php echo h($f['email']); ?></td>
                <td class="text-warning text-nowrap">
                  <?php
                    $r = (int)($f['rating'] ?? 0);
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $r ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star text-muted"></i>';
                    }
                  ?>
                </td>
                <td style="max-width:360px;"><?php echo nl2br(h($f['message'])); ?></td>
                <td class="text-nowrap small"><?php echo h($f['created_at']); ?></td>
                <td class="text-center">
                  <?php if ((int)$f['show_on_homepage'] === 1): ?>
                    <span class="badge text-bg-success">Showing</span>
                  <?php else: ?>
                    <span class="badge text-bg-secondary">Hidden</span>
                  <?php endif; ?>
                </td>
                <td class="text-nowrap">
                  <a class="btn btn-sm <?php echo ((int)$f['show_on_homepage'] === 1) ? 'btn-outline-secondary' : 'btn-success'; ?>"
                     href="feedback.php?toggle_id=<?php echo (int)$f['id']; ?>">
                    <?php echo ((int)$f['show_on_homepage'] === 1) ? 'Hide' : 'Show on Homepage'; ?>
                  </a>
                  <a class="btn btn-sm btn-danger" href="feedback.php?delete_id=<?php echo (int)$f['id']; ?>" onclick="return confirm('Delete this feedback?')">Delete</a>
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


