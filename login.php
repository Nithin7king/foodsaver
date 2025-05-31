<?php 
session_start();

$host = 'localhost'; 
$user = 'root'; 
$pass = ''; 
$dbname = 'mytutur'; 

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['submit'])) { 
    $name = $_POST['name']; 
    $email = $_POST['email']; 
    $mobile = $_POST['mobile']; 
    $city = $_POST['city']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure Password Hashing

    $sql = "INSERT INTO student(name, email, mobile, city, password) VALUES ('$name', '$email', '$mobile', '$city', '$password')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['username'] = $name;
        header("Location: main.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Food Saver Platform</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Beautiful Background */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Form Container */
        .signup-container {
            background: #fff;
            padding: 20px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in;
        }

        h1 {
            color: #4caf50;
            margin-bottom: 20px;
            font-size: 28px;
        }

        /* Input Styles */
        input[type="text"], input[type="email"], input[type="number"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: 0.3s ease;
        }

        input:focus {
            border-color: #4caf50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.8);
        }

        /* Button Styling */
        input[type="submit"] {
            width: 100%;
            background: #4caf50;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #45a049;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Back to Home Link */
        .back-home {
            margin-top: 15px;
            display: inline-block;
            color: #4caf50;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-home:hover {
            color: #45a049;
        }
        a{
             color: #45a049;
             font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="signup-container">
        <h1>Sign Up for Food Saver</h1>
        <form action="#" method="POST">
            <input type="text" name="name" placeholder="Enter Your Name" required><br> 
            <input type="email" name="email" placeholder="Enter Your Email" required><br> 
            <input type="number" name="mobile" placeholder="Enter Your Mobile" required><br> 
            <input type="text" name="city" placeholder="Enter Your City" required><br> 
            <input type="password" name="password" placeholder="Create Password" required><br> 
            <input type="submit" name="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="signin.php">Login</a></p>
        <a class="back-home" href="main.php">← Back to Home</a>
    </div>
</body>
</html>
