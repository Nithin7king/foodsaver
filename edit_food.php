<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$conn = mysqli_connect('localhost','root','','companydetails');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION['username'];
$companyResult = mysqli_query($conn, "SELECT * FROM food WHERE username='$username'");
$company = mysqli_fetch_assoc($companyResult);

if (!$company) {
    header("Location: food.php");
    exit();
}
$companyId = $company['sno'];

// Add new dish
if (isset($_POST['addDish'])) {
    $dishName = mysqli_real_escape_string($conn, $_POST['dishName']);
    $quantity = (int)$_POST['quantity'];
    $price    = (float)$_POST['price'];

    // Handle datetime-local → YYYY-MM-DD HH:MM:SS
    if (!empty($_POST['expiry_time'])) {
        $raw = $_POST['expiry_time'];               // e.g. "2025-05-02T14:30"
        $dt  = str_replace('T', ' ', $raw) . ':00'; // "2025-05-02 14:30:00"
        $expiryVal = "'" . mysqli_real_escape_string($conn, $dt) . "'";
    } else {
        $expiryVal = "NULL";
    }

    // Handle image upload
    if (!empty($_FILES['image']['tmp_name'])) {
        $d = 'uploads/';
        if (!is_dir($d)) mkdir($d, 0755, true);
        $imgName = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "{$d}{$imgName}");
        $imgVal = "'" . mysqli_real_escape_string($conn, $imgName) . "'";
    } else {
        $imgVal = "''";
    }

    $sql = "
      INSERT INTO dish
        (company_id, dishName, Quantity, Price, image, expiry_time)
      VALUES
        ($companyId, '$dishName', $quantity, $price, $imgVal, $expiryVal)
    ";
    mysqli_query($conn, $sql);

    // Redirect to food.php after successful insert
    header("Location: food.php");
    exit();
}

// Fetch existing dishes (unused here, but kept if you want to list them)
$dishes = mysqli_query($conn, "SELECT * FROM dish WHERE company_id=$companyId");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Dishes</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
  body { font-family:'Poppins',sans-serif; background:#f0f2f5; padding:20px; }
  .container { max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px; }
  h2 { color:#4caf50; }
  form { margin-bottom:30px; }
  input, button { width:100%; padding:10px; margin:8px 0; border-radius:5px; border:1px solid #ddd; box-sizing:border-box; }
  button { background:#4caf50; color:#fff; border:none; cursor:pointer; }
  .dish { background:#fff; padding:15px; border-radius:8px; margin-bottom:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1); text-align:left; }
  .dish img { max-width:200px; display:block; margin-top:10px; border-radius:5px; }
</style>
</head>
<body>
  <div class="container">
    <h2>Manage Dishes for <?= htmlspecialchars($company['companyName']) ?></h2>

    <h3>Add New Dish</h3>
    <form method="POST" enctype="multipart/form-data">
      <input type="text"     name="dishName"    placeholder="Dish Name" required>
      <input type="number"   name="quantity"    placeholder="Quantity (kg)" required>
      <input type="number"   name="price"       placeholder="Price (₹)" required>
      <input type="datetime-local" name="expiry_time" required>
      <input type="file"     name="image"       accept="image/*">
      <button type="submit" name="addDish">Add Dish</button>
    </form>

    <!-- Optional: list your current dishes here, if you still need -->
    <h3>Your Dishes</h3>
    <?php while ($dish = mysqli_fetch_assoc($dishes)): ?>
      <div class="dish">
        <strong><?= htmlspecialchars($dish['dishName']) ?></strong><br>
        📦 <?= $dish['Quantity'] ?> kg<br>
        💰 ₹<?= $dish['Price'] ?><br>
        <?php if ($dish['expiry_time']): ?>
          ⏳ Expires: <?= date('d M Y, H:i', strtotime($dish['expiry_time'])) ?><br>
        <?php endif; ?>
        <?php if (!empty($dish['image'])): ?>
          <img src="uploads/<?= htmlspecialchars($dish['image']) ?>" alt="Dish image">
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
</body>
</html>