<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$username = $_SESSION['username'];
$conn = mysqli_connect('localhost', 'root', '', 'companydetails');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$dish_id = isset($_GET['dish_id']) ? (int)$_GET['dish_id'] : 0;
$dish = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM dish WHERE id=$dish_id"));
if (!$dish) {
    header("Location: food.php");
    exit();
}

// Check if user already reviewed this dish
$alreadyReviewed = false;
$check = mysqli_query($conn, "SELECT id FROM reviews WHERE dish_id=$dish_id AND username='$username'");
if (mysqli_num_rows($check) > 0) {
    $alreadyReviewed = true;
}

// Handle review submission
if (isset($_POST['submit']) && !$alreadyReviewed) {
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    if ($rating >= 1 && $rating <= 5) {
        mysqli_query($conn, "INSERT INTO reviews (dish_id, username, rating, comment) VALUES ($dish_id, '$username', $rating, '$comment')");
    }
    header("Location: review.php?dish_id=$dish_id");
    exit();
}

// Fetch all reviews for the dish
$reviews = mysqli_query($conn, "SELECT * FROM reviews WHERE dish_id=$dish_id ORDER BY id DESC");

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Review Dish</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Poppins',sans-serif; background:#f0f2f5; padding:20px; }
    .container { max-width:600px; margin:30px auto; background:#fff; padding:20px;
                 border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    input, textarea, button { width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px; }
    button { background:#4caf50; color:#fff; border:none; cursor:pointer; }
    button:hover { background:#45a049; }
    .error { color:red; margin-bottom:15px; text-align:center; }
    .reviews { margin-top:30px; }
    .review { background:#f9f9f9; padding:15px; margin-bottom:15px; border-radius:8px; }
    .review h4 { margin:0 0 5px; }
    .review .rating { color:#ffc107; }
    .back-link { display:inline-block; margin-top:20px; text-decoration:none; color:#4caf50; font-weight:bold; }
  </style>
</head>
<body>

<div class="container">
  <h2>Review: <?= htmlspecialchars($dish['dishName']) ?></h2>

  <?php if ($alreadyReviewed): ?>
    <div class="error">⚠️ You have already reviewed this dish.</div>
  <?php else: ?>
    <form method="POST">
      <input type="number" name="rating" min="1" max="5" placeholder="Rating (1-5)" required>
      <textarea name="comment" placeholder="Write your comment..." required></textarea>
      <button type="submit" name="submit">Submit Review</button>
    </form>
  <?php endif; ?>

  <div class="reviews">
    <h3>All Reviews</h3>
    <?php if (mysqli_num_rows($reviews) > 0): ?>
      <?php while ($r = mysqli_fetch_assoc($reviews)): ?>
        <div class="review">
          <h4><?= htmlspecialchars($r['username']) ?></h4>
          <div class="rating"><?= str_repeat('⭐', $r['rating']) ?> (<?= $r['rating'] ?>/5)</div>
          <p><?= htmlspecialchars($r['comment']) ?></p>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reviews yet.</p>
    <?php endif; ?>
  </div>

  <a href="food.php" class="back-link">← Back to Menu</a>
</div>

</body>
</html>
