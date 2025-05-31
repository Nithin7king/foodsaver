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

$searchTerm = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, trim($_GET['search']))
    : '';

if ($searchTerm) {
    $sql = "SELECT d.*, f.companyName, f.Location, f.PhoneNumber, f.sno as company_id " .
           "FROM dish d " .
           "JOIN food f ON d.company_id = f.sno " .
           "WHERE f.companyName LIKE '%$searchTerm%' OR d.dishName LIKE '%$searchTerm%'";
} else {
    $sql = "SELECT d.*, f.companyName, f.Location, f.PhoneNumber, f.sno as company_id " .
           "FROM dish d " .
           "JOIN food f ON d.company_id = f.sno";
}

$result = mysqli_query($conn, $sql);

$check = mysqli_query($conn, "SELECT sno FROM food WHERE username='$username'");
$userCompany = mysqli_fetch_assoc($check);
$userHasCompany = (bool)$userCompany;
$userCompanyId = $userCompany['sno'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Marketplace</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body { 
      font-family:'Poppins',sans-serif; 
      background:#f0f2f5; 
      padding:0; 
      margin:0; 
      position: relative;
    }
    
    /* Navbar styles */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 0 20px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      height: 70px;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    /* Added flex layout for center section */
    .navbar-center {
      display: flex;
      align-items: center;
      gap: 30px; /* Controls spacing between elements */
    }
    
    .logo {
      display: flex;
      align-items: center;
    }
    
    .logo-img {
      width: 40px;
      height: 40px;
      margin-right: 10px;
      border-radius: 5px;
    }
    
    .logo span {
      font-size: 18px;
      font-weight: bold;
      color: #4caf50;
    }
    
    .page-title {
      font-size: 22px;
      font-weight: bold;
      color: #4caf50;
    }
    
    .user-profile {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .profile-container {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .profile-img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }
    
    /* Navigation link style like in main.php */
    .back-home {
      color: #333;
      text-decoration: none;
      padding: 10px 15px;
      font-weight: 500;
      position: relative;
      transition: color 0.3s;
    }
    
    .back-home:hover {
      color: #4caf50;
    }
    
    .back-home:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 15%;
      width: 70%;
      height: 2px;
      background-color: #4caf50;
      transform: scaleX(0);
      transition: transform 0.3s;
    }
    
    .back-home:hover:after {
      transform: scaleX(1);
    }
    
    /* Button styles for other buttons */
    .manage-btn, .register-btn {
      display: inline-block;
      padding: 8px 20px;
      background-color: #4caf50;
      color: white;
      border-radius: 4px;
      text-decoration: none;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
      font-size: 14px;
    }
    
    .manage-btn:hover, .register-btn:hover {
      background-color: #45a049;
    }
    
    /* Register button positioning outside navbar */
    .register-btn-container {
      position: absolute;
      top: 85px;
      right: 20px;
      z-index: 900;
    }
    
    .logout-btn {
      color: #555;
      text-decoration: none;
      margin-left: 5px;
      font-size: 18px;
    }
    
    /* Content styles */
    .main-content {
      padding: 20px;
      margin-top: 15px;
    }
    
    .search-bar { 
      text-align:center; 
      margin: 20px auto; 
      max-width: 600px;
    }
    
    .search-bar input { 
      width: 70%; 
      padding:8px; 
      border-radius:5px; 
      border:1px solid #ccc; 
    }
    
    .search-bar button { 
      padding:8px 12px; 
      border:none; 
      background:#4caf50; 
      color:#fff; 
      border-radius:4px;
      cursor:pointer; 
    }
    
    .card-container { 
      display:flex; 
      flex-wrap:wrap; 
      gap:20px; 
      justify-content:center; 
      margin-top: 20px;
    }
    
    .card {
      width:300px; 
      background:#fff; 
      padding:20px; 
      border-radius:10px;
      box-shadow:0 4px 10px rgba(0,0,0,0.1); 
      position:relative;
    }
    
    .card img { 
      width:100%; 
      height:180px; 
      object-fit:cover; 
      border-radius:8px; 
      margin-bottom:10px; 
    }
    
    .card h3 { 
      margin:8px 0; 
    }
    
    .card div { 
      margin:4px 0; 
    }
    
    .edit-btn {
      position:absolute; 
      top:15px; 
      right:15px;
      background:#f39c12; 
      padding:6px 10px; 
      border-radius:5px;
      color:#fff; 
      text-decoration:none;
    }
    
    .review-link {
      display: inline-block;
      margin-top: 10px;
      color: #4caf50;
      text-decoration: none;
      font-weight: bold;
    }
    
    .review-link:hover {
      text-decoration: underline;
    }
    
    /* Modal overlay styles */
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(5px);
      z-index: 1001;
      justify-content: center;
      align-items: center;
    }
    
    /* Registration form */
    #register {
      width: 400px;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    
    #register h2 {
      margin-top: 0;
      color: #4caf50;
    }
    
    #register input {
      width: 100%;
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-sizing: border-box;
    }
    
    #register button {
      padding: 10px;
      background: #4caf50;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }
    
    #cancelRegister {
      background: #f44336 !important;
    }
    /* Removed unnecessary margin style */
  </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
  <div class="logo">
    <img src="images/logo.jpg" alt="Food Saver Logo" class="logo-img" onerror="this.src='https://via.placeholder.com/40x40'; this.onerror=null;">
    <span>Food Saver</span>
  </div>
  
  <div class="navbar-center">
    <div class="page-title">Food Marketplace</div>
  </div>
  <div class="user-profile">
    <a class="back-home" href="main.php"><-Home</a>
    <?php if ($userHasCompany): ?>
      <a href="edit_food.php" class="manage-btn">Manage My Dishes</a>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['username'])): ?>
      <div class="profile-container">
        <img src="images/icon.jpg" alt="Profile" class="profile-img" onerror="this.src='https://via.placeholder.com/40x40'; this.onerror=null;">
        <span><?= htmlspecialchars($_SESSION['username']) ?></span>
        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
      </div>
    <?php endif; ?>
  </div>
</nav>

<!-- Register button moved outside navbar -->
<?php if (!$userHasCompany): ?>
<div class="register-btn-container">
  <button id="showRegister" class="register-btn">Register Company</button>
</div>
<?php endif; ?>

<div class="main-content">
  <div class="search-bar">
    <form method="GET">
      <input type="text" name="search" placeholder="Search companies or dishes..." value="<?= htmlspecialchars($searchTerm) ?>">
      <button type="submit">Search</button>
    </form>
  </div>
  
  <div class="card-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <?php $loc = urlencode($row['Location']); ?>
      <div class="card">
        <?php if ($row['image']): ?>
          <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['dishName']) ?></h3>
        <div><strong>Company:</strong> <?= htmlspecialchars($row['companyName']) ?></div>
        <div>📍 <?= htmlspecialchars($row['Location']) ?></div>
        <div>📞 <?= htmlspecialchars($row['PhoneNumber']) ?></div>
        <div>📦 <?= $row['Quantity'] ?></div>
        <div>💰 ₹<?= $row['Price'] ?></div>
        <div>⏳ Expires: <?= $row['expiry_time'] ? date('d M Y, H:i', strtotime($row['expiry_time'])) : 'N/A' ?></div>
        <a href="https://www.google.com/maps/search/?api=1&query=<?= $loc ?>" target="_blank">🗺 View on Map</a>
        <a href="review.php?dish_id=<?= $row['id'] ?>" class="review-link">View Reviews</a>
        <?php if ($userHasCompany && $row['company_id'] == $userCompanyId): ?>
          <a href="edit_dish.php?id=<?= $row['id'] ?>" class="edit-btn">✏ Edit</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
  
  <?php if (!$userHasCompany): ?>
    <div id="modalOverlay" class="modal-overlay">
      <div id="register">
        <h2>Register Your Company</h2>
        <form method="POST" enctype="multipart/form-data">
          <input type="text" name="companyName" placeholder="Company Name" required>
          <input type="text" name="phoneNumber" placeholder="Phone Number" required>
          <input type="text" name="location" placeholder="Location" required>
          <input type="number" name="quantity" placeholder="Quantity (kg)" required>
          <input type="number" name="price" placeholder="Price (₹)" required>
          <input type="file" name="image" accept="image/*" required>
          <div style="display:flex; gap:10px; margin-top:10px;">
            <button type="submit" name="submit" style="flex:1;">Register</button>
            <button type="button" id="cancelRegister" style="flex:1; background:#f44336;">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  document.getElementById('showRegister').addEventListener('click', function(){
    document.getElementById('modalOverlay').style.display = 'flex';
  });
  
  document.getElementById('cancelRegister').addEventListener('click', function(){
    document.getElementById('modalOverlay').style.display = 'none';
  });
  
  // Close modal when clicking outside the form
  document.getElementById('modalOverlay').addEventListener('click', function(event) {
    if (event.target === this) {
      this.style.display = 'none';
    }
  });
</script>

<?php mysqli_close($conn); ?>
</body>
</html>