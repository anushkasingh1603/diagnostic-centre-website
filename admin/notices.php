<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/admin_auth.php';
require_once __DIR__ . '/../config/db.php';

// Handle enable/disable + create/update
$action = $_POST['action'] ?? $_GET['action'] ?? '';

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

if ($action === 'toggle_active') {
    $id = (int)($_POST['id'] ?? 0);
    $newActive = (int)($_POST['is_active'] ?? 0);
    $newActive = $newActive ? 1 : 0;

    $stmt = mysqli_prepare($conn, 'UPDATE notices SET is_active=? WHERE id=?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'ii', $newActive, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: notices.php');
    exit();
}

if ($action === 'create') {
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $display_from = $_POST['display_from'] ?? null;
    $display_to = $_POST['display_to'] ?? null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($title !== '' && $message !== '') {
        $stmt = mysqli_prepare(
            $conn,
            'INSERT INTO notices (title, message, display_from, display_to, is_active, sort_order) VALUES (?,?,?,?,?,?)'
        );
        if ($stmt) {
            $df = $display_from && $display_from !== '' ? $display_from : null;
            $dt = $display_to && $display_to !== '' ? $display_to : null;
            mysqli_stmt_bind_param($stmt, 'ssssis', $title, $message, $df, $dt, $is_active, $sort_order);
            // Note: bind_param signature expects exact types; use mixed approach below.
            // To keep it simple and robust, re-bind with correct types:
            mysqli_stmt_close($stmt);
        }

        // Robust insert without relying on complex bind types
        $dfSql = ($display_from && $display_from !== '') ? "'" . mysqli_real_escape_string($conn, $display_from) . "'" : 'NULL';
        $dtSql = ($display_to && $display_to !== '') ? "'" . mysqli_real_escape_string($conn, $display_to) . "'" : 'NULL';
        $titleSql = mysqli_real_escape_string($conn, $title);
        $msgSql = mysqli_real_escape_string($conn, $message);

        mysqli_query($conn, "INSERT INTO notices (title, message, display_from, display_to, is_active, sort_order) VALUES ('{$titleSql}','{$msgSql}',{$dfSql},{$dtSql},{$is_active},{$sort_order})");
    }

    header('Location: notices.php');
    exit();
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $display_from = $_POST['display_from'] ?? null;
    $display_to = $_POST['display_to'] ?? null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id > 0 && $title !== '' && $message !== '') {
        $dfSql = ($display_from && $display_from !== '') ? "'" . mysqli_real_escape_string($conn, $display_from) . "'" : 'NULL';
        $dtSql = ($display_to && $display_to !== '') ? "'" . mysqli_real_escape_string($conn, $display_to) . "'" : 'NULL';
        $titleSql = mysqli_real_escape_string($conn, $title);
        $msgSql = mysqli_real_escape_string($conn, $message);

        mysqli_query(
            $conn,
            "UPDATE notices SET title='{$titleSql}', message='{$msgSql}', display_from={$dfSql}, display_to={$dtSql}, is_active={$is_active}, sort_order={$sort_order} WHERE id={$id}"
        );
    }

    header('Location: notices.php');
    exit();
}

// Fetch for listing
$notices = [];
$res = mysqli_query($conn, 'SELECT * FROM notices ORDER BY sort_order ASC, id DESC');
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $notices[] = $row;
    }
}

$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$editNotice = null;
if ($editId > 0) {
    $stmt = mysqli_prepare($conn, 'SELECT * FROM notices WHERE id=?');
    mysqli_stmt_bind_param($stmt, 'i', $editId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editNotice = $result ? mysqli_fetch_assoc($result) : null;
    if ($stmt) mysqli_stmt_close($stmt);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Notices</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/admin_nav.php'; ?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0">Admin Panel - Notices</h2>

    </div>

    <div class="mb-4 text-muted">
        Welcome, <strong><?php echo h($_SESSION['fullname'] ?? 'Admin'); ?></strong>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Notices List</h4>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Active</th>
                        <th>Display</th>
                        <th>Sort</th>
                        <th style="width: 220px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($notices) === 0): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No notices found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($notices as $n): ?>
                            <tr>
                                <td><?php echo (int)$n['id']; ?></td>
                                <td><?php echo h($n['title']); ?></td>
                                <td>
                                    <span class="badge <?php echo ((int)$n['is_active'] === 1) ? 'text-bg-success' : 'text-bg-secondary'; ?>">
                                        <?php echo ((int)$n['is_active'] === 1) ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    <?php
                                        $df = $n['display_from'] ?? '';
                                        $dt = $n['display_to'] ?? '';
                                        echo h($df) . ' → ' . h($dt);
                                    ?>
                                </td>
                                <td><?php echo (int)$n['sort_order']; ?></td>
                                <td>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a class="btn btn-sm btn-outline-primary" href="notices.php?edit=<?php echo (int)$n['id']; ?>">Edit</a>
                                        <form method="POST" action="notices.php" onsubmit="return confirm('Toggle active status?');">
                                            <input type="hidden" name="action" value="toggle_active" />
                                            <input type="hidden" name="id" value="<?php echo (int)$n['id']; ?>" />
                                            <input type="hidden" name="is_active" value="<?php echo ((int)$n['is_active'] === 1) ? 0 : 1; ?>" />
                                            <button type="submit" class="btn btn-sm btn-<?php echo ((int)$n['is_active'] === 1) ? 'secondary' : 'success'; ?>">
                                                <?php echo ((int)$n['is_active'] === 1) ? 'Disable' : 'Enable'; ?>
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
                    <?php if ($editNotice): ?>
                        <h5 class="mb-0">Edit Notice #<?php echo (int)$editNotice['id']; ?></h5>
                    <?php else: ?>
                        <h5 class="mb-0">Create New Notice</h5>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form method="POST" action="notices.php">
                        <input type="hidden" name="action" value="<?php echo $editNotice ? 'update' : 'create'; ?>" />
                        <?php if ($editNotice): ?>
                            <input type="hidden" name="id" value="<?php echo (int)$editNotice['id']; ?>" />
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input class="form-control" name="title" required value="<?php echo h($editNotice['title'] ?? ''); ?>" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="4" required><?php echo h($editNotice['message'] ?? ''); ?></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Display From</label>
                                <input class="form-control" type="date" name="display_from" value="<?php echo h($editNotice['display_from'] ?? ''); ?>" />
                            </div>
                            <div class="col-6">
                                <label class="form-label">Display To</label>
                                <input class="form-control" type="date" name="display_to" value="<?php echo h($editNotice['display_to'] ?? ''); ?>" />
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label">Sort Order</label>
                                <input class="form-control" type="number" name="sort_order" value="<?php echo (int)($editNotice['sort_order'] ?? 0); ?>" />
                            </div>
                            <div class="col-6 d-flex align-items-end">
                                <div>
                                    <label class="form-label">Active</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?php echo ((int)($editNotice['is_active'] ?? 1) === 1) ? 'checked' : ''; ?> />
                                        <label class="form-check-label" for="is_active">Show on homepage</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><?php echo $editNotice ? 'Save Changes' : 'Create Notice'; ?></button>
                        </div>

                        <?php if ($editNotice): ?>
                            <div class="mt-2">
                                <a class="btn btn-link text-decoration-none p-0" href="notices.php">Cancel Edit</a>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <div class="mt-4 small text-muted">
                * Notices are displayed on the homepage when <strong>is_active=1</strong> and the current date is within the display range (if set).
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

