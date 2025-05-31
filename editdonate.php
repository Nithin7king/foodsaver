<?php
// editdonate.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}
$username = $_SESSION['username'];

// connect to donate DB
$conn = mysqli_connect('localhost', 'root', '', 'donate');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// fetch existing record
$query = "SELECT * FROM food WHERE username='$username'";
$result = mysqli_query($conn, $query);
$foodData = mysqli_fetch_assoc($result);
if (!$foodData) {
    header("Location: donate.php");
    exit();
}

// handle update
if (isset($_POST['update'])) {
    $companyName = mysqli_real_escape_string($conn, $_POST['companyName']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $quantity = (int)$_POST['quantity'];
    $price    = (float)$_POST['price'];

    // optional image upload
    $imageUpdate = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetDir . $image);
        $imageUpdate = ", image='$image'";
    }

    $sql = "
      UPDATE food SET
        companyName='$companyName',
        PhoneNumber='$phoneNumber',
        Location='$location',
        Quantity=$quantity,
        Price=$price
        $imageUpdate
      WHERE username='$username'
    ";

    if (mysqli_query($conn, $sql)) {
        header("Location: donate.php");
        exit();
    } else {
        $error = mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Food Donation Details</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
    rel="stylesheet"
  >
  <style>
    body { font-family:'Poppins',sans-serif; background:#f0f2f5; padding:40px; }
    .container {
      max-width:400px; margin:auto; background:#fff; padding:20px;
      border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);
      text-align:center;
    }
    .container h2 { margin-bottom:20px; color:#4caf50; }
    .container input, .container button {
      width:100%; padding:10px; margin:10px 0;
      border:1px solid #ddd; border-radius:5px;
    }
    .container button {
      background:#4caf50; color:#fff; border:none; cursor:pointer;
    }
    .container button:hover { background:#45a049; }
    .error { color:red; margin-top:10px; }
  </style>
</head>
<body>

  <div class="container">
    <h2>Edit Your Donation Details</h2>
    <form method="POST" enctype="multipart/form-data">
      <input
        type="text" name="companyName"
        value="<?= htmlspecialchars($foodData['companyName']) ?>"
        required
      >
      <input
        type="text" name="phoneNumber"
        value="<?= htmlspecialchars($foodData['PhoneNumber']) ?>"
        required
      >
      <input
        type="text" name="location"
        value="<?= htmlspecialchars($foodData['Location']) ?>"
        required
      >
      <input
        type="number" name="quantity"
        value="<?= (int)$foodData['Quantity'] ?>"
        required
      >
      <input
        type="number" step="0.01" name="price"
        value="<?= number_format($foodData['Price'],2) ?>"
        required
      >
      <input type="file" name="image" accept="image/*">
      <button type="submit" name="update">Update</button>
    </form>
    <?php if (!empty($error)): ?>
      <div class="error">❌ Error: <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </div>

</body>
</html>
