<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$dbUser = "root";
$dbPass = "";
$dbname = "hotel_management";


$conn = new mysqli($servername, $dbUser, $dbPass, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = isset($_POST['name']) ? trim($_POST['name']) : null;
    $username = isset($_POST['username']) ? trim($_POST['username']) : null;
    $email    = isset($_POST['email']) ? trim($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;


    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        die("All fields are required. Please fill out the form completely.");
    }

  
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("Username or Email already in use. Please choose a different one.");
    }
    $stmt->close();

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ssss", $name, $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "Registration successful! <a href='login_.php'>Login here</a>";
    } else {
        die("Error: " . $stmt->error);
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Hotel Maya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card p-4 shadow" style="width: 400px;">
        <h3 class="text-center">Sign Up</h3>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-control" id="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-success w-100 bg-primary">Sign Up</button>
        </form>
        <p class="text-center mt-3">Already have an account? <a href="login_.php">Login</a></p>
    </div>
</div>
</body>
</html>
