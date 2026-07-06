<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

function h($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'create') {
    $name = trim($_POST['name'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $full_description = trim($_POST['full_description'] ?? '');
    $prep_instructions = trim($_POST['prep_instructions'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($name !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO services (name, short_description, full_description, prep_instructions, price, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, ?, NOW())'
        );
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                'sssssi',
                $name,
                $short_description,
                $full_description,
                $prep_instructions,
                $price,
                $is_active
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: services.php');
    exit();
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $short_description = trim($_POST['short_description'] ?? '');
    $full_description = trim($_POST['full_description'] ?? '');
    $prep_instructions = trim($_POST['prep_instructions'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0 && $name !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'UPDATE services
             SET name=?, short_description=?, full_description=?, prep_instructions=?, price=?, is_active=?
             WHERE id=?'
        );
        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                'ssssssi',
                $name,
                $short_description,
                $full_description,
                $prep_instructions,
                $price,
                $is_active,
                $id
            );
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    header('Location: services.php');
    exit();
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        $stmt = mysqli_prepare($conn, 'DELETE FROM services WHERE id=?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    header('Location: services.php');
    exit();
}

$services = [];
$res = mysqli_query($conn, 'SELECT id, name, short_description, full_description, prep_instructions, price, is_active, created_at FROM services ORDER BY created_at DESC, id DESC');
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $services[] = $row;
    }
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editService = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM services WHERE id=?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $editId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $editService = $result ? mysqli_fetch_assoc($result) : null;
        mysqli_stmt_close($stmt);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Services</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0">Admin Panel - Services</h2>
    <div class="d-flex gap-2">
      <a href="dashboard.php" class="btn btn-secondary btn-sm">Dashboard</a>

    </div>
  </div>

  <?php if ($editService): ?>
    <div class="alert alert-info">
      Editing service #<?php echo (int)$editService['id']; ?>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <h5 class="card-title mb-3"><?php echo $editService ? 'Edit Service' : 'Create Service'; ?></h5>
      <form method="POST" action="services.php">
        <input type="hidden" name="action" value="<?php echo $editService ? 'update' : 'create'; ?>" />
        <?php if ($editService): ?>
          <input type="hidden" name="id" value="<?php echo (int)$editService['id']; ?>" />
        <?php endif; ?>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" required value="<?php echo h($editService['name'] ?? ''); ?>" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Price</label>
            <input class="form-control" name="price" value="<?php echo h($editService['price'] ?? ''); ?>" />
          </div>

          <div class="col-md-12">
            <label class="form-label">Short Description</label>
            <textarea class="form-control" name="short_description" rows="2"><?php echo h($editService['short_description'] ?? ''); ?></textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label">Full Description</label>
            <textarea class="form-control" name="full_description" rows="3"><?php echo h($editService['full_description'] ?? ''); ?></textarea>
          </div>

          <div class="col-md-12">
            <label class="form-label">Preparation Instructions</label>
            <textarea class="form-control" name="prep_instructions" rows="3"><?php echo h($editService['prep_instructions'] ?? ''); ?></textarea>
          </div>

          <div class="col-md-3">
            <label class="form-label">Active</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?php echo ((int)($editService['is_active'] ?? 1) === 1) ? 'checked' : ''; ?> />
              <label class="form-check-label" for="is_active">Show on public pages</label>
            </div>
          </div>

          <div class="col-md-9 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100"><?php echo $editService ? 'Save Changes' : 'Create Service'; ?></button>
          </div>

          <?php if ($editService): ?>
            <div class="col-12">
              <a class="btn btn-link text-decoration-none p-0" href="services.php">Cancel Edit</a>
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
          <th>Name</th>
          <th>Price</th>
          <th>Active</th>
          <th>Created</th>
          <th style="width:220px;">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php if (count($services) === 0): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No services found.</td></tr>
      <?php else: ?>
        <?php foreach ($services as $s): ?>
          <tr>
            <td><?php echo (int)$s['id']; ?></td>
            <td><?php echo h($s['name']); ?></td>
            <td><?php echo h($s['price'] ?? ''); ?></td>
            <td>
              <span class="badge <?php echo ((int)($s['is_active'] ?? 0) === 1) ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                <?php echo ((int)($s['is_active'] ?? 0) === 1) ? 'Active' : 'Inactive'; ?>
              </span>
            </td>
            <td><?php echo h($s['created_at'] ?? ''); ?></td>
            <td>
              <div class="d-flex gap-2 flex-wrap">
                <a class="btn btn-sm btn-outline-primary" href="services.php?edit=<?php echo (int)$s['id']; ?>">Edit</a>
                <form method="POST" action="services.php" onsubmit="return confirm('Delete this service?');">
                  <input type="hidden" name="action" value="delete" />
                  <input type="hidden" name="id" value="<?php echo (int)$s['id']; ?>" />
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

