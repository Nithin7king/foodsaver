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

// Get and validate dish ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$sql = "
    SELECT d.*, f.username, f.companyName
      FROM dish d
      JOIN food f ON d.company_id = f.sno
     WHERE d.id = $id
";
$res = mysqli_query($conn, $sql);
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: food.php");
    exit();
}
$dish = mysqli_fetch_assoc($res);

// Ensure this dish belongs to the logged‐in user
if ($dish['username'] !== $username) {
    header("Location: food.php");
    exit();
}

// Handle update submission
if (isset($_POST['update'])) {
    $dishName = mysqli_real_escape_string($conn, $_POST['dishName']);
    $quantity = (int)$_POST['quantity'];
    $price    = (float)$_POST['price'];
    $expiryTime = mysqli_real_escape_string($conn, $_POST['expiry_time']);
    $imageUpdate = '';

    if (!empty($_FILES['image']['tmp_name'])) {
        $targetDir = 'uploads/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $image = basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
        $imageUpdate = ", image='$image'";
    }

    $updateSql = "
        UPDATE dish
           SET dishName = '$dishName',
               Quantity = $quantity,
               Price    = $price,
               expiry_time = '$expiryTime'
               $imageUpdate
         WHERE id = $id
    ";
    mysqli_query($conn, $updateSql);
    header("Location: food.php");
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Dish – <?= htmlspecialchars($dish['dishName']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body { font-family:'Poppins',sans-serif; background:#f0f2f5; padding:20px; text-align:center; }
    .container { max-width:400px; margin:50px auto; background:#fff; padding:20px;
                 border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    input, button { width:100%; padding:10px; margin:10px 0; border:1px solid #ddd; border-radius:5px; }
    button { background:#4caf50; color:#fff; border:none; cursor:pointer; }
    button:hover { background:#45a049; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Dish for <?= htmlspecialchars($dish['companyName']) ?></h2>
    <form method="POST" enctype="multipart/form-data">
      <input type="text" name="dishName" value="<?= htmlspecialchars($dish['dishName']) ?>" required>
      <input type="number" name="quantity" value="<?= $dish['Quantity'] ?>" required>
      <input type="number" step="0.01" name="price" value="<?= $dish['Price'] ?>" required>
      <input type="datetime-local" name="expiry_time" value="<?= date('Y-m-d\TH:i', strtotime($dish['expiry_time'])) ?>" required>
      <input type="file" name="image" accept="image/*">
      <button type="submit" name="update">Update Dish</button>
    </form>
  </div>
</body>
</html>
