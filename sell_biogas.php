<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}
$username = $_SESSION['username'];

$conn = mysqli_connect('localhost','root','','companydetails');
if (!$conn) die("Connection failed: ".mysqli_connect_error());

// search
$searchTerm = $_GET['search'] ?? '';
$searchTerm = mysqli_real_escape_string($conn, trim($searchTerm));
$sql = $searchTerm
     ? "SELECT * FROM biogasplant WHERE companyName LIKE '%$searchTerm%'"
     : "SELECT * FROM biogasplant";
$result = mysqli_query($conn, $sql);

// check existing
$has = mysqli_query($conn, "SELECT 1 FROM biogasplant WHERE username='$username'");
$userHasCompany = mysqli_num_rows($has) > 0;

// handle register submission
if (isset($_POST['submit'])) {
    $c = mysqli_real_escape_string($conn, $_POST['companyName']);
    $p = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $l = mysqli_real_escape_string($conn, $_POST['location']);
    $q = (int)$_POST['quantity'];
    $r = (float)$_POST['price'];
    mysqli_query($conn,
      "INSERT INTO biogasplant
       (companyName,PhoneNumber,Location,Quantity,Price,username)
       VALUES
       ('$c','$p','$l',$q,$r,'$username')"
    );
    header("Location: sell_biogas.php");
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Biogas Companies</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f0f2f5;
      margin: 0; 
      padding: 0;
      position: relative;
    }
    
    h1 {
      text-align: center;
      color: #4caf50;
      margin-top: 20px;
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
    
    .navbar-center {
      display: flex;
      align-items: center;
      gap: 30px;
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
    
    .logout-btn {
      color: #555;
      text-decoration: none;
      margin-left: 5px;
      font-size: 18px;
    }
    
    /* Register button positioning outside navbar */
    .register-btn-container {
      position: absolute;
      top: 85px;
      right: 20px;
      z-index: 900;
    }
    
    .register-btn {
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
    
    .register-btn:hover {
      background-color: #45a049;
    }
    
    .main-content {
      padding: 20px;
      margin-top: 15px;
    }
    
    /* Search bar */
    .search-bar {
      text-align: center;
      margin: 20px auto;
      max-width: 600px;
    }
    
    .search-bar input {
      width: 70%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }
    
    .search-bar button {
      padding: 8px 12px;
      border: none;
      background: #4caf50;
      color: #fff;
      border-radius: 4px;
      cursor: pointer;
    }
    
    /* Company cards */
    .company-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }
    
    .company-box {
      width: 320px;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s;
    }
    
    .company-box:hover {
      transform: translateY(-5px);
    }
    
    .company-box h2 { margin-top: 0; font-size: 20px; }
    
    .company-box .detail {
      display: flex; 
      align-items: center;
      margin: 8px 0; 
      font-size: 14px;
    }
    
    .company-box .detail span { 
      margin-right: 8px; 
      color: #4caf50; 
    }
    
    .company-box .edit-btn {
      display: inline-block;
      margin-top: 12px;
      background: #f39c12;
      color: #fff;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 14px;
    }
    
    /* Modal overlay */
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
    <div class="page-title">Biogas Companies</div>
  </div>
  
  <div class="user-profile">
    <a class="back-home" href="main.php"><-Home</a>
    
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
  <button id="registerBtn" class="register-btn">Register Company</button>
</div>
<?php endif; ?>

<div class="main-content">
  <h1>Registered Biogas Companies</h1>

  <div class="search-bar">
    <form method="GET">
      <input
        type="text"
        name="search"
        placeholder="Search by company name"
        value="<?= htmlspecialchars($searchTerm) ?>"
      >
      <button type="submit">Search</button>
    </form>
  </div>

  <div class="company-container">
    <?php while ($r = mysqli_fetch_assoc($result)): ?>
      <div class="company-box">
        <h2><?= htmlspecialchars($r['companyName']) ?></h2>
        <div class="detail"><span>📍</span><?= htmlspecialchars($r['Location']) ?></div>
        <div class="detail"><span>📞</span><?= htmlspecialchars($r['PhoneNumber']) ?></div>
        <div class="detail"><span>📦</span><?= (int)$r['Quantity'] ?> kg</div>
        <div class="detail"><span>💰</span>₹<?= number_format($r['Price'],2) ?></div>
        <a
          href="https://www.google.com/maps/search/?api=1&query=<?= urlencode($r['Location']) ?>"
          class="detail"
          target="_blank"
        ><span>🗺️</span>View on Map</a>
        <?php if ($r['username'] === $username): ?>
          <a href="edit_biogas.php" class="edit-btn">Edit</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>

  <?php if (!$userHasCompany): ?>
    <div id="modalOverlay" class="modal-overlay">
      <div id="register">
        <h2>Register Your Biogas Plant</h2>
        <form method="POST">
          <input type="text" name="companyName" placeholder="Company Name" required>
          <input type="text" name="phoneNumber" placeholder="Phone Number" required>
          <input type="text" name="location" placeholder="Location" required>
          <input type="number" name="quantity" placeholder="Quantity (kg)" required>
          <input type="number" name="price" placeholder="Price (₹)" required>
          <div style="display:flex; gap:10px; margin-top:10px;">
            <button type="submit" name="submit" style="flex:1;">Register</button>
            <button type="button" id="cancelRegister" style="flex:1;">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
  document.getElementById('registerBtn')?.addEventListener('click', function(){
    document.getElementById('modalOverlay').style.display = 'flex';
  });
  
  document.getElementById('cancelRegister')?.addEventListener('click', function(){
    document.getElementById('modalOverlay').style.display = 'none';
  });
  
  // Close modal when clicking outside the form
  document.getElementById('modalOverlay')?.addEventListener('click', function(event) {
    if (event.target === this) {
      this.style.display = 'none';
    }
  });
</script>

</body>
</html>