<?php
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'foody';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch food items from 'details' table
$query = "SELECT * FROM details ORDER BY discount DESC";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discounted Food</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f4f4;
            text-align: center;
        }
        .container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .food-item {
            border-bottom: 1px solid #ddd;
            padding: 15px;
        }
        .food-item img {
            max-width: 100px;
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Buy Discounted Food</h1>
        <p>Save money and help reduce food waste.</p>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='food-item'>";
                echo "<img src='uploads/" . htmlspecialchars($row['image']) . "' alt='Food Image'>";
                echo "<h3>" . htmlspecialchars($row['name']) . " - " . htmlspecialchars($row['discount']) . "% Off</h3>";
                echo "<p><strong>Restaurant:</strong> " . htmlspecialchars($row['restaurant_name']) . "</p>";
                echo "<p><strong>Location:</strong> " . htmlspecialchars($row['location']) . "</p>";
                echo "<p><strong>Price:</strong> ₹" . htmlspecialchars($row['price']) . "</p>";
                echo "<p><strong>Quantity Available:</strong> " . htmlspecialchars($row['quantity']) . "</p>";
                echo "<a href='order.php?food_id=" . $row['id'] . "' class='btn'>Order Now</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No discounted food available at the moment.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
