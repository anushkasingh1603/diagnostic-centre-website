<?php
include("config/db.php");

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$message = "";
$messageType = "success";

if (isset($_POST['submit_feedback'])) {
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $msg    = trim($_POST['message'] ?? '');

    if ($name === '' || $msg === '') {
        $message = "Please enter your name and your feedback.";
        $messageType = "danger";
    } elseif ($rating < 1 || $rating > 5) {
        $message = "Please select a star rating.";
        $messageType = "danger";
    } else {
        // show_on_homepage defaults to 0 (admin approves later)
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO feedback (name, email, rating, message, show_on_homepage) VALUES (?, ?, ?, ?, 0)"
        );
        if (!$stmt) {
            $message = "Database error. Please try again.";
            $messageType = "danger";
        } else {
            mysqli_stmt_bind_param($stmt, 'ssis', $name, $email, $rating, $msg);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Thank you! Your feedback has been submitted.";
                $messageType = "success";
            } else {
                $message = "Could not submit feedback. Please try again.";
                $messageType = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Share Your Feedback | Burdwan Scan Centre</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<style>
  body { background:#f1f5f9; }
  .fb-card { max-width:640px; margin:40px auto; background:#fff; border-radius:18px; box-shadow:0 14px 36px rgba(15,23,42,.10); overflow:hidden; }
  .fb-header { background:linear-gradient(90deg,#1d4ed8,#22d3ee); color:#fff; padding:22px; text-align:center; }
  .fb-body { padding:30px; }
  .star-rating { direction:rtl; display:inline-flex; gap:6px; font-size:2rem; }
  .star-rating input { display:none; }
  .star-rating label { color:#d1d5db; cursor:pointer; transition:color .15s; }
  .star-rating input:checked ~ label,
  .star-rating label:hover,
  .star-rating label:hover ~ label { color:#f59e0b; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white shadow-sm py-3">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php"><i class="fa-solid fa-stethoscope me-2"></i>Burdwan Scan Centre</a>
    <a href="index.php" class="btn btn-outline-primary btn-sm rounded-pill"><i class="fa-solid fa-arrow-left me-1"></i>Back to Home</a>
  </div>
</nav>

<div class="fb-card">
  <div class="fb-header">
    <h3 class="mb-0"><i class="fa-solid fa-comment-dots me-2"></i>Share Your Feedback</h3>
  </div>
  <div class="fb-body">

    <?php if ($message !== ""): ?>
      <div class="alert alert-<?php echo h($messageType); ?>"><?php echo h($message); ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email (optional)</label>
        <input type="email" name="email" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label d-block">Your Rating</label>
        <div class="star-rating">
          <input type="radio" name="rating" id="star5" value="5"><label for="star5"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star4" value="4"><label for="star4"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star3" value="3"><label for="star3"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star2" value="2"><label for="star2"><i class="fa-solid fa-star"></i></label>
          <input type="radio" name="rating" id="star1" value="1"><label for="star1"><i class="fa-solid fa-star"></i></label>
        </div>
      </div>

      <div class="mb-4">
        <label class="form-label">Your Feedback</label>
        <textarea name="message" class="form-control" rows="4" required></textarea>
      </div>

      <button type="submit" name="submit_feedback" class="btn btn-primary w-100 rounded-pill py-2">
        <i class="fa-solid fa-paper-plane me-2"></i>Submit Feedback
      </button>
    </form>
  </div>
</div>

</body>
</html>

