<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {
    $name = trim($_POST['name'] ?? $_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');

    $sort_order = (int)($_POST['sort_order'] ?? 0);

    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($name !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO packages (name, description, price, sort_order, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'sssii', $name, $description, $price, $sort_order, $is_active);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: packages.php');
    exit();
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? $_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');

    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0 && $name !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE packages SET name=?, description=?, price=?, sort_order=?, is_active=? WHERE id=?'
        );
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss s ii i', $name, $description, $price, $sort_order, $is_active, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: packages.php');
    exit();
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM packages WHERE id=?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: packages.php');
    exit();
}

$packages = [];
$res = mysqli_query($conn, 'SELECT id, name, description, price, sort_order, is_active, created_at FROM packages ORDER BY sort_order ASC, id DESC');
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $packages[] = $row;
    }
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editPackage = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM packages WHERE id=?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $editPackage = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Packages</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0">Admin Panel - Packages</h2>
    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>
    </div>

  </div>

  <?php if ($editPackage): ?>
    <div class="alert alert-info">Editing package #<?php echo (int)$editPackage['id']; ?></div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3"><?php echo $editPackage ? 'Edit Package' : 'Create Package'; ?></h5>

      <form method="POST" action="packages.php">
        <input type="hidden" name="action" value="<?php echo $editPackage ? 'update' : 'create'; ?>" />
        <?php if ($editPackage): ?>
          <input type="hidden" name="id" value="<?php echo (int)$editPackage['id']; ?>" />
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input class="form-control" name="title" required value="<?php echo h($editPackage['name'] ?? $editPackage['title'] ?? ''); ?>" />

          </div>
          <div class="col-md-3">
            <label class="form-label">Price</label>
            <input class="form-control" name="price" value="<?php echo h($editPackage['price'] ?? ''); ?>" />
          </div>
          <div class="col-md-3">
            <label class="form-label">Sort Order</label>
            <input class="form-control" type="number" name="sort_order" value="<?php echo (int)($editPackage['sort_order'] ?? 0); ?>" />
          </div>

          <div class="col-12">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="description" rows="4"><?php echo h($editPackage['description'] ?? ''); ?></textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Active</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?php echo ((int)($editPackage['is_active'] ?? 1) === 1) ? 'checked' : ''; ?> />
              <label class="form-check-label" for="is_active">Show on public pages</label>
            </div>
          </div>

          <div class="col-md-9 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><?php echo $editPackage ? 'Save Changes' : 'Create Package'; ?></button>
          </div>

          <?php if ($editPackage): ?>
            <div class="col-12">
              <a class="btn btn-link text-decoration-none p-0" href="packages.php">Cancel Edit</a>
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
          <th>Price</th>
          <th>Sort</th>
          <th>Active</th>
          <th>Created</th>
          <th style="width:220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($packages) === 0): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No packages found.</td></tr>
        <?php else: ?>
          <?php foreach ($packages as $p): ?>
            <tr>
              <td><?php echo (int)$p['id']; ?></td>
              <td><?php echo h($p['name'] ?? $p['title'] ?? ''); ?></td>

              <td><?php echo h($p['price'] ?? ''); ?></td>
              <td><?php echo (int)($p['sort_order'] ?? 0); ?></td>
              <td>
                <span class="badge <?php echo ((int)($p['is_active'] ?? 0) === 1) ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                  <?php echo ((int)($p['is_active'] ?? 0) === 1) ? 'Active' : 'Inactive'; ?>
                </span>
              </td>
              <td><?php echo h($p['created_at'] ?? ''); ?></td>
              <td>
                <div class="d-flex gap-2 flex-wrap">
                  <a class="btn btn-sm btn-outline-primary" href="packages.php?edit=<?php echo (int)$p['id']; ?>">Edit</a>
                  <form method="POST" action="packages.php" onsubmit="return confirm('Delete this package?');">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>" />
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

