<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'companydetails';
$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the user's company details
$query = "SELECT * FROM biogasplant WHERE username='$username'";
$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Error in query: " . mysqli_error($conn));
}

$companyData = mysqli_fetch_assoc($result);

// If no company is registered for the user, redirect
if (!$companyData) {
    header("Location: sell_biogas.php");
    exit();
}

// Handle the update
if (isset($_POST['update'])) {
    $companyName = $_POST['companyName'];
    $phoneNumber = $_POST['phoneNumber'];
    $location = $_POST['location'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Update query
    $sql = "UPDATE biogasplant SET 
                companyName='$companyName', 
                PhoneNumber='$phoneNumber', 
                Location='$location', 
                Quantity='$quantity', 
                Price='$price'
            WHERE username='$username'";

    if (mysqli_query($conn, $sql)) {
        // After updating, redirect back to sell_biogas.php
        header("Location: sell_biogas.php");
        exit();
    } else {
        echo "<div class='error'>❌ Error: " . mysqli_error($conn) . "</div>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biogas Company Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; color: #333; text-align: center; padding: 20px; }
        .container { width: 400px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        input, button { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        button { background: #4caf50; color: white; cursor: pointer; transition: 0.3s; border: none; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Your Company Details</h2>
        <form action="#" method="POST">
            <input type="text" name="companyName" placeholder="Company Name" value="<?php echo htmlspecialchars($companyData['companyName']); ?>" required>
            <input type="text" name="phoneNumber" placeholder="Phone Number" value="<?php echo htmlspecialchars($companyData['PhoneNumber']); ?>" required>
            <input type="text" name="location" placeholder="Location" value="<?php echo htmlspecialchars($companyData['Location']); ?>" required>
            <input type="number" name="quantity" placeholder="Quantity (kg)" value="<?php echo htmlspecialchars($companyData['Quantity']); ?>" required>
            <input type="number" name="price" placeholder="Price (₹)" value="<?php echo htmlspecialchars($companyData['Price']); ?>" required>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
