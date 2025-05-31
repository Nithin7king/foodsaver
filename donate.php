<?php
// donate.php
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

// handle search
$searchTerm = isset($_GET['search'])
    ? mysqli_real_escape_string($conn, trim($_GET['search']))
    : '';
$sql = $searchTerm
    ? "SELECT * FROM food WHERE companyName LIKE '%$searchTerm%'"
    : "SELECT * FROM food";
$result = mysqli_query($conn, $sql);

// check if user already has a donation
$check = mysqli_query($conn,
    "SELECT 1 FROM food WHERE username='$username'"
);
$userHasDonation = mysqli_num_rows($check) > 0;

// handle registration
if (isset($_POST['submit'])) {
    $c = mysqli_real_escape_string($conn, $_POST['companyName']);
    $p = mysqli_real_escape_string($conn, $_POST['phoneNumber']);
    $l = mysqli_real_escape_string($conn, $_POST['location']);
    // quantity and price removed

    // image upload
    $img = '';
    if (!empty($_FILES['image']['tmp_name'])) {
        $d = 'uploads/';
        if (!is_dir($d)) mkdir($d, 0755, true);
        $img = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], "$d$img");
    }

    // insert into food table
    $insert = "
      INSERT INTO food
        (companyName, PhoneNumber, Location, username, image)
      VALUES
        ('$c','$p','$l','$username','$img')
    ";
    mysqli_query($conn, $insert);
    header("Location: donate.php");
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Food Donations</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
    rel="stylesheet"
  >
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    body { 
      font-family:'Poppins',sans-serif; 
      background:#f0f2f5; 
      padding:0; 
      margin:0;
      position: relative;
    }
    
    h1 { 
      color:#4caf50; 
      text-align:center; 
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
    
    .search-bar { 
      text-align:center; 
      margin:20px auto;
      max-width: 600px;
    }
    
    .search-bar input {
      width:70%;
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
    
    .company-container {
      display:flex; 
      flex-wrap:wrap; 
      gap:20px;
      justify-content:center;
    }
    
    .company-box {
      width:320px; 
      background:#fff; 
      padding:20px;
      border-radius:10px; 
      box-shadow:0 4px 10px rgba(0,0,0,0.1);
      text-align:center;
    }
    
    .company-box img {
      width:100%; 
      height:200px; 
      object-fit:cover;
      border-radius:8px; 
      margin-bottom:12px;
    }
    
    .company-box h2 { 
      margin:10px 0; 
    }
    
    .company-box div { 
      margin:6px 0; 
      font-size:14px; 
    }
    
    .company-box a { 
      text-decoration:none; 
      display:inline-block; 
      margin-top:10px; 
    }
    
    .edit-btn {
      background:#f39c12; 
      color:#fff; 
      padding:8px 12px;
      border-radius:5px; 
      text-decoration:none;
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
    <div class="page-title">Food Donations</div>
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
<?php if (!$userHasDonation): ?>
<div class="register-btn-container">
  <button id="showRegister" class="register-btn">Register Donation</button>
</div>
<?php endif; ?>

<div class="main-content">
  <h1>Available Food Donations</h1>

  <div class="search-bar">
    <form method="GET">
      <input
        type="text" name="search"
        placeholder="Search by company name"
        value="<?= htmlspecialchars($searchTerm) ?>"
      >
      <button type="submit">Search</button>
    </form>
  </div>

  <div class="company-container">
    <?php while ($r = mysqli_fetch_assoc($result)): ?>
      <?php $loc = urlencode($r['Location']); ?>
      <div class="company-box">
        <?php if ($r['image']): ?>
          <img src="uploads/<?= htmlspecialchars($r['image']) ?>" alt="">
        <?php endif; ?>
        <h2><?= htmlspecialchars($r['companyName']) ?></h2>
        <div>📍 <?= htmlspecialchars($r['Location']) ?></div>
        <div>📞 <?= htmlspecialchars($r['PhoneNumber']) ?></div>
        <a
          href="https://www.google.com/maps/search/?api=1&query=<?= $loc ?>"
          target="_blank"
        >🗺️ View</a>
        <?php if ($r['username'] === $username): ?>
          <a href="editdonate.php" class="edit-btn">Edit</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>

  <?php if (!$userHasDonation): ?>
    <div id="modalOverlay" class="modal-overlay">
      <div id="register">
        <h2>Register Your Food Donation</h2>
        <form method="POST" enctype="multipart/form-data">
          <input type="text" name="companyName" placeholder="Company Name" required>
          <input type="text" name="phoneNumber" placeholder="Phone Number" required>
          <input type="text" name="location" placeholder="Location" required>
          <!-- Quantity and Price fields removed -->
          <input type="file" name="image" accept="image/*" required>
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
  document.getElementById('showRegister')?.addEventListener('click', function(){
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
