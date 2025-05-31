<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signup.php");
    exit();
}

$username = $_SESSION['username'];

$conn = mysqli_connect('localhost', 'root', '', 'foodsell');
if (!$conn) die("Connection failed: ".mysqli_connect_error());

// Search functionality
$searchTerm = $_GET['search'] ?? '';
$searchTerm = mysqli_real_escape_string($conn, trim($searchTerm));

$sql = $searchTerm
    ? "SELECT * FROM fooditems WHERE companyName LIKE '%$searchTerm%' OR foodName LIKE '%$searchTerm%'"
    : "SELECT * FROM fooditems";

$result = mysqli_query($conn, $sql);

// Fetch reviews for each company
function getReviews($companyName, $conn) {
    $reviewSql = "SELECT * FROM company_reviews WHERE companyName='$companyName'";
    return mysqli_query($conn, $reviewSql);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Food Items for Sale</title>
</head>
<body>
    <h1>Food Items Available for Sale</h1>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by company or food name" value="<?= htmlspecialchars($searchTerm) ?>">
        <button type="submit">Search</button>
    </form>

    <div class="food-items">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="food-item">
                <h2><?= htmlspecialchars($row['foodName']) ?> (<?= htmlspecialchars($row['companyName']) ?>)</h2>
                <p>Quantity: <?= (int)$row['quantity'] ?> kg</p>
                <p>Price: ₹<?= number_format($row['price'], 2) ?></p>
                <p>Expiration Date: <?= htmlspecialchars($row['expirationDate']) ?></p>

                <h3>Reviews:</h3>
                <?php
                    $reviews = getReviews($row['companyName'], $conn);
                    while ($review = mysqli_fetch_assoc($reviews)) {
                        echo "<p><strong>" . htmlspecialchars($review['username']) . ":</strong> " . htmlspecialchars($review['review']) . " (Rating: " . $review['rating'] . "/5)</p>";
                    }
                ?>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
