<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

// Toggle active
if ($action === 'toggle_active') {
    $id = (int)($_POST['id'] ?? 0);
    $newActive = (int)($_POST['is_active'] ?? 0);
    $newActive = $newActive ? 1 : 0;

    $stmt = mysqli_prepare($conn, 'UPDATE doctors SET is_active=? WHERE id=?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $newActive, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: doctors.php');
    exit();
}

// Create/Update
if ($action === 'create' || $action === 'update') {
    $isUpdate = $action === 'update';
    $id = $isUpdate ? (int)($_POST['id'] ?? 0) : 0;

    $name = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // image_path comes from form (no upload processing in this placeholder)
    $image_path = trim($_POST['image_path'] ?? '');
    if ($image_path === '') $image_path = null;

    if ($name !== '' && $specialization !== '') {
        $nameSql = mysqli_real_escape_string($conn, $name);
        $specSql = mysqli_real_escape_string($conn, $specialization);
        $bioSql = mysqli_real_escape_string($conn, $bio);
        $tagsSql = mysqli_real_escape_string($conn, $tags);
        $imgSql = $image_path !== null ? "'" . mysqli_real_escape_string($conn, $image_path) . "'" : 'NULL';

        if ($isUpdate && $id > 0) {
            mysqli_query(
                $conn,
                "UPDATE doctors SET name='{$nameSql}', specialization='{$specSql}', bio='{$bioSql}', image_path={$imgSql}, tags='{$tagsSql}', is_active={$is_active}, sort_order={$sort_order} WHERE id={$id}"
            );
        } else {
            mysqli_query(
                $conn,
                "INSERT INTO doctors (name, specialization, bio, image_path, tags, is_active, sort_order) VALUES ('{$nameSql}','{$specSql}','{$bioSql}',{$imgSql},'{$tagsSql}',{$is_active},{$sort_order})"
            );
        }
    }

    header('Location: doctors.php');
    exit();
}

// List
$doctors = [];
$res = mysqli_query($conn, 'SELECT * FROM doctors ORDER BY sort_order ASC, id DESC');
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $doctors[] = $row;
    }
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editDoctor = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM doctors WHERE id=?');
    mysqli_stmt_bind_param($stmt, 'i', $editId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editDoctor = $result ? mysqli_fetch_assoc($result) : null;
    if ($stmt) mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Doctors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0">Admin Panel - Doctors</h2>

    </div>

    <div class="mb-4 text-muted">
        Welcome, <strong><?php echo h($_SESSION['fullname'] ?? 'Admin'); ?></strong>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Doctors List</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Active</th>
                        <th>Sort</th>
                        <th style="width: 220px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($doctors) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No doctors found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($doctors as $d): ?>
                            <tr>
                                <td><?php echo (int)$d['id']; ?></td>
                                <td><?php echo h($d['name']); ?></td>
                                <td class="small text-muted"><?php echo h($d['specialization']); ?></td>
                                <td>
                                    <span class="badge <?php echo ((int)$d['is_active'] === 1) ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                                        <?php echo ((int)$d['is_active'] === 1) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo (int)$d['sort_order']; ?></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a class="btn btn-sm btn-outline-primary" href="doctors.php?edit=<?php echo (int)$d['id']; ?>">Edit</a>
                                        <form method="POST" action="doctors.php" onsubmit="return confirm('Toggle active status?');">
                                            <input type="hidden" name="action" value="toggle_active" />
                                            <input type="hidden" name="id" value="<?php echo (int)$d['id']; ?>" />
                                            <input type="hidden" name="is_active" value="<?php echo ((int)$d['is_active'] === 1) ? 0 : 1; ?>" />
                                            <button type="submit" class="btn btn-sm btn-<?php echo ((int)$d['is_active'] === 1) ? 'secondary' : 'success'; ?>">
                                                <?php echo ((int)$d['is_active'] === 1) ? 'Disable' : 'Enable'; ?>
                                            </button>
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

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <?php if ($editDoctor): ?>
                        <h5 class="mb-0">Edit Doctor #<?php echo (int)$editDoctor['id']; ?></h5>
                    <?php else: ?>
                        <h5 class="mb-0">Create New Doctor</h5>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="doctors.php">
                        <input type="hidden" name="action" value="<?php echo $editDoctor ? 'update' : 'create'; ?>" />
                        <?php if ($editDoctor): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editDoctor['id']; ?>" />
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Doctor Name</label>
                            <input class="form-control" name="name" required value="<?php echo h($editDoctor['name'] ?? ''); ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Specialization</label>
                            <input class="form-control" name="specialization" required value="<?php echo h($editDoctor['specialization'] ?? ''); ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <textarea class="form-control" name="bio" rows="3"><?php echo h($editDoctor['bio'] ?? ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Image Path (URL or local path)</label>
                            <input class="form-control" name="image_path" placeholder="assets/images/doctor.jpg" value="<?php echo h($editDoctor['image_path'] ?? ''); ?>" />
                            <div class="form-text">Example: <code>assets/images/xray.jpg</code> or a full URL.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tags (comma-separated)</label>
                            <input class="form-control" name="tags" value="<?php echo h($editDoctor['tags'] ?? ''); ?>" />
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Sort Order</label>
                                <input class="form-control" type="number" name="sort_order" value="<?php echo (int)($editDoctor['sort_order'] ?? 0); ?>" />
                            </div>
                            <div class="col-6 d-flex align-items-end">
                                <div>
                                    <label class="form-label">Active</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?php echo ((int)($editDoctor['is_active'] ?? 1) === 1) ? 'checked' : ''; ?> />
                                        <label class="form-check-label" for="is_active">Show on homepage</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><?php echo $editDoctor ? 'Save Changes' : 'Create Doctor'; ?></button>
                        </div>

                        <?php if ($editDoctor): ?>
                            <div class="mt-2">
                                <a class="btn btn-link text-decoration-none p-0" href="doctors.php">Cancel Edit</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="mt-4 small text-muted">
                * Doctors are shown when <strong>is_active=1</strong>.
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

