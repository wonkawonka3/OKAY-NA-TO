<?php
session_start(); require_once __DIR__ . '/db_connection.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); 
}


if (isset($_POST['signup'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

   
    echo "Name: $name <br> Email: $email <br> Password: " . (!empty($password) ? 'Received' : 'Not received');

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        require_once 'db_connection.php'; 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Sign-up successful! You can now log in.'); window.location.href='customer_login.php';</script>";
        } else {
            echo "<script>alert('Error: Email already exists.');</script>";
        }
    }
}



if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id, full_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $full_name, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["user_name"] = $full_name;
        header("Location: customer_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script>
        function toggleForm(formType) {
            if (formType === 'signup') {
                document.getElementById('signup-form').style.display = 'block';
                document.getElementById('login-form').style.display = 'none';
            } else {
                document.getElementById('signup-form').style.display = 'none';
                document.getElementById('login-form').style.display = 'block';
            }
        }
    </script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="container text-center">
        <h2>Welcome to Hotel Management</h2>


        <div id="login-form">
            <h3>Login</h3>
            <form method="POST">
                <input type="email" name="email" placeholder="Email" required class="form-control"><br>
                <input type="password" name="password" placeholder="Password" required class="form-control"><br>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="#" onclick="toggleForm('signup')">Sign Up</a></p>
        </div>


        <div id="signup-form" style="display:none;">
            <h3>Sign Up</h3>
            <form action="customer_login.php" method="post">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="signup">Sign Up</button>
</form>

            <p>Already have an account? <a href="#" onclick="toggleForm('login')">Login</a></p>
        </div>
    </div>

</body>
</html>
