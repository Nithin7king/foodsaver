<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$username = $_SESSION['username'];

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'foodcompanydetails');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle the food item submission
if (isset($_POST['submit'])) {
    $foodName = mysqli_real_escape_string($conn, $_POST['foodName']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $sql = "INSERT INTO fooditems (foodName, quantity, price, location, username) 
            VALUES ('$foodName', $quantity, $price, '$location', '$username')";
    if (mysqli_query($conn, $sql)) {
        header("Location: sell_food.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Item</title>
</head>
<body>
    <h1>Add Your Food Item</h1>
    <form action="#" method="POST">
        <input type="text" name="foodName" placeholder="Food Name" required><br>
        <input type="number" name="quantity" placeholder="Quantity" required><br>
        <input type="number" name="price" placeholder="Price" required><br>
        <input type="text" name="location" placeholder="Location" required><br>
        <button type="submit" name="submit">Add Food Item</button>
    </form>
</body>
</html>
