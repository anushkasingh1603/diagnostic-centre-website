<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO careers (title, description, is_active, posted_at)
             VALUES (?, ?, ?, NOW())'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssi', $title, $description, $is_active);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: careers.php');
    exit();
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0 && $title !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE careers SET title=?, description=?, is_active=? WHERE id=?'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssii', $title, $description, $is_active, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: careers.php');
    exit();
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM careers WHERE id=?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: careers.php');
    exit();
}

$careers = [];
$res = mysqli_query($conn, 'SELECT id, title, description, is_active, posted_at FROM careers ORDER BY posted_at DESC, id DESC');
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $careers[] = $row;
    }
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editCareer = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM careers WHERE id=?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $editCareer = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Careers</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0">Admin Panel - Careers</h2>
    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>

    </div>
  </div>

  <?php if ($editCareer): ?>
    <div class="alert alert-info">Editing career #<?php echo (int)$editCareer['id']; ?></div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3"><?php echo $editCareer ? 'Edit Career' : 'Create Career'; ?></h5>

      <form method="POST" action="careers.php">
        <input type="hidden" name="action" value="<?php echo $editCareer ? 'update' : 'create'; ?>" />
        <?php if ($editCareer): ?>
          <input type="hidden" name="id" value="<?php echo (int)$editCareer['id']; ?>" />
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" required value="<?php echo h($editCareer['title'] ?? ''); ?>" />
          </div>

          <div class="col-md-3 d-flex align-items-end">
            <div class="form-check pb-2">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?php echo ((int)($editCareer['is_active'] ?? 1) === 1) ? 'checked' : ''; ?> />
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4"><?php echo h($editCareer['description'] ?? ''); ?></textarea>
          </div>

          <div class="col-12 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><?php echo $editCareer ? 'Save Changes' : 'Create Career'; ?></button>
          </div>

          <?php if ($editCareer): ?>
            <div class="col-12">
              <a class="btn btn-link text-decoration-none p-0" href="careers.php">Cancel Edit</a>
            </div>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Sort</th>
          <th>Active</th>
          <th>Created</th>
          <th style="width:220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($careers) === 0): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">No careers found.</td></tr>
        <?php else: ?>
          <?php foreach ($careers as $c): ?>
            <tr>
              <td><?php echo (int)$c['id']; ?></td>
              <td><?php echo h($c['title']); ?></td>
              <td><?php echo (int)($c['sort_order'] ?? 0); ?></td>
              <td>
                <span class="badge <?php echo ((int)($c['is_active'] ?? 0) === 1) ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                  <?php echo ((int)($c['is_active'] ?? 0) === 1) ? 'Active' : 'Inactive'; ?>
                </span>
              </td>
              <td><?php echo h($c['created_at'] ?? ''); ?></td>
              <td>
                <div class="d-flex gap-2 flex-wrap">
                  <a class="btn btn-sm btn-outline-primary" href="careers.php?edit=<?php echo (int)$c['id']; ?>">Edit</a>
                  <form method="POST" action="careers.php" onsubmit="return confirm('Delete this career?');">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id" value="<?php echo (int)$c['id']; ?>" />
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </div>
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

